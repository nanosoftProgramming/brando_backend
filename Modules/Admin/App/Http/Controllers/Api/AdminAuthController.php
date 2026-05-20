<?php

namespace Modules\Admin\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Admin\App\Http\Requests\AdminLoginRequest;
use Modules\Admin\App\resources\AdminResource;
use Modules\Admin\App\resources\RestaurantResource;

class AdminAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function login(AdminLoginRequest $request)
    // {
    //     try {
    //         $credentials = $request->validated();

    //         if (! $token = auth('admin')->attempt($credentials)) {
    //             return returnValidationMessage(false, 'Unauthorized', ['password' => 'Wrong Credentials'], 'unauthorized');
    //         }

    //         $admin = auth('admin')->user();

    //         if ($admin->is_active == 0) {
    //             return returnMessage(false, 'In-Active Admin Verification Required', null, 'temporary_redirect');
    //         }

    //         $admin->load('roles');
    //         if ($admin->getRoleNames()->first() === 'Restaurant Manager') {
    //             $admin->load('restaurant');
    //         }

    //         return $this->respondWithToken($token, $admin);

    //     } catch (\Exception $e) {
    //         return returnMessage(false, $e->getMessage(), null, 'server_error');
    //     }
    // }

    public function login(DriverLoginRequest $request)
{
    try {
        $credentials = $request->validated();

        if (! $token = auth('driver')->attempt($credentials)) {
            return returnValidationMessage(false, 'Unauthorized', ['password' => 'Wrong Credentials'], 'unauthorized');
        }

        $driver = auth('driver')->user();

        if ($driver->is_active == 0) {
            return returnMessage(false, 'Inactive Driver', null, 'temporary_redirect');
        }

        if ($request['fcm_token'] ?? null) {
            $driver->update(['fcm_token' => $request->fcm_token]);
        }

        return $this->respondWithToken($token);

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
        return returnMessage(true, 'Admin Data', new AdminResource(auth('admin')->user()));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admin')->logout();

        return returnMessage(true, 'Successfully logged out');
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    protected function respondWithToken($token)
    {
        $admin = auth('admin')->user();
        $admin->load('roles');
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60,
            'admin' => new AdminResource($admin),
        ];

        if ($admin->getRoleNames()->first() === 'Restaurant Manager') {
            $admin->load('restaurant');
            $data['restaurant'] = new RestaurantResource($admin->restaurant);
        }

        return returnMessage(true, 'Successfully Logged in', $data);
    }
}
