<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\PasswordExpiryNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendPasswordExpiryNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:expiry-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications to users whose passwords are about to expire';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Checking for users with expiring passwords...');

        $users = User::where('status', 'active')->get();
        $expiryDays = 180;

        foreach ($users as $user) {
            if ($user->password_changed_at) {
                $daysSinceChange = Carbon::parse($user->password_changed_at)->diffInDays(Carbon::now());
                $daysRemaining = $expiryDays - $daysSinceChange;

                // Notify if password will expire in 10 days or less
                if ($daysRemaining <= 10 && $daysRemaining > 0) {
                    Mail::to($user->email)->queue(new PasswordExpiryNotification($user, $daysRemaining));
                    $this->info("Notification sent to: {$user->email} (Expires in $daysRemaining days)");
                }
            }
        }

        $this->info('Password expiry check completed.');
    }
}
