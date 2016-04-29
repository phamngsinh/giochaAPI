<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\HttpException;
use JWTAuth;
abstract class BaseController extends Controller
{
    protected  $currentUser ;

    public function validateRequestOrFail($request, array $rules, $messages = [], $customAttributes = []) {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new HttpException(400, json_encode($validator->errors()->getMessages()));
        }
    }
    protected function getCurrentUser(){
        $token = JWTAuth::parseToken();
        $user = $token->toUser();
        return $user;
    }

}
