<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
class UploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $nameWithExtension = $request->file('file')->getClientOriginalName();
            $name = pathinfo($nameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $date = Carbon::now()->toDateString();
            $filename = 'tmp/tmp_' . $date . '/' . hash('ripemd160', $name) . '.' . $extension;
            $fileNameTmp = $request->file('file')->Move('tmp/tmp_' . $date, $filename);
            return response()->json([
                'url' => url($fileNameTmp)
            ], Response::HTTP_OK);
        } else
            return "";
    }
}
