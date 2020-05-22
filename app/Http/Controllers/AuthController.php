<?php
namespace App\Http\Controllers;

use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\User;
use App\Http\Services\UserService;
use Carbon\Carbon;
use App\Jobs\SendEmailAfterRegistration;
use App\Jobs\SendTokenGeneratedEmail;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','confirm','generate']]);
    }

    /**
     * POST /api/auth/register
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return SuccessResource
     */
    public function register(UserService $userService,RegisterRequest $request)
    {
        $credentials = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'auth_token' => Str::random(),
            'token_generated' => date('Y-m-d H:i:s', time()),
            'status' => "Inactive"
        ];
        $user = $userService->create($credentials);
        SendEmailAfterRegistration::dispatch($user);
        return new SuccessResource((object)['message' => 'Successfully registered']);
    }

    /**
     * POST /api/auth/login
     * Login user
     *
     * @param Request $request
     *  @return FailedResource|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = auth('api')->attempt($credentials)) {
            return new FailedResource((object)['error' => 'Unauthorized']);
        }
        else {
            return $this->respondWithToken($token);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return SuccessResource
     */
    public function logout()
    {
        auth('api')->logout();
        return new SuccessResource((object)['message' => 'Successfully Log out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'code' => 200,
            'token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * POST /api/auth/confirmation/{token}
     * Confirm user Authentication Token
     *
     * @param UserService $userService
     * @param  $token
     * @return FailedResource|SuccessResource
     */
    public function confirm(UserService $userService,$token)
    {
        $user = User::where('auth_token', $token)->first();
        if($user){
            $end = Carbon::now();
            $start = Carbon::parse($user->token_generated);
            $dif_time = $end->diffInHours($start);
            if($dif_time > 24){
                $credentials = [
                    'status' => "Inactive"
                ];
                $userService->update($credentials,$user->id);
                return new FailedResource((object)['error' => 'Auth Token time expired']);
            }else {
                $credentials = [
                    'status' => "Active"
                ];
                $userService->update($credentials,$user->id);
                return new SuccessResource((object)['message' => 'Your Registration confirmed,please log in to your account']);
            }

        }
    }
    /**
     * POST /api/auth/generate
     * Generate new  user Authentication Token
     *
     * @param UserService $userService
     * @param  Request $request
     * @return SuccessResource
     */
    public function generate(UserService $userService,Request $request){
        $user = User::where('auth_token', $request->token)->first();
        $credentials = [
            'auth_token' => Str::random(),
            'token_generated' => date('Y-m-d H:i:s', time()),
            'status' => "Inactive"
        ];
        $user = $userService->update($credentials,$user->id);
        SendTokenGeneratedEmail::dispatch($user);
        return new SuccessResource((object)['message' => 'Auth token generated,check Email for Confirmation']);
    }
    public function guard()
    {
        return \Auth::Guard('api');
    }
}
