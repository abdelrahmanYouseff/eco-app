<?php

namespace App\Console\Commands;

use App\PropertyManagement\Services\Notifications\NotificationService;
use Illuminate\Console\Command;

class GeneratePaymentDueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:generate-payment-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate notifications for payments due in 30 days';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Generating payment due notifications...');
        
        $notificationService->createPaymentDueNotifications();
        
        $this->info('Payment due notifications generated successfully!');
        
        return Command::SUCCESS;
    }
}
