<?php 

namespace Modules\ModuleRelease2\Services\Auth;

use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Modules\ModuleRelease2\Http\Requests\Web\Auth\LoginRequest;
use Modules\ModuleRelease2\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Str;
use Modules\ModuleRelease2\Notifications\OtpNotification;
use Modules\ModuleRelease2\Services\Shared\LogActivityService;

class AuthenticationService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected LogActivityService $logActivityService,
    ) {}

    /**
     * Handle an incoming authentication request.
     */
    // public function login(LoginRequest $request)
    public function login(Request $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::guard(module_release_2_meta('snake').'_web')->user();

        $otpConfig = config('auth.otp');
        $otpEnabled = $otpConfig['enabled'] ?? false;
        $otpRoles   = $otpConfig['roles'] ?? [];

        if ($otpEnabled && !in_array($user->role, $otpRoles)) {
            $this->logActivityService->log(
                action: 'login_without_otp',
                model: $user,
                description: sprintf(
                    'User "%s" has logged in without OTP because the role "%s" is not required to use OTP.',
                    $user->name,
                    $user->role
                ),
                before: [],
                after: [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login_at' => now()->toDateTimeString(),
                ]
            );
        }

        return $user;
    }

    /**
     * Handle verify OTP
     */
    public function sendOtp($user)
    {
        $otpConfig = config('auth.otp');

        $otp = rand(100000, 999999);

        Cache::put("otp:{$user->id}", $otp, now()->addMinutes($otpConfig['expiry'] ?? 5));

        session()->put('otp_pending_user_id', $user->id);

        Notification::send($user, new OtpNotification($otp));

        Auth::guard(module_release_2_meta('snake').'_web')->logout();
    }

    /**
     * Confirm OTP and finalize login.
     */
    public function confirmOtp($userId, $inputOtp)
    {
        $cachedOtp = Cache::get("otp:{$userId}");

        if (!$cachedOtp || $cachedOtp != $inputOtp) {
            return ['status' => 'error', 'message' => 'Invalid or expired OTP'];
        }

        Cache::forget("otp:{$userId}");

        $user = $this->userRepository->find($userId);

        Auth::guard(module_release_2_meta('snake').'_web')->login($user);

        session()->forget('otp_pending_user_id');

        // Log activity
        $this->logActivityService->log(
            action: 'login_with_otp',
            model: $user,
            description: sprintf(
                'User "%s" successfully logged in with OTP verification.',
                $user->name
            ),
            before: [],
            after: [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'login_at' => now()->toDateTimeString(),
            ]
        );

        return ['status' => 'ok', 'user' => $user];
    }

    /**
     * Resend OTP for pending user.
     */
    public function resendOtp()
    {
        $userId = session('otp_pending_user_id');

        if (! $userId) {
            return ['status' => 'error', 'message' => 'No OTP request pending'];
        }

        $user = $this->userRepository->find($userId);

        if (! $user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }

        $otpConfig = config('auth.otp');
        $otp = rand(100000, 999999);

        // simpan OTP baru
        Cache::put("otp:{$user->id}", $otp, now()->addMinutes($otpConfig['expiry'] ?? 5));

        // kirim notifikasi
        Notification::send($user, new OtpNotification($otp));

        return ['status' => 'ok', 'message' => 'OTP resent successfully'];
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $guard = module_release_2_meta('snake').'_web'; // atau release

        $user = Auth::guard($guard)->user();

        if ($user) {
            $this->logActivityService->log(
                action: 'logout',
                model: $user,
                description: sprintf('User "%s" has logged out successfully.', $user->name),
                before: [],
                after: [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'logout_at' => now()->toDateTimeString(),
                ]
            );
        }

        Auth::guard($guard)->logout();

        // Hapus session key khusus guard ini saja
        $loginKey = 'login_'.$guard.'_'.sha1(config('app.key'));
        $request->session()->forget($loginKey);

        $request->session()->regenerateToken();

        return;
    }

    /**
     * Handle incoming registration request.
     */
    public function register(Request $request)
    {
        $userCreated = $this->userRepository->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
            'province_code' => $request->input('province'),
            'city_code' => $request->input('city'),
            'district_code' => $request->input('district'),
            'village_code' => $request->input('village'),
        ]);

        try {
            $userCreated->assignRole('user');
        } catch (Exception $e) {
            throw new \RuntimeException('Gagal menetapkan role: ' . $e->getMessage());
        }
    
        event(new Registered($userCreated));
    
        Auth::guard(module_release_2_meta('snake').'_web')->login($userCreated);

        // Log activity
        $this->logActivityService->log(
            action: 'register',
            model: $userCreated,
            description: sprintf(
                'A new user account "%s" has been successfully registered and logged in automatically.',
                $userCreated->name
            ),
            before: [],
            after: [
                'id' => $userCreated->id,
                'name' => $userCreated->name,
                'email' => $userCreated->email,
                'role' => $userCreated->roles->pluck('name')->first(),
                'province' => $userCreated->province,
                'city' => $userCreated->city,
                'district' => $userCreated->district,
                'village' => $userCreated->village,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'registered_at' => now()->toDateTimeString(),
            ]
        );
    
        return $userCreated;
    }
    
    /**
     * Handle incoming change password request.
     */
    public function changePassword(array $data)
    {
        $user = Auth::guard(module_release_2_meta('snake').'_web')->user();

        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The provided password does not match your current password',
            ]);
        }

        $before = ['password' => '***old_hash_hidden***'];

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        // Log activity
        $this->logActivityService->log(
            action: 'change_password',
            model: $user,
            description: sprintf(
                'User "%s" (%s) has successfully changed their account password.',
                $user->name,
                $user->email
            ),
            before: $before,
            after: [
                'password' => '***new_hash_hidden***',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'changed_at' => now()->toDateTimeString(),
            ]
        );

        Auth::guard(module_release_2_meta('snake').'_web')->logout();

        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * Handle confirmable user password
     */
    public function confirmPassword(string $password)
    {
        $user = Auth::guard(module_release_2_meta('snake').'_web')->user();

        if (!Auth::guard(module_release_2_meta('snake').'_web')->validate([
            'email' => $user->email,
            'password' => $password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session()->put('auth.password_confirmed_at', time());

        // Log activty
        $this->logActivityService->log(
            action: 'confirm_password',
            model: $user,
            description: sprintf(
                'User "%s" (%s) has successfully confirmed their password for a sensitive operation.',
                $user->name,
                $user->email
            ),
            before: [],
            after: [
                'confirmed_at' => now()->toDateTimeString(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]
        );
    }

    /**
     * Handle email verification.
     */
    public function verifyEmail($idUser): bool
    {
        $user = $this->userRepository->find($idUser);

        if ($user->hasVerifiedEmail()) {
            return true;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            // Log the activity
            $this->logActivityService->log(
                action: 'verify_email',
                model: $user,
                description: sprintf(
                    'User "%s" (%s) has successfully verified their email address.',
                    $user->name,
                    $user->email
                ),
                before: ['email_verified_at' => null],
                after: [
                    'email_verified_at' => now()->toDateTimeString(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]
            );
        }

        return true;
    }

    /**
     * Handle resend email verification
     */
    public function resendEmailVerification(Request $request)
    {
        $user = $request->user(module_release_2_meta('snake').'_web');

        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->sendEmailVerificationNotification();
        
        // Log the activity
        $this->logActivityService->log(
            action: 'resend_email_verification',
            model: $user,
            description: sprintf(
                'A new email verification link has been sent to "%s" (%s).',
                $user->name,
                $user->email
            ),
            before: [],
            after: [
                'resent_at' => now()->toDateTimeString(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );
    }

    /**
     * Handle reset paword.
     */
    public function resetPassword(array $credentials): string
    {
        return Password::broker(module_release_2_meta('snake').'_users')->reset(
            $credentials,
            function ($user) use ($credentials) {
                $user->forceFill([
                    'password' => Hash::make($credentials['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                // Log the activity
                $this->logActivityService->log(
                    action: 'reset_password',
                    model: $user,
                    description: sprintf(
                        'The password for user "%s" (%s) has been successfully reset.',
                        $user->name,
                        $user->email
                    ),
                    before: [], // password lama tidak perlu disimpan demi keamanan
                    after: [
                        'id' => $user->id,
                        'email' => $user->email,
                        'reset_at' => now()->toDateTimeString(),
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]
                );
            }
        );
    }

    /**
     * Handle password reset link.
     */
    public function sendPasswordResetLink(string $email): string
    {
        $status = Password::broker(module_release_2_meta('snake').'_users')->sendResetLink([
            'email' => $email,
        ]);

        // Log activity
        $this->logActivityService->log(
            action: 'send_password_reset_link',
            model: null, // user bisa jadi tidak ditemukan
            description: sprintf(
                'A password reset link was requested for email: %s. Status: %s',
                $email,
                $status
            ),
            before: [],
            after: [
                'email' => $email,
                'status' => $status, // RESET_LINK_SENT / INVALID_USER
                'requested_at' => now()->toDateTimeString(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]
        );

        return $status;
    }

    /**
     * Handle social login.
     */
    // public function handleSocialLogin(string $provider): User
    // {
    //     $providerUser = Socialite::driver($provider)->user();

    //     $user = User::firstOrCreate(
    //         ['email' => $providerUser->getEmail()],
    //         [
    //             'name' => $providerUser->getName() ?? $providerUser->getNickname(),
    //             'password' => Hash::make(md5(uniqid().now())),
    //             'email_verified_at' => now()
    //         ]
    //     );

    //     try {
    //         $user->assignRole('user');
    //     } catch (Exception $e) {
    //         // Optional: log error atau trigger exception
    //         throw new \RuntimeException("Gagal menetapkan role: " . $e->getMessage());
    //     }

    //     Auth::login($user, true);

    //     return $user;
    // }
}