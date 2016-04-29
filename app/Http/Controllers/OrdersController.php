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
use Illuminate\Support\Facades\DB;
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

        return makeResponse($this->order->all(), trans('messages.get_data'), Response::HTTP_OK);
    }
    /**
     * Store a newly created resource in storage.
     * [
     * 'transaction_time'=>1461731399,
     * 'product_id'=>1,
     * 'user_id'=>2,
     * ];
     * //create order for user is on today
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (sizeof(Order::$rules) > 0)
            $this->validateRequestOrFail($request, Order::$rules, Order::$messages);
        $transactionToday = $this->dailyTransactionRepository->getTransactionTime();
        if(!$transactionToday){
            $transactionToday = $this->dailyTransactionRepository->create(['transaction_time'=>Carbon::now()->addDay()]);
        }
        $request = $request->all();
        $transactionToday->products()->attach($request['product_id'],['quantity'=>$request['quantity']]);
        $order = new Order($request);
        $transactionToday->orders()->save($order);
        return makeResponse($transactionToday->with(['orders','products'])->first(), trans('messages.create_data'), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order->apiFindOrFail($id);
        $order = $order->with(['dailyTransactions','users'])->toSql();
        return makeResponse($order->toArray(), trans('messages.get_data'), Response::HTTP_OK);
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
