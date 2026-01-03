<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\Models\MenuTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuSettingsController extends Controller
{
    public function index(): View
    {
        $store_menu = Helpers::get_store_data()->menu_template;
        $row = MenuTemplate::get();
        return view('vendor-views.menu-template.index', compact('row'));
    }

    public function changeStatus($id)
    {
        $store = Helpers::get_store_data();

        DB::transaction(function () use ($id, $store) {
            QrScanner::where('store_id', $store->id)->where('status', 1)->update(['status' => 0]);
            QrScanner::where('store_id', $store->id)->where('id', $id)->update(['status' => 1]);
        });
        Toastr::success(translate('messages.New_QR-Scanner_activated_now!'));
        return back();
    }
}
