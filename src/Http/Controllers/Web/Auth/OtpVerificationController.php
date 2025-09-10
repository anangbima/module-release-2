<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Auth\OtpRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationService;

class OtpVerificationController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,
    ) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Verify OTP',
        ];

        return view(module_release_2_meta('kebab').'::web.auth.verify-otp', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OtpRequest $request)
    {
        $userId = session('otp_pending_user_id');

        if (!$userId) {
            return redirect()->route(module_release_2_meta('kebab').'.auth.login')
                ->withErrors(['otp' => 'Session expired, please login again.']);
        }

        $result = $this->authService->confirmOtp($userId, $request->validated()['otp']);

        if ($result['status'] === 'error') {
            return back()->withErrors(['otp' => $result['message']]);
        }

        $user = $result['user'];

        if ($user->hasRole('user')) {
            return redirect()->route(module_release_2_meta('kebab').".user.index")
                ->with('success', 'Login successful!');
        }

        return redirect()->intended(module_release_2_meta('kebab').'/admin')
            ->with('success', 'Login successful!');
    }

    public function resend()
    {
        $result = $this->authService->resendOtp();

        if ($result['status'] === 'error') {
            return back()->withErrors(['otp' => $result['message']]);
        }

        return back()->with('status', $result['message']);
    }
}
