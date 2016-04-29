<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use JWTAuth;

/**
 * Class BaseController
 * @package App\Http\Controllers
 */
abstract class BaseController extends Controller
{
    /**
     * @var
     */
    protected  $currentUser ;

    /**
     * @param $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     */
    public function validateRequestOrFail($request, array $rules, $messages = [], $customAttributes = []) {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new HttpException(400, json_encode($validator->errors()->getMessages()));
        }
    }

    /**
     * @return mixed
     */
    protected function getCurrentUser(){
        $token = JWTAuth::parseToken();
        $user = $token->toUser();
        return $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validUserRole(Request $request){
        $user = $this->getCurrentUser();
        if ($user->role == User::USER_ROLE) {
            throw new HttpException(\Illuminate\Http\Response::HTTP_OK, trans('messages.permission_role'));
        };
    }
}
