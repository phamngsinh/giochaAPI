<?php

namespace App\Http\Controllers;

use App\Repository\DailyTransactionProductRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DailyTransactionProductController extends Controller
{
    protected $dailyTransactionProduct;
    public function __construct(DailyTransactionProductRepository  $dailyTransactionProductRepository)
    {
        $this->dailyTransactionProduct = $dailyTransactionProductRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return makeResponse($this->dailyTransactionProduct->all(),trans('messages>dailyTransactionProduct_get'),Response::HTTP_OK);
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
        $dailyTransactionProduct  = $this->dailyTransactionProduct->create($request->all());
        return makeResponse($dailyTransactionProduct->toArray(),trans('messages.create_data'),Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dailyTransactionProduct = $this->dailyTransactionProduct->find($id);
        return makeResponse($dailyTransactionProduct->toArray(),trans('messages.get_data'),Response::HTTP_OK);
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
        $dailyTransactionProduct  = $this->dailyTransactionProduct->updateRich($request->all(),$id);
        return makeResponse($dailyTransactionProduct->toArray(),trans('messages.update_data'),Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dailyTransactionProduct  = $this->dailyTransactionProduct->delete($id);
        return makeResponse($dailyTransactionProduct->toArray(),trans('messages.delete_data'),Response::HTTP_OK);
    }
}
