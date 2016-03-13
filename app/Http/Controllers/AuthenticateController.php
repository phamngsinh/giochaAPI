<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends Controller
{

    public function index()
    {

        // TODO: show users
    }

    public function authenticate(Request $request)
    {
        $data = [];
        $credentials = $request->only('email', 'password');
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return makeResponse($data,'invalid_credentials',401,false);
            }
        } catch (JWTException $e) {
            // something went wrong
            return makeResponse($data,'could_not_create_token',500,false);

        }
        $data['token'] = $token;
        $data['user'] = User::where('email', '=', $credentials['email'])->first()->toArray();
        return makeResponse($data,trans('messages.get_data'),Response::HTTP_OK,true);
    }
}