<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use JWTAuth;

class UsersController extends BaseController
{
    protected $user;

    public function __construct(UserRepository  $userRepository,Request $request)
    {

        $this->user = $userRepository;
        $this->currentUser = $this->getCurrentUser();
        $this->validUserRole($request);
        $this->middleware('jwt.auth', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return makeResponse($this->user->all(),trans('messages.user_get'),Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (sizeof(User::$rules) > 0) {
            $this->validateRequestOrFail($request, User::$rules, User::$messages);
        }
        $user  = $this->user->create($request->all());
        return makeResponse($user->toArray(),trans('messages.create_data'),Response::HTTP_OK);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->user->find($id);
        if(!$user){
            return makeResponse($id,trans('messages.data_not_found'),Response::HTTP_OK);
        }
        if($user->role == User::USER_ROLE){
            $user  = $this->user->with('orders')->find($id);
        }else{
            $user  = $this->user->with(['orders','products'])->find($id);
        }

        return makeResponse($user->toArray(),trans('messages.get_data'),Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (sizeof(User::rules($id)) > 0)
            $this->validateRequestOrFail($request, User::rules($id), User::$messages);
        $user = $this->user->apiFindOrFail($id);
        $user  = $this->user->updateRich($request->all(),$id);
        if($user){
            $user = $this->user->apiFindOrFail($id);
        }
        return makeResponse($user->toArray(),trans('messages.update_data'),Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user  = $this->user->delete($id);
        return makeResponse(null,trans('messages.delete_data'),Response::HTTP_OK);
    }
}
