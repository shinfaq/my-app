<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\product_type;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Carbon\Carbon;
use DateTime;
class ReportController extends Controller
{
    public function getTotalOrderByType(Request $request)
    {
        $list_product_type = product_type::with(['list_product' => function ($q) {
            return $q->distinct()->select('type');
        }])->with(['order_detail' => function ($q)  use ($request) {
            $q = $q->select('order_details.amount');
            if ($request->month) {
                return $q->whereHas('order', function (Builder $query) use ($request) {
                    $query->whereMonth('date', $request->month);
                });
            }
            return $q;
        }])->get();
        foreach ($list_product_type as $value) {
            $total_amount = collect($value['order_detail'])->sum('amount');
            $value['total_ordered'] = $total_amount;
            unset($value['list_product']);
            unset($value['order_detail']);
            unset($value['list_product']);
            unset($value['updated_at']);
        }
        if ($request->id) {
            foreach ($list_product_type as  $value) {
                if ($value->id == $request->id)
                    return $value;
            }
            return response()->json([
                'status' => 'error',
                'error' => 'Not found',
                'msg' => 'Please try again'
            ], Response::HTTP_NOT_FOUND);
        }
        $list_product_type = json_decode(json_encode($list_product_type));
        usort($list_product_type, fn ($a, $b) => ($b->total_ordered - $a->total_ordered));
        return $list_product_type;
    }
    public function getTotalOrderByProduct(Request $request)
    {
        $list_product = product::with(['list_order' => function ($q) use ($request) {
            if ($request->month) {
                return $q->whereHas('order', function (Builder $query) use ($request) {
                    $query->whereMonth('date', $request->month);
                });
            }
            return $q;
        }])->get();
        foreach ($list_product as $value) {
            $total_amount = collect($value['list_order'])->sum('amount');
            $value['total_ordered'] = $total_amount;
            $product_type_name = $value['product_type_name']['name'];
            $value['type_name'] = $product_type_name;
            unset($value['list_order']);
            unset($value['updated_at']);
            unset($value['product_type_name']);
        }
        if ($request->id) {
            foreach ($list_product as  $value) {
                if ($value->id == $request->id)
                    return $value;
            }
            return response()->json([
                'status' => 'error',
                'error' => 'Not found',
                'msg' => 'Please try again'
            ], Response::HTTP_NOT_FOUND);
        }
        $list_product = json_decode(json_encode($list_product));
        usort($list_product, fn ($a, $b) => ($b->total_ordered - $a->total_ordered));
        return $list_product;
    }

    public function totalMoney(Request $request)
    {
        $day = $request->day;
        $type = $request->type;
        // date:Y-m-d
        switch ($type) {
            case 1:
                $list_order = order::with('list_order')->where('date',$day);
                return $list_order->get();
            case 2:
                $start = Carbon::parse($day)->startOfMonth();
                $end = Carbon::parse($day)->endOfMonth();
                $list_order_by_month = [];
                while ($start <= $end) {
                    $date = $start->format('Y-m-d');
                    $list_order = order::with('list_order')->where('date', $date)->get();
                    $total_money = 0;
                    foreach($list_order as  $value) {
                        $total_money+=$value['total_money'];
                    }
                    $list_order_of_day = (object)[
                        'date'=> $date,
                        'total_money' => $total_money,
                        'list_ordered'=> $list_order

                    ];
                    $list_order_by_month[] = $list_order_of_day;
                    $start->addDay();
                }
                return $list_order_by_month;
            case 3:
                $start = Carbon::parse($day)->startOfYear();
                $end = Carbon::parse($day)->endOfYear();
                $list_order_by_year = [];
                while ($start <= $end) {
                    $start_month = $start->startOfMonth()->format('Y-m-d');
                    $end_month = $start->endOfMonth()->format('Y-m-d');
                    $mount = $start->format('m');
                    $list_order = order::with('list_order')->whereBetween('date',[$start_month, $end_month])->get();
                    $total_money = 0;
                    foreach ($list_order as  $value) {
                        $total_money += $value['total_money'];
                    }
                    $list_order_of_muonth = (object)[
                        'mouth' => $mount,
                        'total_money' => $total_money,
                        'list_ordered' => $list_order
                    ];
                    $start= $start->startOfMonth();
                    $list_order_by_year[] = $list_order_of_muonth;
                    $start->addMonth();
                }
                return $list_order_by_year;
        }

    }
}
