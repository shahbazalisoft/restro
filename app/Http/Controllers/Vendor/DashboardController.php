<?php

namespace App\Http\Controllers\Vendor;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\DB;
use Modules\Rental\Entities\Trips;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        if(Helpers::get_store_data()->module_type == 'rental'){
            return to_route('vendor.providerDashboard');

        }
        $params = [
            'statistics_type' => $request['statistics_type'] ?? 'overall'
        ];
        session()->put('dash_params', $params);

        $data = self::dashboard_order_stats_data();
        $earning = [];
        $commission = [];
        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        return view('vendor-views.dashboard');
    }

    public function store_data()
    {

        $store= Helpers::get_store_data();
        if($store->module_type == 'rental'){
            $type='trip';
            $new_pending_order=Trips::where(['checked' => 0])->where('provider_id', $store->id)->count();

        } else{
            $new_pending_order = DB::table('orders')->where(['checked' => 0])->where('store_id', $store->id)->where('order_status','pending');
            if(config('order_confirmation_model') != 'store' && !$store->sub_self_delivery)
            {
                $new_pending_order = $new_pending_order->where('order_type', 'take_away');
            }
            $new_pending_order = $new_pending_order->count();
            $new_confirmed_order = DB::table('orders')->where(['checked' => 0])->where('store_id', $store->id)->whereIn('order_status',['confirmed', 'accepted'])->whereNotNull('confirmed')->count();
            $type= 'store_order';
        }

        return response()->json([
            'success' => 1,
            'data' => ['new_pending_order' => $new_pending_order, 'new_confirmed_order' => $new_confirmed_order?? 0, 'order_type' =>$type]
        ]);
    }

    public function order_stats(Request $request)
    {
        $params = session('dash_params');
        foreach ($params as $key => $value) {
            if ($key == 'statistics_type') {
                $params['statistics_type'] = $request['statistics_type'];
            }
        }
        session()->put('dash_params', $params);

        $data = self::dashboard_order_stats_data();
        return response()->json([
            'view' => view('vendor-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    public function dashboard_order_stats_data()
    {
        $params = session('dash_params');
        $today = $params['statistics_type'] == 'today' ? 1 : 0;
        $this_month = $params['statistics_type'] == 'this_month' ? 1 : 0;

        $data = [
            'confirmed' => 1,
            'cooking' => 2,
            'ready_for_delivery' => 2,
            'item_on_the_way' => 2,
            'delivered' => 2,
            'refunded' => 2,
            'scheduled' => 2,
            'all' => 2,
        ];

        return $data;
    }

    public function updateDeviceToken(Request $request)
    {
        $vendor = Vendor::find(Helpers::get_vendor_id());
        $vendor->firebase_token =  $request->token;

        $vendor->save();

        return response()->json(['Token successfully stored.']);
    }
}
