<?php

namespace App\CentralLogics;

use App\Models\BusinessSetting;
use App\Models\Currency;
use App\Models\DataSetting;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helpers
{
    // use PaymentGatewayTrait, NotificationDataSetUpTrait;

    public static function get_business_settings($key, $json_decode = true, $relations = [])
    {
        try {
            static $allSettings = null;

            $configKey = $key . '_conf';
            if (Config::has($configKey)) {
                $data = Config::get($configKey);
            } else {
                if (is_null($allSettings)) {
                    $allSettings = Cache::rememberForever('business_settings_all_data', function () {
                        return BusinessSetting::select('key', 'value')->get();
                    });
                }

                $data = $allSettings->firstWhere('key', $key);
                if ($data && !empty($relations)) {
                    $data->loadMissing($relations);
                }
                Config::set($configKey, $data);
            }

            if (!isset($data['value'])) {
                return null;
            }

            $value = $data['value'];
            if ($json_decode && is_string($value)) {
                $decoded = json_decode($value, true);
                return is_null($decoded) ? $value : $decoded;
            }

            return $value;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public static function getDisk()
    {
        $config=self::get_business_settings('local_storage');

        return isset($config) ? ($config == 0 ? 's3' : 'public') : 'public';
    }
    public static function get_business_data($name)
    {
        return self::get_business_settings($name);
    }

    public static function get_full_url($path, $data, $type, $placeholder = null)
    {
        $place_holders = [
            'default' => asset('public/assets/admin/img/100x100/2.jpg'),
            'business' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'contact_us_image' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'profile' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'product' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'order' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'refund' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'delivery-man' => asset('public/assets/admin/img/160x160/img2.jpg'),
            'admin' => asset('public/assets/admin/img/160x160/img1.jpg'),
            'conversation' => asset('public/assets/admin/img/160x160/img1.jpg'),
            'banner' => asset('public/assets/admin/img/900x400/img1.jpg'),
            'campaign' => asset('public/assets/admin/img/900x400/img1.jpg'),
            'notification' => asset('public/assets/admin/img/900x400/img1.jpg'),
            'category' => asset('public/assets/admin/img/100x100/2.jpg'),
            'store' => asset('public/assets/admin/img/160x160/img1.jpg'),
            'vendor' => asset('public/assets/admin/img/160x160/img1.jpg'),
            'brand' => asset('public/assets/admin/img/100x100/2.jpg'),
            'upload_image' => asset('public/assets/admin/img/upload-img.png'),
            'store/cover' => asset('public/assets/admin/img/100x100/2.jpg'),
            'upload_image_4' => asset('/public/assets/admin/img/upload-4.png'),
            'promotional_banner' => asset('public/assets/admin/img/100x100/2.jpg'),
            'admin_feature' => asset('public/assets/admin/img/100x100/2.jpg'),
            'aspect_1' => asset('/public/assets/admin/img/aspect-1.png'),
            'special_criteria' => asset('public/assets/admin/img/100x100/2.jpg'),
            'download_user_app_image' => asset('public/assets/admin/img/100x100/2.jpg'),
            'reviewer_image' => asset('public/assets/admin/img/100x100/2.jpg'),
            'fixed_header_image' => asset('/public/assets/admin/img/aspect-1.png'),
            'header_icon' => asset('/public/assets/admin/img/aspect-1.png'),
            'available_zone_image' => asset('public/assets/admin/img/100x100/2.jpg'),
            'why_choose' => asset('/public/assets/admin/img/aspect-1.png'),
            'header_banner' => asset('/public/assets/admin/img/aspect-1.png'),
            'reviewer_company_image' => asset('public/assets/admin/img/100x100/2.jpg'),
            'module' => asset('public/assets/admin/img/100x100/2.jpg'),
            'parcel_category' => asset('/public/assets/admin/img/400x400/img2.jpg'),
            'favicon' => asset('/public/assets/admin/img/favicon.png'),
            'seller' => asset('public/assets/back-end/img/160x160/img1.jpg'),
            'upload_placeholder' => asset('/public/assets/admin/img/upload-placeholder.png'),
            'payment_modules/gateway_image' => asset('/public/assets/admin/img/payment/placeholder.png'),
            'email_template' => asset('/public/assets/admin/img/blank1.png'),
        ];
        try {
            if ($data && $type == 's3' && Storage::disk('s3')->exists($path . '/' . $data)) {
                return Storage::disk('s3')->url($path . '/' . $data);
//                $awsUrl = config('filesystems.disks.s3.url');
//                $awsBucket = config('filesystems.disks.s3.bucket');
//                return rtrim($awsUrl, '/') . '/' . ltrim($awsBucket . '/' . $path . '/' . $data, '/');
            }
        } catch (\Exception $e) {
        }

        if ($data && Storage::disk('public')->exists($path . '/' . $data)) {
            return asset('storage/app/public') . '/' . $path . '/' . $data;
        }

        if (request()->is('api/*')) {
            return null;
        }

        if (isset($placeholder) && array_key_exists($placeholder, $place_holders)) {
            return $place_holders[$placeholder];
        } elseif (array_key_exists($path, $place_holders)) {
            return $place_holders[$path];
        } else {
            return $place_holders['default'];
        }

        return 'def.png';
    }

    public static function getSettingsDataFromConfig($settings, $relations = [])
    {
        try {
            if (!config($settings . '_conf')) {
                $data = BusinessSetting::where('key', $settings)->with($relations)->first();
                Config::set($settings . '_conf', $data);
            } else {
                $data = config($settings . '_conf');
            }
            return $data;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public static function logoFullUrl(){
        $logo = self::getSettingsDataFromConfig('logo',['storage']);
        return self::get_full_url('business', $logo?->value ?? '', $logo?->storage[0]?->value ?? 'public', 'favicon');
    }

    public static function iconFullUrl(){
        $icon = self::getSettingsDataFromConfig('icon',['storage']);
        return self::get_full_url('business', $icon?->value ?? '', $icon?->storage[0]?->value ?? 'public', 'favicon');
    }

    public static function get_settings($name)
    {
        return self::get_business_settings($name);
    }

    public static function module_permission_check($mod_name)
    {
        if (!auth('admin')->user()->role) {
            return false;
        }

        if ($mod_name == 'zone' && auth('admin')->user()->zone_id) {
            return false;
        }

        $permission = auth('admin')->user()->role->modules;
        if (isset($permission) && in_array($mod_name, (array)json_decode($permission)) == true) {
            return true;
        }

        if (auth('admin')->user()->role_id == 1) {
            return true;
        }
        return false;
    }

    public static function employee_module_permission_check($mod_name)
    {
        if (auth('vendor')->check()) {
            if ($mod_name == 'reviews') {
                return auth('vendor')->user()->stores[0]->reviews_section;
            } else if ($mod_name == 'deliveryman' || $mod_name == 'deliveryman_list') {
                return auth('vendor')->user()->stores[0]->self_delivery_system;
            } else if ($mod_name == 'pos') {
                return auth('vendor')->user()->stores[0]->pos_system;
            } else if ($mod_name == 'addon') {
                return config('module.' . auth('vendor')->user()->stores[0]->module->module_type)['add_on'];
            }
            return true;
        } else if (auth('vendor_employee')->check()) {
            $permission = auth('vendor_employee')->user()->role->modules;
            if (isset($permission) && in_array($mod_name, (array)json_decode($permission)) == true) {
                if ($mod_name == 'reviews') {
                    return auth('vendor_employee')->user()->store->reviews_section;
                } else if ($mod_name == 'deliveryman' || $mod_name == 'deliveryman_list') {
                    return auth('vendor_employee')->user()->store->self_delivery_system;
                } else if ($mod_name == 'pos') {
                    return auth('vendor_employee')->user()->store->pos_system;
                } else if ($mod_name == 'addon') {
                    return config('module.' . auth('vendor_employee')->user()->store->module->module_type)['add_on'];
                }
                return true;
            }
        }

        return false;
    }

    //Mail Config Check
    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ';', '<', '>'], ' ', $str);
    }

    public static function get_login_url($type)
    {
        $data = DataSetting::whereIn('key', ['store_employee_login_url', 'store_login_url', 'admin_employee_login_url', 'admin_login_url'
        ])->pluck('key', 'value')->toArray();

        return array_search($type, $data);
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if ($image == null) {
            return $old_image;
        }
        try {
            if (Storage::disk(self::getDisk())->exists($dir . $old_image)) {
                Storage::disk(self::getDisk())->delete($dir . $old_image);
            }
        } catch (\Exception $e) {
        }
        $imageName = Helpers::upload($dir, $format, $image);
        return $imageName;
    }

    public static function upload(string $dir, string $format, $image = null)
    {
        try {
            if ($image != null) {
                $format = $image->getClientOriginalExtension();
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
                if (!Storage::disk(self::getDisk())->exists($dir)) {
                    Storage::disk(self::getDisk())->makeDirectory($dir);
                }
                Storage::disk(self::getDisk())->putFileAs($dir, $image, $imageName, ['visibility' => 'public']);
            } else {
                $imageName = 'def.png';
            }
        } catch (\Exception $e) {
        }
        return $imageName;
    }

    public static function deleteCacheData($prefix)
    {
        $cacheKeys = DB::table('cache')
            ->where('key', 'like', "%" . $prefix . "%")
            ->pluck('key');
        $appName = env('APP_NAME') . '_cache';
        $remove_prefix = strtolower(str_replace('=', '', $appName));
        $sanitizedKeys = $cacheKeys->map(function ($key) use ($remove_prefix) {
            $key = str_replace($remove_prefix, '', $key);
            return $key;
        });
        foreach ($sanitizedKeys as $key) {
            Cache::forget($key);
        }
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
    }

    public static function get_store_data()
    {
        if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user()->store;
        }
        return auth('vendor')->user()->stores[0];
    }

    public static function currency_code()
    {
        if (!config('currency') ){
            $currency = self::get_business_settings('currency');
            Config::set('currency', $currency );
        }
        else{
            $currency = config('currency');
        }

        return $currency;
    }

    public static function currency_symbol()
    {
        if (!config('currency_symbol')) {
            $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()?->currency_symbol;
            Config::set('currency_symbol', $currency_symbol);
        } else {
            $currency_symbol = config('currency_symbol');
        }
        return $currency_symbol;
    }

    public static function get_store_id()
    {
        if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user()->store->id;
        }
        return auth('vendor')->user()->stores[0]->id;
    }

    public static function get_loggedin_user()
    {
        if (auth('vendor')->check()) {
            return auth('vendor')->user();
        } else if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user();
        }
        return 0;
    }

    public static function get_vendor_data()
    {
        if (auth('vendor')->check()) {
            return auth('vendor')->user();
        } else if (auth('vendor_employee')->check()) {
            return auth('vendor_employee')->user()->vendor;
        }
        return 0;
    }

}
