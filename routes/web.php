<?php

use App\Events\TestEvent;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AutoReplyController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentScheduleController;
use App\Http\Controllers\PhoneVerificationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\BillingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\TeamMemberController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('welcome');
// });
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('auth/status', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user'          => auth()->user(),
    ]);
});

Route::get('/csrf-token', function () {
    return Response::json([
        'csrfToken' => csrf_token(),
    ]);
});

Route::get('/team-invitation-accept', function () {
    return Response::json([
        'csrfToken' => csrf_token(),
    ]);
})->name('team.invitation.accept');

Route::get('/broadcast', function () {
    broadcast(new TestEvent('Hello from Reverb!'));
    return 'Event broadcasted!';
});

// Route::get('/', function () {
//     return view('auth.base');
// });

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::get('app/settings', [SettingsController::class, 'all_settings']);
Route::get('auth/active-user', [SettingsController::class, 'get_active_user']);

Route::get('/auth/status', [LoginController::class, 'status']);

Route::prefix('auth')->middleware('guest')->group(function () {

    //Base auth
    Route::get('/', function () {
        return view('auth.base');
    });

    // Sign Up routes
    Route::get('/sign-up', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/sign-up-new-user', [RegisterController::class, 'register'])->name('register-user');
    Route::get('/sign-up-cities/{province}', [RegisterController::class, 'getCities'])->name('register-user-getCities');
    Route::post('/send-email-verification-code', [RegisterController::class, 'sendEmailVerificationCode'])->name('send-email-verification-code');
    Route::post('/verify-email-verification-code', [RegisterController::class, 'verifyEmailCode'])->name('verify-email-verification-code');
    Route::post('/verify-phone-verification-code', [RegisterController::class, 'verifyPhoneCode'])->name('verify-phone-verification-code');
    Route::post('/send-phone-verification-code', [RegisterController::class, 'sendPhoneVerificationCode'])->name('send-phone-verification-code');
    //Verify account
    Route::post('/plaid-verify-account', [VerificationController::class, 'verifyAccount'])->name('plaid-verify-account');
    // Sign In routes
    // Route::get('/sign-in', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/sign-in', [LoginController::class, 'login'])->name('login-user');

    //Reset Password
    Route::get('password/email', [PasswordController::class, 'showResetPasswordForm'])->name('password.send.link');
    Route::post('/password/email', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('password/reset', [PasswordController::class, 'showResetForm'])->name('password.reset.page');
    Route::post('/password/reset', [PasswordController::class, 'reset'])->name('password.reset');
    Route::post('/check-email-exists', [RegisterController::class, 'checkEmailExists'])->name('checkEmailExists');

});

// Route::middleware('auth:sanctum')->group(function () {
//     // Chat Routes
//     Route::get('/chat', function () {
//         return view('chat');
//     })->name('chat');

//     Route::post('/api/conversations', [ChatController::class, 'startConversation']);
//     Route::get('/api/messages', [ChatController::class, 'fetchMessages']);
//     Route::post('/api/messages', [ChatController::class, 'sendMessage']);
//     Route::post('/api/messages/read', [ChatController::class, 'markAsRead']);

// Ticket Routes
Route::prefix('api/tickets')->group(function () {
    // Ticket CRUD
    Route::get('/', [TicketController::class, 'fetchTickets'])->name('tickets.fetch');
    Route::get('/{ticketId}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/', [TicketController::class, 'createTicket'])->name('tickets.create');
    Route::post('/{ticketId}/close', [TicketController::class, 'closeTicket'])->name('tickets.close');
    Route::delete('/{ticketId}', [TicketController::class, 'deleteTicket'])->name('tickets.delete');
    Route::patch('/{ticketId}/restore', [TicketController::class, 'restoreTicket'])->name('tickets.restore');

    // Ticket Messages
    Route::get('/{ticketId}/messages', [TicketController::class, 'fetchMessages'])->name('tickets.messages.fetch');
    Route::post('/messages', [TicketController::class, 'sendMessage'])->name('tickets.messages.send');
    Route::delete('/messages/{messageId}', [TicketController::class, 'deleteMessage'])->name('tickets.messages.delete');
    Route::patch('/messages/{messageId}/restore', [TicketController::class, 'restoreMessage'])->name('tickets.messages.restore');
    Route::get('/api/messages/{message}/download', [TicketController::class, 'downloadFile'])->name('files.download');
});
// });

Route::middleware(['auth:sanctum'])->group(function () {
    // Logout route - does not require OTP middleware
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout-user');

    // // OTP routes - do not require OTP middleware
    // Route::post('auth/otp-resend', [OtpController::class, 'resendOtp'])->name('otp.resend');
    // Route::get('auth/otp-verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
    // Route::post('auth/otp-verify', [OtpController::class, 'verifyOtp'])->name('otp.verify.submit');

    // // Routes requiring both auth and OTP verification
    // // Route::middleware(['otp.verify'])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/chat-us', [ChatController::class, 'index'])->name('chat-us');
    // Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    // Route::get('/billing-details', [BillingController::class, 'index'])->name('billing');
    // Route::get('/wallet', [BillingController::class, 'wallet'])->name('wallet');
    // Route::get('/password', [DashboardController::class, 'password'])->name('password');
    // Route::get('/mortgage-details', [DashboardController::class, 'mortgage'])->name('mortgage');
    // Route::get('/rental-details', [DashboardController::class, 'rental'])->name('rental');
    // Route::get('/credit-advisory', [DashboardController::class, 'advisory'])->name('advisory');
    // Route::get('/calendar/events', [DashboardController::class, 'getCalendarEvents'])->name('getCalendarEvents');

    // Route::post('/checkEmailExists', [DashboardController::class, 'checkEmailExists'])->name('user.checkEmailExists');
    // Route::post('/sendEmailCode', [DashboardController::class, 'sendEmailCode'])->name('user.sendEmailCode');
    // Route::post('/verifyEmailCode', [DashboardController::class, 'verifyEmailCode'])->name('user.verifyEmailCode');
    // // Route::post('/updateUserEmail', [DashboardController::class, 'updateUserEmail'])->name('user.updateUserEmail');

    // Route::post('/updateUserEmail', [DashboardController::class, 'verifyEmailCodeAndUpdateEmail'])->middleware('auth')->name('user.updateUserEmail');

    // // Modal-specific routes
    // Route::prefix('user-modal')->name('modal.user.')->group(function () {
    //     Route::get('setupBill', [ModalController::class, 'setupBill'])->name('setupBill');
    //     Route::get('wallet', [ModalController::class, 'wallet'])->name('wallet');
    // });

    // Other routes requiring both auth and OTP verification can go here
    // });
});

// User Notifications Routes
Route::prefix('notifications')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('notifications.index'); // New index route
    Route::get('/', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/archive', [NotificationController::class, 'archive'])->name('notifications.archive');
    Route::get('/search', [NotificationController::class, 'search'])->name('notifications.search');
    Route::delete('/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
});

// Admin Notifications Routes
Route::prefix('admin/notifications')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [AdminNotificationController::class, 'index'])->name('admin.notifications.index'); // New admin index route
    Route::get('/', [AdminNotificationController::class, 'fetch'])->name('admin.notifications.fetch');
    Route::post('/mark-read', [AdminNotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');
    Route::post('/archive', [AdminNotificationController::class, 'archive'])->name('admin.notifications.archive');
    Route::get('/search', [AdminNotificationController::class, 'search'])->name('admin.notifications.search');
});

Route::prefix('admin')
    ->middleware('admin.guest') // Apply the admin guest middleware
    ->as('admin.')              // Automatically prefix 'admin.' to route names
    ->group(function () {

        // Sign In routes
        Route::get('/sign-in', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/sign-in', [AuthController::class, 'login'])->name('login-admin');

        // Add registration routes here if needed
    });

Route::prefix('admin')
    ->middleware(['admin.auth']) // Apply the admin auth middleware
    ->as('admin.')
    ->group(function () {

        //Export
        Route::get('/export', [ExportController::class, 'export'])->name('export');
        // Route::get('/export/pdf', [ExportController::class, 'exportToPDF'])->name('export.pdf');

        // Support-related routes
        Route::middleware(['admin.role:support'])->group(function () {
            // Route::get('/support', [SupportController::class, 'index'])->name('admin.support');
        });

        // Another role example
        Route::middleware(['admin.role:manager'])->group(function () {
            // Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        });

        Route::resource('/admin-users', AdminController::class)->names([
            'index'   => 'admin-users.index',
            'create'  => 'admin-users.create',
            'store'   => 'admin-users.store',
            'show'    => 'admin-users.show',
            'edit'    => 'admin-users.edit',
            'update'  => 'admin-users.update',
            'destroy' => 'admin-users.destroy',
        ]);;

        // Protected routes
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout-admin');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::delete('/user/{uuid}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/user-list', [UserController::class, 'getUsers'])->name('users.list');
        Route::get('/user/{uuid}', [UserController::class, 'viewUser'])->name('users.view');

        Route::post('/users/{uuid}/approve', [UserController::class, 'approveUser'])->name('admin.users.approve');
        Route::post('/users/{uuid}/reject', [UserController::class, 'rejectUser'])->name('admin.users.reject');
        Route::delete('/users/{uuid}/delete', [UserController::class, 'deleteUser'])->name('admin.users.delete');

        Route::get('/test', [AdminDashboardController::class, 'test'])->name('test');
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::get('/support', [AdminDashboardController::class, 'help'])->name('support');
        Route::get('/onboarding', [AdminDashboardController::class, 'onboarding'])->name('onboarding');

        //Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('report.index');
        Route::post('/report-query-table', [ReportController::class, 'queryTable'])->name('report.queryTable');
        Route::post('/report-export-table', [ReportController::class, 'exportData'])->name('report.exportData');
        Route::post('/report-get-table-columns', [ReportController::class, 'getTableColumns'])->name('report.getTableColumns');
        Route::get('/equifax-report', [ReportController::class, 'equifax'])->name('report.equifax');
        Route::get('/transunion-report', [ReportController::class, 'transunion'])->name('report.transunion');

        //Transaction
        Route::get('/card-transactions', [TransactionController::class, 'card'])->name('transaction.card');
        Route::get('/card-transactions/view/{uuid}', [TransactionController::class, 'card_transaction'])->name('card.transactions.view');
        Route::get('/card-transactions/download/{uuid}', [TransactionController::class, 'card_download'])->name('card.transactions.download');

        Route::get('/wallet-transactions', [TransactionController::class, 'wallet'])->name('wallet.transaction');

        Route::get('/wallet-transactions/export', [TransactionController::class, 'wallet_export'])->name('wallet.transactions.export');

        Route::get('/wallet-transactions/view/{uuid}', [TransactionController::class, 'wallet_view'])->name('wallet.transactions.view');

        Route::get('/wallet-transactions/download/{uuid}', [TransactionController::class, 'wallet_download'])->name('wallet.transactions.download');

        //AutoReply
        Route::resource('auto-replies', AutoReplyController::class);
    });

// Routes for OTP verification, excluding guest middleware but including CheckOtp middleware
// Route::prefix('auth')->group(function () {
// // Route for OTP verification
// Route::get('/verify-otp', [LoginController::class, 'showOtpVerificationForm'])->name('otp.verify');
// Route::post('/verify-otp', [LoginController::class, 'verifyOtp']);
// });

// Route::post('/api/team/members/resend-decline', [TeamMemberController::class, 'declineInvite'])->name('team.invitation.decline');

// Route::get('/api/team/members/accept/{token}', [TeamMemberController::class, 'acceptInvite'])->name('team.invitation.accept');
// Route::get('/api/team/members/decline/{token}', [TeamMemberController::class, 'declineInvite'])->name('team.invitation.decline');
// Route::post('/api/team/members/verify-token', [TeamMemberController::class, 'verifyInvitationToken']);
// Route::post('/api/team/members/complete-invitation', [TeamMemberController::class, 'completeInvitation']);

// For handling email links
Route::post('/api/team/members/decline/{token}', [TeamMemberController::class, 'declineInvite'])->name('team.invitation.decline');
Route::post('/api/team/invitation/{token}/decline', [TeamMemberController::class, 'declineInvitation']);

// For handling registration process
Route::get('/api/team/invitation/{token}', [TeamMemberController::class, 'verifyInvitationToken']);
Route::post('/api/team/members/verify-token', [TeamMemberController::class, 'verifyInvitationToken']);
Route::post('/api/team/invitation/{token}/accept', [TeamMemberController::class, 'completeRegistration']);

Route::post('/api/phone/send-code', [PhoneVerificationController::class, 'sendCode']);
Route::post('/api/phone/verify-code', [PhoneVerificationController::class, 'verifyCode']);

Route::middleware(['checkLaravelAuth'])->group(function () {
    Route::get('api/ping', function (Request $request) {
        return response()->json([
            // 'auth_user' => Auth::user(),
            'status'  => 'success',
            'message' => 'Session active',
        ], 200);
    });

    Route::get('/api/geocode', function (Request $request) {
        $query    = $request->query('query');
        $response = Http::get("https://api.mapbox.com/geocoding/v5/mapbox.places/{$query}.json", [
            'access_token' => env('MAPBOX_TOKEN'),
            'country'      => 'CA',
            'autocomplete' => true,
        ]);
        return $response->json();
    });
    Route::get('/user-data', [DashboardController::class, 'user_details']);

    Route::prefix('/api/team')->group(function () {
        Route::get('/members', [TeamMemberController::class, 'getTeamMembers']);
        Route::post('/members', [TeamMemberController::class, 'addMember']);
        Route::post('/members/admin', [TeamMemberController::class, 'changeAdmin']);
        Route::delete('/members', [TeamMemberController::class, 'removeMember']);
        Route::put('/members/percentages', [TeamMemberController::class, 'updatePercentages']);
        Route::post('/members/resend-invite', [TeamMemberController::class, 'resendInvite'])->name('team.invitation.accept');
        Route::post('/dissolve/code', [TeamMemberController::class, 'sendDissolveCode']);
        Route::post('/dissolve', [TeamMemberController::class, 'dissolveTeam']);
        Route::post('/admin/transfer', [TeamMemberController::class, 'transferAdmin']);
    });

    // Route::prefix('/api/notifications')->middleware(['auth:sanctum'])->group(function () {
    //     Route::get('/', [NotificationController::class, 'index'])->name('notifications.index'); // New index route
    //     Route::get('/', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    //     Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    //     Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    //     Route::post('/archive', [NotificationController::class, 'archive'])->name('notifications.archive');
    //     Route::get('/search', [NotificationController::class, 'search'])->name('notifications.search');
    //     Route::delete('/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
    // });
    Route::prefix('/api/notifications')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [NotificationController::class, 'fetch'])->name('notifications.fetch');
        Route::get('/all', [NotificationController::class, 'fetchAll'])->name('notifications.fetchAll');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::patch('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::post('/{id}/archive', [NotificationController::class, 'archive'])->name('notifications.archive');
        Route::get('/search', [NotificationController::class, 'search'])->name('notifications.search');
        Route::delete('/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    });

    Route::post('/api/cards', [BillingController::class, 'store']);
    Route::post('/api/cards/{cardId}/delete', [BillingController::class, 'deleteCard']);
    Route::get('api/cards/{cardId}', [BillingController::class, 'getCardDetails']);
    Route::get('api/cards', [BillingController::class, 'showCards']);
    Route::get('/api/cards/edit-form/{id}', [BillingController::class, 'editForm']);
    Route::post('/api/cards/update/{id}', [BillingController::class, 'update']);

    Route::get('/api/bill-types', [BillingController::class, 'billTypes']);
    Route::post('/api/bill-histories', [BillingController::class, 'storeBillHistory']);
    Route::get('/api/bill-histories', [BillingController::class, 'fetch']);
    Route::delete('/api/bill-histories/{uuid}', [BillingController::class, 'destroy']);
    Route::get('/api/payment-schedules', [PaymentScheduleController::class, 'index']);
    Route::post('/api/wallet/fund', [BillingController::class, 'fundWallet']);
    Route::get('/api/wallet', [BillingController::class, 'getWalletData']);

    Route::post('/api/update/profile', [AccountController::class, 'update']);
    Route::post('/api/addresses/update', [AccountController::class, 'updateAddress']);
    Route::post('/api/addresses/setup', [AccountController::class, 'setupAddress']);

    // Send deactivation code to email
    Route::post('/api/send-deactivation-code', [AccountController::class, 'sendDeactivationCode']);

// Deactivate account after verifying the code
    Route::post('/api/deactivate-account', [AccountController::class, 'deactivateAccount']);

    Route::post('/api/send-deactivation-email', [AccountController::class, 'sendDeactivationEmail']);

    Route::get('/api/profile', [AccountController::class, 'show']);
    Route::post('/api/profile/update', [AccountController::class, 'update']);
    Route::post('/api/profile/update', [AccountController::class, 'update']);
    Route::post('/api/update-password', [AccountController::class, 'updatePassword']);

    Route::get('/api/team-members', [AccountController::class, 'index']);

    Route::post('/payments/{paymentScheduleId}/cancel-reminders', [PaymentScheduleController::class, 'cancelReminders']);

});
// Serve Vue app for frontend routes
Route::get('/{any}', function () {
    return view('app'); // Ensure 'app.blade.php' exists in your views directory
})->where('any', '.*');

// Route::get('/', [LoginController::class, 'index'])->name('home-page');

Route::prefix('auth')->middleware('custom-guest')->group(function () {

    Route::get('/sign-in', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/sign-in', [LoginController::class, 'login'])->name('login-user');

});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout-user');

Route::middleware(['checkLaravelAuth'])->group(function () {
    // Logout route - no VerifyOtp middleware
    // Route::post('/logout', [LoginController::class, 'logout'])->name('logout-user');

    // Route::middleware(['otp.verify'])->group(function () {
    // OTP routes - no VerifyOtp middleware
    Route::post('auth/otp-resend', [OtpController::class, 'resendOtp'])->name('otp.resend');
    Route::get('auth/otp-verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('auth/otp-verify', [OtpController::class, 'verifyOtp'])->name('otp.verify.submit');

    Route::middleware(['check.otp'])->group(function () {
        // Routes that require both auth and OTP verification
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        // Route::get('/billing-details', [BillingController::class, 'index'])->name('billing');
        // Route::get('/wallet', [BillingController::class, 'wallet'])->name('wallet');
        // Route::get('/password', [DashboardController::class, 'password'])->name('password');
        // Route::get('/mortgage-details', [DashboardController::class, 'mortgage'])->name('mortgage');
        // Route::get('/rental-details', [DashboardController::class, 'rental'])->name('rental');
        // Route::get('/credit-advisory', [DashboardController::class, 'advisory'])->name('advisory');
        // Route::get('/calendar/events', [DashboardController::class, 'getCalendarEvents'])->name('getCalendarEvents');

        // Route::prefix('/modal-')->name('modal.user.')->group(function () {
        //     Route::get('setupBill', [ModalController::class, 'setupBill'])->name('setupBill');
        // });

    });
    // other protected routes that require both auth and OTP verification
    // });
});
