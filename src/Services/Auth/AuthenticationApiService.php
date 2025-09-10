<?php

namespace Modules\DesaModuleTemplate\Services\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Modules\DesaModuleTemplate\Repositories\Interfaces\UserRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Modules\DesaModuleTemplate\Http\Requests\Api\Auth\LoginRequest;
use Modules\DesaModuleTemplate\Http\Resources\Shared\AuthenticatedUserResource;

class AuthenticationApiService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ){ }

    /**
     * Handle an incoming login api request.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = auth('desa_module_template_api')->attempt($credentials)) {
                return [
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Invalid credentials',
                ];
            }
        } catch (JWTException $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Could not create token',
            ];
        }

        
        $user = auth('desa_module_template_api')->user();
        
        // Check if the user is verified
        if (!$user->hasVerifiedEmail()) {
            return [
                'status' => 'error',
                'code' => 403,
                'message' => 'Email not verified',
            ];
        }
        
        return [
            'status' => 'success',
            'code' => 200,
            'data' => [
                'token' => $token,
                'user' => new AuthenticatedUserResource(
                    $user
                ),
            ]
        ];
    }

    /**
     * Handle an incoming logout api request.
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Successfully logged out',
            ];
        } catch (JWTException $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to logout, token invalid',
            ];
        }
    }

    /**
     * Handle incoming registration api request
     */
    public function register(Request $request)
    {
        $userCreated = $this->userRepository->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        try {
            $userCreated->assignRole('user');
        } catch (JWTException $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to assign role: ' . $e->getMessage(),
            ];
        }

        // event(new Registered($userCreated));
        $userCreated->sendEmailVerificationNotification('api');

        // Generate JWT token
        $token = JWTAuth::fromUser($userCreated);

        return [
            'status' => 'success',
            'code' => 200,
            'data' => [
                'token' => $token,
                'user' => new AuthenticatedUserResource(
                    $userCreated
                ),
            ]
        ];
    }

    /**
     * Handle incoming change password api request.
     */
    public function changePassword(array $data)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!Hash::check($data['current_password'], $user->password)) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => 'The provided password does not match your current password',
            ];
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        JWTAuth::invalidate(JWTAuth::getToken());

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Password changed successfully',
            'data' => new AuthenticatedUserResource(
                $user
            ),
        ];
    }

    /**
     * Handle confirm password api request.
     */
    public function confirmPassword(string $password)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!Hash::check($password, $user->password)) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => 'The provided password does not match your current password',
            ];
        }

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Password confirmed successfully',
            'data' => new AuthenticatedUserResource(
                $user
            ),
        ];
    }

    /**
     * Handle email verification api request.
     */
    public function verifyEmail($idUser)
    {
        $user = $this->userRepository->find($idUser);

        if (!$user) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'User not found',
            ];
        }

        if ($user->hasVerifiedEmail()) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Email already verified',
                'data' => new AuthenticatedUserResource(
                    $user
                ),
            ];
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Email verified successfully',
                'data' => new AuthenticatedUserResource(
                    $user
                ),
            ];
        }

        return [
            'status' => 'error',
            'code' => 500,
            'message' => 'Failed to verify email',
        ];
    }

    /**
     * Handle resend email verification api request.
     */
    public function resendEmailVerification(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'User not found',
            ];
        }

        if ($user->hasVerifiedEmail()) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Email already verified',
            ];
        }

        $user->sendEmailVerificationNotification('api');

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Email verification link sent',
        ];
    }

    /**
     * Handle reset password api request.
     */
    public function resetPassword(array $credentials)
    {
        $status = Password::broker(desa_module_template_meta('snake') . '_users')->reset(
            $credentials,
            function ($user) use ($credentials) {
                $user->forceFill([
                    'password' => Hash::make($credentials['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                // event(new PasswordReset($user));
                $user->sendPasswordResetNotification($credentials['token'], 'api');
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Password reset successfully',
            ];
        }

        return [
            'status' => 'error',
            'code' => 400,
            'message' => 'Failed to reset password',
        ];
    }

    /**
     * Handle send password reset link api request.
     */
    public function sendPasswordResetLink(string $email)
    {
        $status = Password::broker(desa_module_template_meta('snake') . '_users')->sendResetLink([
            'email' => $email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Password reset link sent successfully',
                'data' => null,
            ];
        }

        return [
            'status' => 'error',
            'code' => 400,
            'message' => 'Failed to send password reset link',
        ];
    }

}