<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Exports\StoreCategoryExport;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\StoreSubCategoryExport;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories = Category::where(['position'=>0])//, 'store_id'=>Helpers::get_store_id()
        ->when(isset($key) , function($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));

        return view('vendor-views.category.index',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|max:2048',
        ]);

        $store = Helpers::get_store_data();
        $category = new Category();
        $category->name = $request->name;
        $category->position = 0;
        $category->image = Helpers::upload('category/', 'png', $request->file('image'));;
        $category->store_id = $store->id;
        $category->module_id = 1;
        $category->parent_id = 0;
        $category->save();
        return response()->json([
            'status' => true,
            'message' => translate('messages.menu_added_successfully')
        ]);
    }

    public function edit($id)
    {
        
        $category = Category::withoutGlobalScope('translate')->findOrFail($id);
        
        return view('vendor-views.category.edit', compact('category'));

        $html = view('vendor-views.category.edit', compact('category'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
        ],[
            'name.required'=>translate('name_is_required'),
        ]);

        $category = Category::find($id);
        $slug = Str::slug($request->name);
        $category->slug = $category->slug? $category->slug :"{$slug}{$category->id}";
        $category->name = $request->name;
        $category->image = $request->has('image') ? Helpers::update('category/', $category->image, 'png', $request->file('image')) : $category->image;
        $category->save();
        
        Toastr::success(translate('messages.menu_updated_successfully'));
        return back();
    }

    public function get_all(Request $request){
        $data = Category::where('name', 'like', '%'.$request->q.'%')->module(Helpers::get_store_data()->module_id)->limit(8)->get([DB::raw('id, CONCAT(name, " (", if(position = 0, "'.translate('messages.main').'", "'.translate('messages.sub').'"),")") as text')]);
        if(isset($request->all))
        {
            $data[]=(object)['id'=>'all', 'text'=>translate('messages.all')];
        }
        return response()->json($data);
    }

    function sub_index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories=Category::with(['parent'])
        ->whereHas('parent',function($query){
            $query->module(Helpers::get_store_data()->module_id);
        })
        ->where(['position'=>1])
        ->when(isset($key) , function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
        ->latest()->paginate(config('default_pagination'));
        return view('vendor-views.category.sub-index',compact('categories'));
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $categories=Category::where(['position'=>0])
    //     ->module(Helpers::get_store_data()->module_id)
    //     ->where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('name', 'like', "%{$value}%");
    //         }
    //     })
    //     ->latest()
    //     ->limit(50)->get();
    //     return response()->json([
    //         'view'=>view('vendor-views.category.partials._table',compact('categories'))->render(),
    //         'count'=>$categories->count()
    //     ]);
    // }

//    public function sub_search(Request $request){
//        $key = explode(' ', $request['search']);
//        $categories=Category::with(['parent'])
//        ->where(function ($q) use ($key) {
//            foreach ($key as $value) {
//                $q->orWhere('name', 'like', "%{$value}%");
//            }
//        })
//        ->where(['position'=>1])->limit(50)->get();
//
//        return response()->json([
//            'view'=>view('vendor-views.category.partials._sub_table',compact('categories'))->render(),
//            'count'=>$categories->count()
//        ]);
//    }

    public function export_categories(Request $request){
        $key = explode(' ', $request['search']);
        $categories=Category::where(['position'=>0])->module(Helpers::get_store_data()->module_id)
        ->when(isset($key) , function($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->get();

        $taxData = Helpers::getTaxSystemType();
        $categoryWiseTax = $taxData['categoryWiseTax'];
        $data=[
            'data' =>$categories,
            'search' =>$request['search'] ?? null,
            'categoryWiseTax' => $categoryWiseTax
        ];
        if($request->type == 'csv'){
            return Excel::download(new StoreCategoryExport($data), 'Categories.csv');
        }
        return Excel::download(new StoreCategoryExport($data), 'Categories.xlsx');


    }

    public function export_sub_categories(Request $request){
        $key = explode(' ', $request['search']);
        $categories=Category::with(['parent'])
        ->whereHas('parent',function($query){
            $query->module(Helpers::get_store_data()->module_id);
        })
        ->where(['position'=>1])
        ->when(isset($key) , function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->get();

            $data=[
                'data' =>$categories,
                'search' =>$request['search'] ?? null,
            ];
            if($request->type == 'csv'){
                return Excel::download(new StoreSubCategoryExport($data), 'SubCategories.csv');
            }
            return Excel::download(new StoreSubCategoryExport($data), 'SubCategories.xlsx');

    }
}
