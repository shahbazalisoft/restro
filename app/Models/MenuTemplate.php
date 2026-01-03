<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\CentralLogics\Helpers;

class MenuTemplate extends Model
{
    public function getStoreMenuAttribute()
    {
        $storeMenuId = Helpers::get_store_data()->menu_template ?? null;
        return $this->id == $storeMenuId ? 1 : 0;
    }
}
