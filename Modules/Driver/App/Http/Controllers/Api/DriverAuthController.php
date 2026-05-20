<?php

namespace Modules\Driver\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Driver\App\Http\Requests\DriverLoginRequest;
use Modules\Driver\App\resources\DriverResource;
use Modules\Driver\Service\DriverService;

class DriverAuthController extends Controller
{
    protected $driverService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(DriverService $driverService)
    {
        $this->middleware('auth:driver', ['except' => ['login']]);
        $this->driverService = $driverService;

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(DriverLoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (! $token = auth('driver')->attempt($credentials)) {
                return returnValidationMessage(false, 'Unauthorized', ['password' => 'Wrong Credentials'], 'unauthorized');
            }
            if (auth('driver')->user()['is_active'] == 0) {
                return returnMessage(false, 'In-Active Driver Verification Required', null, 'temporary_redirect');
            }
            if ($request['fcm_token'] ?? null) {
                auth('driver')->user()->update(['fcm_token' => $request->fcm_token]);
            }
            

            return $this->respondWithToken($token,"Driver");

        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return returnMessage(true, 'Driver Data', new DriverResource(auth('driver')->user()));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('driver')->logout();

        return returnMessage(true, 'Successfully logged out', null);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('driver')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return returnMessage(true, 'Successfully Logged in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('driver')->factory()->getTTL() * 60,
            'driver' => new DriverResource(auth('driver')->user()),
        ]);
    }
}
