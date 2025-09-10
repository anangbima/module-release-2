<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationService;

class SocialLoginController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,    
    ) {}
    
    /**
     * Redirect the user to the social provider for authentication.
     */
    public function redirectToProvider(string $provider)
    {
        // return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the social provider.
     */
    public function handleProviderCallback(string $provider)
    {
        // $user = Socialite::driver($provider)->user();

        // Handle the user information, e.g., create or update the user in your database
        // $this->authService->handleSocialLogin($provider);

        // Redirect or return a response after successful login
        // return redirect()->route('home')->with('status', 'Logged in successfully via ' . ucfirst($provider) . '.');
    }
}
