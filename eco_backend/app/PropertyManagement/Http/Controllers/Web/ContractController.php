<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\PropertyManagement\Models\Broker;
use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Models\Unit;
use App\PropertyManagement\Services\Contracts\ContractService;
use App\PropertyManagement\Services\Payments\PaymentService;
use App\PropertyManagement\Support\ReceiptStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function __construct(
        private ContractService $contractService,
        private PaymentService $paymentService
    ) {}

    public function index(Request $request)
    {
        $query = \App\PropertyManagement\Models\Contract::with(['building', 'unit', 'client']);

        // Filter by building if provided
        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        // Show all contracts by default (removed the active contracts filter)
        // Only filter if explicitly requested
        if ($request->filled('show_active_only')) {
            $today = now()->toDateString();
            $query->where('start_date', '<=', $today)
                  ->where('end_date', '>=', $today);
        }

        $contracts = $query->orderBy('created_at', 'desc')->get();
        $buildings = \App\Models\Building::all(['id', 'name']);

        return view('property_management.contracts.index', compact('contracts', 'buildings'));
    }

    public function create(Request $request)
    {
        // Prevent viewer role from creating contracts
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.index')
                ->with('error', 'ليس لديك صلاحية لإنشاء عقود');
        }

        $buildings = Building::all(['id', 'name']);

        // Get only units that don't have active contracts
        $units = Unit::with('building')
            ->whereDoesntHave('contracts', function($query) {
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            })
            ->get();

        $clients = Client::all(['id', 'name', 'client_type']);
        $brokers = Broker::all(['id', 'name']);

        // Clear new_client_id from session after using it
        $newClientId = session('new_client_id');
        if ($newClientId) {
            session()->forget('new_client_id');
        }

        return view('property_management.contracts.create', compact('buildings', 'units', 'clients', 'brokers', 'newClientId'));
    }

    public function store(Request $request)
    {
        // Prevent viewer role from storing contracts
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.index')
                ->with('error', 'ليس لديك صلاحية لإنشاء عقود');
        }

        $validated = $request->validate([
            'contract_type' => 'required|in:جديد,مجدد',
            'building_id' => 'required|exists:buildings,id',
            'unit_id' => 'required|exists:units,id',
            'client_id' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'contract_signing_date' => 'nullable|date',
            'is_conditional' => 'boolean',
            'total_rent' => 'required|numeric|min:0',
            'annual_rent' => 'required|numeric|min:0',
            'deposit_amount' => 'numeric|min:0',
            'rent_cycle' => 'required|integer|min:1',
            'vat_amount' => 'numeric|min:0',
            'general_services_amount' => 'numeric|min:0',
            'fixed_amounts' => 'numeric|min:0',
            'insurance_policy_number' => 'nullable|string',
            'broker_id' => 'nullable|exists:brokers,id',
        ]);

        // Check if unit has an active contract
        $activeContract = \App\PropertyManagement\Models\Contract::where('unit_id', $validated['unit_id'])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($activeContract) {
            return back()
                ->withInput()
                ->with('error', 'لا يمكن إنشاء عقد جديد لهذه الوحدة لأنها تحتوي على عقد ساري (عقد رقم: ' . $activeContract->contract_number . ')');
        }

        // Check if the new contract dates overlap with any existing contract for this unit
        $overlappingContract = \App\PropertyManagement\Models\Contract::where('unit_id', $validated['unit_id'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                      });
            })
            ->first();

        if ($overlappingContract) {
            return back()
                ->withInput()
                ->with('error', 'لا يمكن إنشاء عقد جديد لأن التواريخ تتداخل مع عقد موجود (عقد رقم: ' . $overlappingContract->contract_number . ')');
        }

        try {
            $contract = $this->contractService->createContract($validated, []);

            // Activity is automatically logged by Spatie via LogsActivity trait in Contract model

            return redirect()->route('property-management.contracts.index')
                ->with('success', 'تم إنشاء العقد بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $contract = \App\PropertyManagement\Models\Contract::with([
            'building', 'unit', 'client', 'broker', 'representatives', 'rentPayments', 'invoices', 'receiptVouchers'
        ])->findOrFail($id);

        $dueAmounts = $this->contractService->calculateDueAmounts($contract);

        return view('property_management.contracts.show', compact('contract', 'dueAmounts'));
    }

    public function edit($id)
    {
        // Prevent viewer role from editing contracts
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'ليس لديك صلاحية لتعديل العقود');
        }

        $contract = \App\PropertyManagement\Models\Contract::findOrFail($id);
        $buildings = Building::all(['id', 'name']);

        // Get all units (including the current contract's unit)
        $units = Unit::with('building')->get();

        $clients = Client::all(['id', 'name', 'client_type']);
        $brokers = Broker::all(['id', 'name']);

        return view('property_management.contracts.edit', compact('contract', 'buildings', 'units', 'clients', 'brokers'));
    }

    public function update(Request $request, $id)
    {
        // Prevent viewer role from updating contracts
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'ليس لديك صلاحية لتعديل العقود');
        }

        $validated = $request->validate([
            'contract_type' => 'required|in:جديد,مجدد',
            'building_id' => 'required|exists:buildings,id',
            'unit_id' => 'required|exists:units,id',
            'client_id' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'contract_signing_date' => 'nullable|date',
            'is_conditional' => 'boolean',
            'total_rent' => 'required|numeric|min:0',
            'annual_rent' => 'required|numeric|min:0',
            'deposit_amount' => 'numeric|min:0',
            'rent_cycle' => 'required|integer|min:1',
            'vat_amount' => 'numeric|min:0',
            'general_services_amount' => 'numeric|min:0',
            'fixed_amounts' => 'numeric|min:0',
            'insurance_policy_number' => 'nullable|string',
            'broker_id' => 'nullable|exists:brokers,id',
        ]);

        try {
            $contract = \App\PropertyManagement\Models\Contract::findOrFail($id);

            // Check if unit has an active contract (excluding current contract)
            $activeContract = \App\PropertyManagement\Models\Contract::where('unit_id', $validated['unit_id'])
                ->where('id', '!=', $id)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($activeContract) {
                return back()
                    ->withInput()
                    ->with('error', 'لا يمكن تعديل العقد لأن الوحدة تحتوي على عقد ساري آخر (عقد رقم: ' . $activeContract->contract_number . ')');
            }

            // Check if the new contract dates overlap with any existing contract for this unit (excluding current contract)
            $overlappingContract = \App\PropertyManagement\Models\Contract::where('unit_id', $validated['unit_id'])
                ->where('id', '!=', $id)
                ->where(function($query) use ($validated) {
                    $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                          ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('start_date', '<=', $validated['start_date'])
                                ->where('end_date', '>=', $validated['end_date']);
                          });
                })
                ->first();

            if ($overlappingContract) {
                return back()
                    ->withInput()
                    ->with('error', 'لا يمكن تعديل العقد لأن التواريخ تتداخل مع عقد موجود (عقد رقم: ' . $overlappingContract->contract_number . ')');
            }

            $contract->update($validated);

            // Activity is automatically logged by Spatie via LogsActivity trait in Contract model

            return redirect()->route('property-management.contracts.show', $id)
                ->with('success', 'تم تحديث العقد بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث العقد: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        // Prevent viewer role from deleting contracts
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.index')
                ->with('error', 'ليس لديك صلاحية لحذف العقود');
        }

        $request->validate([
            'contract_ids' => 'required|array',
            'contract_ids.*' => 'exists:contracts,id',
        ]);

        try {
            $contractIds = $request->input('contract_ids');
            $contracts = \App\PropertyManagement\Models\Contract::whereIn('id', $contractIds)->get();

            $deletedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($contracts as $contract) {
                try {
                    // Delete all related records first
                    // Delete rent payments
                    $contract->rentPayments()->delete();

                    // Delete representatives
                    $contract->representatives()->delete();

                    // Delete transactions
                    $contract->transactions()->delete();

                    // Activity is automatically logged by Spatie via LogsActivity trait in Contract model

                    // Delete contract
                    $contract->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $skippedCount++;
                    $errors[] = "خطأ في حذف عقد {$contract->contract_number}: " . $e->getMessage();
                }
            }

            $message = "تم حذف {$deletedCount} عقد بنجاح";
            if ($skippedCount > 0) {
                $message .= " (تم تخطي {$skippedCount} عقد)";
                if (!empty($errors)) {
                    session()->flash('errors', $errors);
                }
            }

            return redirect()->route('property-management.contracts.index')
                ->with($skippedCount > 0 ? 'warning' : 'success', $message);
        } catch (\Exception $e) {
            return redirect()->route('property-management.contracts.index')
                ->with('error', 'حدث خطأ أثناء حذف العقود: ' . $e->getMessage());
        }
    }

    public function markPaymentAsPaid(Request $request, $contractId, $paymentId)
    {
        // Prevent viewer role from marking payments as paid
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.show', $contractId)
                ->with('error', 'ليس لديك صلاحية لتسجيل السداد');
        }

        $request->validate([
            'receipt_image' => 'required|mimes:pdf,jpg,jpeg,png,gif,webp|max:10240',
            'payment_notes' => 'nullable|string|max:2000',
        ]);

        try {
            $contract = \App\PropertyManagement\Models\Contract::findOrFail($contractId);
            $payment = RentPayment::where('contract_id', $contractId)
                ->where('id', $paymentId)
                ->firstOrFail();

            if ($payment->status === 'paid') {
                return redirect()->route('property-management.contracts.show', $contractId)
                    ->with('warning', 'هذه الدفعة مدفوعة بالفعل');
            }

            $file = $request->file('receipt_image');
            $extension = strtolower($file->getClientOriginalExtension() ?: 'pdf');
            $fileName = 'receipt_' . $contract->contract_number . '_' . $paymentId . '_' . time() . '.' . $extension;
            $path = $file->storeAs('receipts', $fileName, 'public');

            // Update payment with receipt image path and notes
            $payment->receipt_image_path = $path;
            $payment->notes = $request->input('payment_notes');
            $payment->save();

            $result = $this->paymentService->markPaymentAsPaid($payment);

            // Log custom activity for payment
            try {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($contract)
                    ->withProperties([
                        'ip_address' => request()->ip(),
                        'payment_id' => $payment->id,
                        'action' => 'mark_payment_paid',
                    ])
                    ->log("تم تسجيل سداد دفعة - العقد: {$contract->contract_number}");
            } catch (\Exception $e) {
                // Silently fail if activity_log table doesn't exist
            }

            return redirect()->route('property-management.contracts.show', $contractId)
                ->with('success', "تم تسجيل السداد بنجاح! تم إنشاء الفاتورة {$result['invoice']->invoice_number} وسند القبض {$result['receipt_voucher']->receipt_number}");
        } catch (\Exception $e) {
            return redirect()->route('property-management.contracts.show', $contractId ?? 0)
                ->with('error', 'حدث خطأ أثناء تسجيل السداد: ' . $e->getMessage());
        }
    }

    /**
     * View payment receipt (PDF or image)
     */
    public function viewReceipt($id, $paymentId)
    {
        $payment = null;

        try {
            \App\PropertyManagement\Models\Contract::findOrFail($id);
            $payment = RentPayment::where('contract_id', $id)
                ->where('id', $paymentId)
                ->firstOrFail();

            if (!$payment->receipt_image_path) {
                abort(404, 'لا يوجد إيصال مرفق لهذه الدفعة');
            }

            $filePath = ReceiptStorage::resolveAbsolutePath($payment->receipt_image_path);
            if (!$filePath) {
                \Illuminate\Support\Facades\Log::warning('Receipt file missing on server', [
                    'contract_id' => $id,
                    'payment_id' => $paymentId,
                    'stored_path' => $payment->receipt_image_path,
                    'normalized' => ReceiptStorage::normalizePath($payment->receipt_image_path),
                    'checked_paths' => ReceiptStorage::candidateAbsolutePaths($payment->receipt_image_path),
                ]);
                abort(404, 'ملف الإيصال غير موجود على الخادم. تأكد من رفع الملف أو من إعداد SHARED_STORAGE_PUBLIC_ROOT على Forge.');
            }

            $filename = basename($payment->receipt_image_path);
            $mimeType = $this->receiptMimeType($payment->receipt_image_path);

            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error viewing receipt', [
                'contract_id' => $id,
                'payment_id' => $paymentId,
                'path' => $payment?->receipt_image_path,
                'message' => $e->getMessage(),
            ]);
            abort(404, 'حدث خطأ أثناء عرض الإيصال');
        }
    }

    /**
     * Upload or replace a payment receipt file (e.g. DB path exists but file missing on disk).
     */
    public function uploadPaymentReceipt(Request $request, $id, $paymentId)
    {
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'ليس لديك صلاحية لرفع الإيصال');
        }

        $request->validate([
            'receipt_image' => 'required|mimes:pdf,jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $contract = \App\PropertyManagement\Models\Contract::findOrFail($id);
        $payment = RentPayment::where('contract_id', $id)
            ->where('id', $paymentId)
            ->firstOrFail();

        $disk = Storage::disk('public');
        $oldPath = $payment->receipt_image_path;
        if ($oldPath) {
            $oldAbsolute = ReceiptStorage::resolveAbsolutePath($oldPath);
            if ($oldAbsolute && is_file($oldAbsolute)) {
                @unlink($oldAbsolute);
            } elseif ($disk->exists($oldPath)) {
                $disk->delete($oldPath);
            }
        }

        $file = $request->file('receipt_image');
        $extension = strtolower($file->getClientOriginalExtension() ?: 'pdf');
        $fileName = 'receipt_' . $contract->contract_number . '_' . $paymentId . '_' . time() . '.' . $extension;
        $path = $file->storeAs('receipts', $fileName, 'public');

        $payment->receipt_image_path = $path;
        $payment->save();

        return redirect()->route('property-management.contracts.show', $id)
            ->with('success', 'تم رفع الإيصال بنجاح.');
    }

    private function receiptMimeType(string $path): string
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }

    /**
     * Upload contract PDF
     */
    public function uploadPdf(Request $request, $id)
    {
        // Prevent viewer role from uploading PDFs
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'ليس لديك صلاحية لرفع الملفات');
        }

        $request->validate([
            'contract_pdf' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        try {
            $contract = \App\PropertyManagement\Models\Contract::findOrFail($id);

            // Delete old PDF if exists
            if ($contract->contract_pdf_path && Storage::disk('public')->exists($contract->contract_pdf_path)) {
                Storage::disk('public')->delete($contract->contract_pdf_path);
            }

            // Store new PDF
            $file = $request->file('contract_pdf');
            $fileName = 'contract_' . $contract->contract_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('contracts', $fileName, 'public');

            // Update contract
            $contract->contract_pdf_path = $path;
            $contract->save();

            // Log custom activity for PDF upload
            try {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($contract)
                    ->withProperties([
                        'ip_address' => request()->ip(),
                        'action' => 'upload_pdf',
                    ])
                    ->log("تم رفع ملف PDF للعقد: {$contract->contract_number}");
            } catch (\Exception $e) {
                // Silently fail if activity_log table doesn't exist
            }

            return redirect()->route('property-management.contracts.show', $id)
                ->with('success', 'تم رفع ملف العقد بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage());
        }
    }

    /**
     * View contract PDF in browser
     */
    public function viewPdf($id)
    {
        try {
            $contract = \App\PropertyManagement\Models\Contract::findOrFail($id);

            if (!$contract->contract_pdf_path) {
                return redirect()->route('property-management.contracts.show', $id)
                    ->with('error', 'لا يوجد ملف PDF مرفق لهذا العقد');
            }

            $filePath = storage_path('app/public/' . $contract->contract_pdf_path);

            if (!file_exists($filePath)) {
                return redirect()->route('property-management.contracts.show', $id)
                    ->with('error', 'الملف غير موجود في النظام');
            }

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'حدث خطأ أثناء عرض الملف: ' . $e->getMessage());
        }
    }

    /**
     * Download contract PDF
     */
    public function downloadPdf($id)
    {
        try {
            $contract = \App\PropertyManagement\Models\Contract::findOrFail($id);

            if (!$contract->contract_pdf_path) {
                return redirect()->route('property-management.contracts.show', $id)
                    ->with('error', 'لا يوجد ملف PDF مرفق لهذا العقد');
            }

            $filePath = storage_path('app/public/' . $contract->contract_pdf_path);

            if (!file_exists($filePath)) {
                return redirect()->route('property-management.contracts.show', $id)
                    ->with('error', 'الملف غير موجود في النظام');
            }

            return response()->download($filePath, 'contract_' . $contract->contract_number . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'حدث خطأ أثناء تحميل الملف: ' . $e->getMessage());
        }
    }

    /**
     * Delete contract PDF
     */
    public function deletePdf($id)
    {
        // Prevent viewer role from deleting PDFs
        if (auth()->user()->role === 'viewer') {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'ليس لديك صلاحية لحذف الملفات');
        }

        try {
            $contract = \App\PropertyManagement\Models\Contract::findOrFail($id);

            if (!$contract->contract_pdf_path) {
                return redirect()->route('property-management.contracts.show', $id)
                    ->with('error', 'لا يوجد ملف PDF مرفق لهذا العقد');
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($contract->contract_pdf_path)) {
                Storage::disk('public')->delete($contract->contract_pdf_path);
            }

            // Update contract
            $contract->contract_pdf_path = null;
            $contract->save();

            // Log custom activity for PDF deletion
            try {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($contract)
                    ->withProperties([
                        'ip_address' => request()->ip(),
                        'action' => 'delete_pdf',
                    ])
                    ->log("تم حذف ملف PDF للعقد: {$contract->contract_number}");
            } catch (\Exception $e) {
                // Silently fail if activity_log table doesn't exist
            }

            return redirect()->route('property-management.contracts.show', $id)
                ->with('success', 'تم حذف ملف العقد بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('property-management.contracts.show', $id)
                ->with('error', 'حدث خطأ أثناء حذف الملف: ' . $e->getMessage());
        }
    }
}

