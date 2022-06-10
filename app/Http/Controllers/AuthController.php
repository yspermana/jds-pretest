<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\RequestHelper;
use App\Helpers\ResponseHelper;
use Firebase\JWT\JWT;

use App\Models\Auth;

class AuthController extends Controller
{

    /**
     * Create authentication id and generate password.
     *
     * @param 
     * - string  $nik       : Must be 16 digit 
     * - string  $role      : Role of user
     *
     * @return object json 
     */
    public function register(Request $request){

        // Get all request params
        $params = $request->all();

        // Validate params
        if (empty($params['nik']) || empty($params['role'])) {
            return ResponseHelper::setErrorResponse('Invalid parameter');
        }

        // Validate NIK
        if (strlen($params['nik']) != 16) {
            return ResponseHelper::setErrorResponse('NIK must be 16 digit');
        }

        $result = array();

        // Validate NIK in database
        $getAuth = Auth::where('auth_nik', $params['nik'])->first();

        if ($getAuth) {
            return ResponseHelper::setErrorResponse('NIK has been used');
        }

        // Generate password
        $generatePassword = hash('sha256', uniqid() . time());
        $generatePassword = substr($generatePassword, 10, 6);

        try {
            
            // Insert data into database
            $value = array(
                        'auth_nik'      => $params['nik'],
                        'auth_role'     => $params['role'],
                        'auth_password' => Hash::make($generatePassword),
                    );

            Auth::create($value);

        } catch (Exception $e) {
             return ResponseHelper::setErrorResponse($e->getMessage());
        }

        $result = array(
                    'nik'       => $params['nik'],
                    'role'      => $params['role'],
                    'password'  => $generatePassword,
                );

        return ResponseHelper::setResponse('Create authentication success', $result);

    }

    /**
     * Validate access user using nik and password.
     *
     * @param 
     * - string  $nik       : Must be 16 digit 
     * - string  $password  : Password has been given in register process
     *
     * @return object json 
     */
    public function verify(Request $request){

        // Get all request params
        $params = $request->all();

        // Validate params
        if (empty($params['nik']) || empty($params['password'])) {
            return ResponseHelper::setErrorResponse('Invalid parameter');
        }

        // Validate NIK
        if (strlen($params['nik']) != 16) {
            return ResponseHelper::setErrorResponse('NIK must be 16 digit');
        }

        $result = array();

        // Validate NIK in database
        $getAuth = Auth::where('auth_nik', $params['nik'])->first();

        if (!$getAuth) {
            return ResponseHelper::setErrorResponse('Invalid NIK');
        }

        // Validate password
        if (!Hash::check($params['password'], $getAuth->auth_password)) {
            return ResponseHelper::setErrorResponse('Invalid password');
        }

        // Payload token
        $payload = array(
                        'id'   => $getAuth->auth_id,
                        'nik'  => $getAuth->auth_nik,
                        'role' => $getAuth->auth_role
                    );

        // Generate token
        $generateToken = JWT::encode($payload, env('JWT_KEY'), 'HS256');

        try {
            
            // Update token into database
            $value = array(
                        'auth_token'    => $generateToken,
                    );

            $getAuth->update($value);

        } catch (Exception $e) {
             return ResponseHelper::setErrorResponse($e->getMessage());
        }

        $result = array(
                    'id'        => $getAuth->auth_id,
                    'nik'       => $getAuth->auth_nik,
                    'role'      => $getAuth->auth_role,
                    'token'     => $generateToken,
                );

        return ResponseHelper::setResponse('Authentication valid', $result);

    }

}