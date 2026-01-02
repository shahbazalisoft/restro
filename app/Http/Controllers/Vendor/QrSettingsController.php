<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\Helpers;
use App\Models\QrScanner;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class QrSettingsController extends Controller
{

    public function index(): View
    {
        // QR Start
        // $data = 'https://example.com'; // QR content
        // // file & folder
        // $folder = 'qrcodes';
        // $fileName = '1_' . time() . '.svg';
        // $path = $folder . '/' . $fileName;
        
        // // generate & store QR
        // Storage::disk('public')->put(
        //     $path,
        //     QrCode::format('svg')
        //         ->size(300)
        //         // ->color(0, 123, 255) //for color
        //         //->backgroundColor(255, 0, 0, 25)//backgoudcolor
        //         ->gradient(255, 0, 0, 0, 0, 255, 'diagonal')
        //         // ->eyeColor(0, 166, 135, 204) 
        //         // ->style('dot')
        //         ->generate($data)
        // );
        // QrScanner::where('id', 8)->update(['qr_scanner'=>$fileName]);
        // QR End 
        $store = Helpers::get_store_data();
        $get_qr = QrScanner::where('store_id', $store->id)->get();
        return view('vendor-views.qr-setting.index', compact('get_qr'));
    }

    public function changeStatus($id)
    {
        $store = Helpers::get_store_data();

        DB::transaction(function () use ($id, $store) {
            QrScanner::where('store_id',$store->id)->where('status', 1)->update(['status' => 0]);
            QrScanner::where('store_id',$store->id)->where('id', $id)->update(['status' => 1]);
        });
        Toastr::success(translate('messages.New_QR-Scanner_activated_now!'));
        return back();
    }
}
