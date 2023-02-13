<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Product;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\ProductResource;

class ProductController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\StoreRequest  $request
     * @return \App\Http\Resources\ProductResource|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $product = Product::create($request->all());
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\UpdateRequest  $request
     * @param  \App\Models\Api\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function update(UpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->fill($request->all())->save();
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return new ProductResource($product);
    }
}
