<?php

namespace App\Http\Controllers;

use App\Models\DailyTransaction;
use App\Models\DailyTransactionProduct;
use App\Models\Order;
use App\Models\Product;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;
use App\Repository\DailyTransactionRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Log;

class OrdersController extends BaseController
{
    protected $order;
    protected $dailyTransactionRepository;

    public function __construct(
        OrderRepository $orderRepository,
        DailyTransactionRepository $dailyTransactionRepositoryRepository
    ) {
        $this->order = $orderRepository;
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

        $order = Order::find(3);
        return makeResponse($this->order->all(), trans('messages.get_data'), Response::HTTP_OK);
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
     * [
     * 'transaction_time'=>1461731399,
     * 'product_id'=>1,
     * 'user_id'=>2,
     * ];
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $order = $this->order->create($request->all());
        return makeResponse($order->toArray(), trans('messages.create_data'), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order->find($id);

        return makeResponse($order->toArray(), trans('messages.get_data'), Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = $this->order->updateRich($request->all(), $id);

        return makeResponse($order->toArray(), trans('messages.update_data'), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = $this->order->delete($id);

        return makeResponse($order->toArray(), trans('messages.delete_data'), Response::HTTP_OK);
    }
}
