<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Services\Tenants\TenantService;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct(
        private TenantService $tenantService
    ) {}

    public function index(Request $request)
    {
        $query = $request->input('search');
        $tenants = $query 
            ? $this->tenantService->searchTenants($query)
            : Client::with('contracts')->paginate(15);
        
        return view('property_management.tenants.index', compact('tenants', 'query'));
    }

    public function create(Request $request)
    {
        $returnTo = $request->input('return_to');
        $contractData = $request->input('contract_data');
        
        return view('property_management.tenants.create', compact('returnTo', 'contractData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_type' => 'required|in:فرد,شركة',
            'id_number_or_cr' => 'required|string|unique:clients,id_number_or_cr',
            'id_type' => 'nullable|string',
            'nationality' => 'nullable|string',
            'email' => 'nullable|email|unique:clients,email',
            'mobile' => 'required|string|unique:clients,mobile',
            'national_address' => 'nullable|string',
        ]);

        try {
            $tenant = $this->tenantService->createTenant($validated);
            
            // Check if we should return to contract creation page
            $returnTo = $request->input('return_to');
            if ($returnTo === 'contract') {
                return redirect()->route('property-management.contracts.create')
                    ->with('success', 'تم إضافة العميل بنجاح')
                    ->with('new_client_id', $tenant->id);
            }
            
            return redirect()->route('property-management.tenants.index')
                ->with('success', 'Tenant created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $tenant = Client::with(['contracts.unit.building', 'contracts.rentPayments'])->findOrFail($id);
        $statement = $this->tenantService->getAccountStatement($tenant);
        
        return view('property_management.tenants.show', compact('tenant', 'statement'));
    }

    public function destroy($id)
    {
        try {
            $tenant = Client::findOrFail($id);
            
            // Check if tenant has contracts
            if ($tenant->contracts()->count() > 0) {
                return redirect()->route('property-management.tenants.index')
                    ->with('error', 'لا يمكن حذف المستأجر لأنه مرتبط بعقود');
            }
            
            $tenant->delete();
            return redirect()->route('property-management.tenants.index')
                ->with('success', 'تم حذف المستأجر بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('property-management.tenants.index')
                ->with('error', 'حدث خطأ أثناء حذف المستأجر: ' . $e->getMessage());
        }
    }
}

