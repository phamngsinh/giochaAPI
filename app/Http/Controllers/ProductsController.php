<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Requests;
use Illuminate\Http\Response;

class ProductsController extends BaseController
{
    protected $product;
    public function __construct(ProductRepository  $productRepository)
    {
        $this->product = $productRepository;
        $this->middleware('jwt.auth', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return makeResponse($this->product->all(),trans('messages.get_data'),Response::HTTP_OK);
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
    public function store(ProductRequest $request)
    {
        if (sizeof(Product::$rules) > 0)
            $this->validateRequestOrFail($request, Product::$rules, Product::$messages);

        $product  = $this->product->create($request->all());
        return makeResponse($product->toArray(),trans('messages.create_data'),Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);
        return makeResponse($product->toArray(),trans('messages.get_data'),Response::HTTP_OK);
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

        if (sizeof(Product::$rules) > 0)
            $this->validateRequestOrFail($request, Product::$rules, Product::$messages);

        $product = $this->product->apiFindOrFail($id);

        $product  = $this->product->updateRich($request->all(),$id);
        if($product){
            $product = $this->product->apiFindOrFail($id);
        }

        return makeResponse($product->toArray(),trans('messages.update_data'),Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product  = $this->product->delete($id);
        return makeResponse($product->toArray(),trans('messages.delete_data'),Response::HTTP_OK);
    }
}
