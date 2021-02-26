<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use Illuminate\Http\Response;
class ProductController
{
    public function getAll()
    {
        return product::all();
    }
    public function getProduct($id)
    {
        $product =product::find($id);
        if($product)
            return $product;
        return response()->json([
            'status' => 'error',
            'error' => 'Not found',
            'msg' => 'Please try again'
        ], Response::HTTP_NOT_FOUND);
    }
    public function getByType($type)
    {
        $products = product::where('type',$type)->get();
        return $products;
    }
    public function createProduct(Request $request)
    {
        $product = new product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->type = $request->type;
        $product->image = $request->image;
        $product->save();
        return response()->json($product);
    }
    public function updateProduct(Request $request)
    {
        $product = product::find($request->id);
        if($product){
            if($product->updated_at==$request->updated_at){
                $product->update($request->all());
                return response()->json($product);
            }
            return response()->json([
                'status' => 'error',
                'msg' => 'Error at updated_at of product '. $product->id
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'status' => 'error',
            'error' => 'Not found',
            'msg' => 'Please try again'
        ], Response::HTTP_NOT_FOUND);

    }
    public function deleteProduct($id)
    {
        $product = product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 'OK',
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => 'error',
            'error' => 'Not found',
            'msg' => 'Please try again'
        ], Response::HTTP_NOT_FOUND);
    }
}
