<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;

trait ApiResponse
{
    public function success($data = null, string $message = 'İşlem başarılı', int $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data != null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public function error(string $message = 'Bir hata oluştu', $data = null, int $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($data != null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $formattedErrors = [];
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $formattedErrors[] = [
                    'field' => $field,
                    'message' => $message,
                ];
            }
        }

        throw new HttpResponseException(response()->json([
            'message' => __('validations.validation_failed'),
            'success' => false,
            'errors' => $formattedErrors,
        ], 422));
    }

    public function successWithToken(string $token, $user = null, string $message = 'Giriş başarılı')
    {
        $response = [
            'success' => true,
            'message' => $message,
            'token' => $token,
        ];

        if ($user != null) {
            $response['data'] = $user;
        }

        return response()->json($response, 200);
    }
}
