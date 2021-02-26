<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product_type;
use Illuminate\Http\Response;

class ProductTypeController
{
    public function getAll()
    {
        return product_type::all();
    }
    public function createProductType(Request $request)
    {
        $product_type = new product_type();
        $product_type->name = $request->name;
        $product_type->save();
        return response()->json($product_type);
    }
    public function getProductType($id)
    {
        $product_type = product_type::find($id);
        if ($product_type)
            return $product_type;
        return response()->json([
            'status' => 'error',
            'error' => 'Not found',
            'msg' => 'Please try again'
        ], Response::HTTP_NOT_FOUND);
    }
    public function updateProductType(Request $request)
    {
        $product_type = product_type::find($request->id);
        if ($product_type) {
            if ($product_type->updated_at == $request->updated_at) {
                $product_type->update($request->all());
                return response()->json($product_type);
            }
            return response()->json([
                'status' => 'error',
                'msg' => 'Error at updated_at of product ' . $product_type->id
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'status' => 'error',
            'error' => 'Not found',
            'msg' => 'Please try again'
        ], Response::HTTP_NOT_FOUND);
    }
    public function deleteProductType($id)
    {
        $product_type = product_type::find($id);
        if ($product_type) {
            $product_type->delete();
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
