<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Request\Auth\LoginStore;
use App\Http\Request\Auth\RegisterStore;

class AuthController extends Controller
{
    public function login(LoginStore $request)
    {
        try {
            $requestData = $request->validated();

            $user = User::query()->where('email', $requestData['email'])->first();

            if (! $user || ! Hash::check($requestData['password'], $user->password)) 
            {
                return $this->error(__('auth.auth_error'));
            }

            $token = $user->createToken('API Token')->plainTextToken;

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];

            return $this->successWithToken($token, $userData, __('auth.auth_success'));
            
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), null, 500);
        }
    }

    public function register(RegisterStore $request)
    {
        try {
            $requestData = $request->validated();

            $user = User::create($requestData);

            return $this->success(
                $user,
                'Kullanıcı başarıyla oluşturuldu.'
            );
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), null, 500);
        }
    }

}
