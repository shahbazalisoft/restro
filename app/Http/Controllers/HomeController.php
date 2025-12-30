<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\AdminFeature;
use App\Models\AdminPromotionalBanner;
use App\Models\AdminSpecialCriteria;
use App\Models\AdminTestimonial;
use App\Models\BusinessSetting;
use App\Models\DataSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $datas =  DataSetting::with('translations', 'storage')->where('type', 'admin_landing_page')->get();
        $data = [];
        foreach ($datas as $key => $value) {
            if (count($value->translations) > 0) {
                $cred = [
                    $value->key => $value->translations[0]['value'],
                ];
                array_push($data, $cred);
            } else {
                $cred = [
                    $value->key => $value->value,
                ];
                array_push($data, $cred);
            }
            if (count($value->storage) > 0) {
                $cred = [
                    $value->key . '_storage' => $value->storage[0]['value'],
                ];
                array_push($data, $cred);
            } else {
                $cred = [
                    $value->key . '_storage' => 'public',
                ];
                array_push($data, $cred);
            }
        }
        $settings = [];
        foreach ($data as $single_data) {
            foreach ($single_data as $key => $single_value) {
                $settings[$key] = $single_value;
            }
        }

        // $settings =  DataSetting::with('translations')->where('type','admin_landing_page')->pluck('value','key')->toArray();
        $opening_time = BusinessSetting::where('key', 'opening_time')->first();
        $closing_time = BusinessSetting::where('key', 'closing_time')->first();
        $opening_day = BusinessSetting::where('key', 'opening_day')->first();
        $closing_day = BusinessSetting::where('key', 'closing_day')->first();
        $promotional_banners = AdminPromotionalBanner::where('status', 1)->get()->toArray();
        $features = AdminFeature::where('status', 1)->get()->toArray();
        $criterias = AdminSpecialCriteria::where('status', 1)->get();
        $testimonials = AdminTestimonial::where('status', 1)->get();

        $landing_data = [
            'fixed_header_title' => (isset($settings['fixed_header_title']))  ? $settings['fixed_header_title'] : null,
            'fixed_header_sub_title' => (isset($settings['fixed_header_sub_title']))  ? $settings['fixed_header_sub_title'] : null,
            'fixed_module_title' => (isset($settings['fixed_module_title']))  ? $settings['fixed_module_title'] : null,
            'fixed_module_sub_title' => (isset($settings['fixed_module_sub_title']))  ? $settings['fixed_module_sub_title'] : null,
            'fixed_referal_title' => (isset($settings['fixed_referal_title']))  ? $settings['fixed_referal_title'] : null,
            'fixed_referal_sub_title' => (isset($settings['fixed_referal_sub_title']))  ? $settings['fixed_referal_sub_title'] : null,
            'fixed_newsletter_title' => (isset($settings['fixed_newsletter_title']))  ? $settings['fixed_newsletter_title'] : null,
            'fixed_newsletter_sub_title' => (isset($settings['fixed_newsletter_sub_title']))  ? $settings['fixed_newsletter_sub_title'] : null,
            'fixed_footer_article_title' => (isset($settings['fixed_footer_article_title']))  ? $settings['fixed_footer_article_title'] : null,
            'feature_title' => (isset($settings['feature_title']))  ? $settings['feature_title'] : null,
            'feature_short_description' => (isset($settings['feature_short_description']))  ? $settings['feature_short_description'] : null,
            'earning_title' => (isset($settings['earning_title']))  ? $settings['earning_title'] : null,
            'earning_sub_title' => (isset($settings['earning_sub_title']))  ? $settings['earning_sub_title'] : null,
            'earning_seller_image' => (isset($settings['earning_seller_image']))  ? $settings['earning_seller_image'] : null,
            'earning_seller_image_storage' => (isset($settings['earning_seller_image_storage']))  ? $settings['earning_seller_image_storage'] : 'public',
            'earning_delivery_image' => (isset($settings['earning_delivery_image']))  ? $settings['earning_delivery_image'] : null,
            'earning_delivery_image_storage' => (isset($settings['earning_delivery_image_storage']))  ? $settings['earning_delivery_image_storage'] : 'public',
            'why_choose_title' => (isset($settings['why_choose_title']))  ? $settings['why_choose_title'] : null,
            'download_user_app_title' => (isset($settings['download_user_app_title']))  ? $settings['download_user_app_title'] : null,
            'download_user_app_sub_title' => (isset($settings['download_user_app_sub_title']))  ? $settings['download_user_app_sub_title'] : null,
            'download_user_app_image' => (isset($settings['download_user_app_image']))  ? $settings['download_user_app_image'] : null,
            'download_user_app_image_storage' => (isset($settings['download_user_app_image_storage']))  ? $settings['download_user_app_image_storage'] : 'public',
            'testimonial_title' => (isset($settings['testimonial_title']))  ? $settings['testimonial_title'] : null,
            'contact_us_title' => (isset($settings['contact_us_title']))  ? $settings['contact_us_title'] : null,
            'contact_us_sub_title' => (isset($settings['contact_us_sub_title']))  ? $settings['contact_us_sub_title'] : null,
            'contact_us_image' => (isset($settings['contact_us_image']))  ? $settings['contact_us_image'] : null,
            'contact_us_image_storage' => (isset($settings['contact_us_image_storage']))  ? $settings['contact_us_image_storage'] : 'public',
            'opening_time' => $opening_time ? $opening_time->value : null,
            'closing_time' => $closing_time ? $closing_time->value : null,
            'opening_day' => $opening_day ? $opening_day->value : null,
            'closing_day' => $closing_day ? $closing_day->value : null,
            'promotional_banners' => (isset($promotional_banners))  ? $promotional_banners : null,
            'features' => (isset($features))  ? $features : [],
            'criterias' => (isset($criterias))  ? $criterias : null,
            'testimonials' => (isset($testimonials))  ? $testimonials : null,

            'counter_section' => (isset($settings['counter_section']))  ? json_decode($settings['counter_section'], true) : null,
            'seller_app_earning_links' => (isset($settings['seller_app_earning_links']))  ? json_decode($settings['seller_app_earning_links'], true) : null,
            'dm_app_earning_links' => (isset($settings['dm_app_earning_links']))  ? json_decode($settings['dm_app_earning_links'], true) : null,
            'download_user_app_links' => (isset($settings['download_user_app_links']))  ? json_decode($settings['download_user_app_links'], true) : null,
            'fixed_link' => (isset($settings['fixed_link']))  ? json_decode($settings['fixed_link'], true) : null,

            'available_zone_status' => (int)((isset($settings['available_zone_status'])) ? $settings['available_zone_status'] : 0),
            'available_zone_title' => (isset($settings['available_zone_title'])) ? $settings['available_zone_title'] : null,
            'available_zone_short_description' => (isset($settings['available_zone_short_description'])) ? $settings['available_zone_short_description'] : null,
            'available_zone_image' => (isset($settings['available_zone_image'])) ? $settings['available_zone_image'] : null,
            
        ];


        $config = Helpers::get_business_settings('landing_page');
        $landing_integration_type = Helpers::get_business_data('landing_integration_type');
        $redirect_url = Helpers::get_business_data('landing_page_custom_url');

        $new_user = request()?->new_user ?? null;
        
        if (isset($config) && $config) {

            return view('home', compact('landing_data', 'new_user'));
        } elseif ($landing_integration_type == 'file_upload' && File::exists('resources/views/layouts/landing/custom/index.blade.php')) {
            return view('layouts.landing.custom.index');
        } elseif ($landing_integration_type == 'url') {
            return redirect($redirect_url);
        } else {
            abort(404);
        }
    }
}
