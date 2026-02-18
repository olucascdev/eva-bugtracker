<?php

use App\Models\User;
use Filament\Notifications\Notification;

$user = User::find(2); // Client
\Illuminate\Support\Facades\Log::info('Testing notification for user: '.$user->id);

try {
    Notification::make()
        ->title('Test Notification')
        ->body('This is a test notification.')
        ->success()
        ->sendToDatabase($user);

    echo "Notification sent to database.\n";
} catch (\Exception $e) {
    echo 'Error sending notification: '.$e->getMessage()."\n";
    \Illuminate\Support\Facades\Log::error('Error sending notification: '.$e->getMessage());
}
