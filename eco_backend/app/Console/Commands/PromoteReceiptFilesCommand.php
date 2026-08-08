<?php

namespace App\Console\Commands;

use App\PropertyManagement\Models\RentPayment;
use App\PropertyManagement\Support\ReceiptStorage;
use Illuminate\Console\Command;

class PromoteReceiptFilesCommand extends Command
{
    protected $signature = 'receipts:promote
                            {--payment= : Rent payment ID}
                            {--path= : Receipt path from DB (e.g. receipts/file.pdf)}
                            {--all : Promote all payments that have receipt_image_path}';

    protected $description = 'Find receipt files in Forge release folders and copy them to shared/active storage';

    public function handle(): int
    {
        $this->line('Forge site root: ' . (ReceiptStorage::forgeSiteRoot() ?? '(not detected)'));
        $this->line('Shared public root: ' . (config('filesystems.shared_public_root') ?: '(not set)'));
        $this->newLine();

        if ($path = $this->option('path')) {
            return $this->promotePath($path) ? self::SUCCESS : self::FAILURE;
        }

        if ($paymentId = $this->option('payment')) {
            $payment = RentPayment::find($paymentId);
            if (!$payment || !$payment->receipt_image_path) {
                $this->error('Payment not found or has no receipt_image_path.');

                return self::FAILURE;
            }

            return $this->promotePath($payment->receipt_image_path, (int) $paymentId) ? self::SUCCESS : self::FAILURE;
        }

        if ($this->option('all')) {
            $payments = RentPayment::whereNotNull('receipt_image_path')->get();
            $ok = 0;
            $fail = 0;

            foreach ($payments as $payment) {
                if ($this->promotePath($payment->receipt_image_path, $payment->id, quiet: true)) {
                    $ok++;
                } else {
                    $fail++;
                    $this->warn("Missing: payment #{$payment->id} → {$payment->receipt_image_path}");
                }
            }

            $this->newLine();
            $this->info("Done. Promoted/found: {$ok}, still missing: {$fail}");

            return $fail === 0 ? self::SUCCESS : self::FAILURE;
        }

        $this->error('Use --payment=ID, --path=receipts/file.pdf, or --all');

        return self::FAILURE;
    }

    private function promotePath(string $storedPath, ?int $paymentId = null, bool $quiet = false): bool
    {
        if (!$quiet) {
            $label = $paymentId ? "Payment #{$paymentId}" : 'Path';
            $this->info("{$label}: {$storedPath}");
        }

        $diag = ReceiptStorage::diagnose($storedPath);

        if (!$quiet) {
            $this->table(
                ['Destination', 'Exists'],
                collect($diag['destinations'])->map(fn ($exists, $dest) => [$dest, $exists ? 'yes' : 'no'])->values()->all()
            );
        }

        if ($diag['found']) {
            if (!$quiet) {
                $this->info('OK: ' . ($diag['source'] ?? 'file available'));
            }

            return true;
        }

        if (!$quiet) {
            $this->error('File not found in active storage or any release.');
            if ($diag['site_root']) {
                $relative = ReceiptStorage::normalizePath($storedPath);
                $this->line('Searched pattern: ' . $diag['site_root'] . '/releases/*/storage/app/public/' . $relative);
            }
        }

        return false;
    }
}
