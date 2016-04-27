<?php

namespace App\Http\Controllers;

use App\Repository\DailyTransactionRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DailyTransactionsController extends BaseController
{
    protected $dailyTransactionRepository;
    public function __construct(DailyTransactionRepository  $dailyTransactionRepositoryRepository)
    {
        $this->dailyTransactionRepository = $dailyTransactionRepositoryRepository;
        $this->middleware('jwt.auth', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return makeResponse($this->dailyTransactionRepository->all(),trans('messages.dailyTransactionRepository_get'),Response::HTTP_OK);
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
        $dailyTransactionRepository  = $this->dailyTransactionRepository->create($request->all());
        return makeResponse($dailyTransactionRepository->toArray(),trans('messages.create_data'),Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dailyTransactionRepository = $this->dailyTransactionRepository->find($id);
        return makeResponse($dailyTransactionRepository->toArray(),trans('messages.get_data'),Response::HTTP_OK);
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
        $dailyTransactionRepository  = $this->dailyTransactionRepository->updateRich($request->all(),$id);
        return makeResponse($dailyTransactionRepository->toArray(),trans('messages.update_data'),Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dailyTransactionRepository  = $this->dailyTransactionRepository->delete($id);
        return makeResponse($dailyTransactionRepository->toArray(),trans('messages.delete_data'),Response::HTTP_OK);
    }
}
