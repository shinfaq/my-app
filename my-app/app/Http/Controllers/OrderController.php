<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\order_detail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    public function getAll()
    {
        return order::all();
    }
    public function getOrder($id)
    {
        $order = order::with('list_order')->find($id);
        if ($order)
            return response()->json($order);
        return response()->json([
            'status' => 'error',
            'error' => 'Not found',
            'msg' => 'Please try again'
        ], Response::HTTP_NOT_FOUND);
    }
    public function createOrder(Request $request)
    {
        try {
            return DB::connection()->transaction(function () use ($request) {
                $error = false;
                $order = new order();
                $list_order = $request->list_order;
                if (count($list_order) > 0) {
                    $order->time = now();
                    $order->total_money = $request->total_money;
                    $order->shift_id = $request->shift_id;
                    $order->table_id = $request->table_id;
                    $order->save();

                    $list = [];
                    foreach ($list_order as  $value) {
                        $order_detail = [];
                        $order_detail['amount'] = $value["amount"];
                        $order_detail['price'] = $value["price"];
                        $order_detail['product_id'] = $value["product_id"];
                        $order_detail['order_id'] = $order->id;
                        $order_detail['updated_at'] = now();
                        $order_detail['created_at'] = now();
                        $list[] = $order_detail;
                    }
                    order_detail::query()->insert($list);
                    $order = order::with('list_order')->find($order->id);
                    return response()->json($order);

                    $error = true;
                    return response()->json([
                        'status' => 'error',
                        'msg' => 'Can not create order (System error)'
                    ], Response::HTTP_BAD_REQUEST);
                }
                if ($error == true) {
                    DB::rollBack();
                }
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Can not create order (list order is null)'
                ], Response::HTTP_BAD_REQUEST);
            });
        } catch (Exception $e) {
            throw new Exception('err');
        }
    }
    public function updateProduct(Request $request)
    {

        try {
            return DB::connection()->transaction(function () use ($request) {
                $error = false;
                $list_order = $request->list;
                if (count($list_order) > 0) {
                    $order = order::find($request->id);
                    if ($order) {
                        if($order->updated_at == $request->updated_at){
                            $order->total_money = $request->total_money;
                            if ($order->save()) {
                                $list_order = $request->list_order;
                                $old_order = order::with('list_order')->find($order->id);
                                foreach ($old_order->list_order as  $value) {
                                    $order_detail =  order_detail::find($value->id);
                                    $order_detail->delete();
                                }
                                $list = [];
                                foreach ($list_order as  $value) {
                                    $order_detail = new order_detail();
                                    $order_detail->amount = $value->amount;
                                    $order_detail->price = $value->price;
                                    $order_detail->product_id = $value->product_id;
                                    $order_detail->order_id = $order->id;
                                    $list[] = $order_detail;
                                }
                                if (order_detail::query()->insert($list)) {
                                    $order = order::with('list_order')->find($order->id);
                                    return response()->json($order);
                                };
                                $error = true;
                                return response()->json([
                                    'status' => 'error',
                                    'msg' => 'Can not create order (list order is null)'
                                ], Response::HTTP_BAD_REQUEST);
                            }
                            if ($error) DB::rollBack();

                        }
                        return response()->json([
                            'status' => 'error',
                            'msg' =>'Error at updated_at of product '. $order->id
                        ], Response::HTTP_BAD_REQUEST);
                    }
                    return response()->json([
                        'status' => 'error',
                        'error' => 'Not found',
                        'msg' => 'Please try again'
                    ], Response::HTTP_NOT_FOUND);
                }
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Can not create order (list order is null)'
                ], Response::HTTP_BAD_REQUEST);
            });
        } catch (Exception $e) {
            throw new Exception('err');
        }
    }
    public function deleteProduct($id)
    {
        $order = order::find($id);
        if ($order) {
            $order_details = order_detail::where('order_id', $order->id);
            $order_details->delete();
            $order->delete();
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