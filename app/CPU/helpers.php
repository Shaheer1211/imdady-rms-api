<?php

namespace App\CPU;

use App\Model\Customer;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Helpers
{
    public static function module_permission_check($module_name)
    {
        $permission = auth()->user()->module_access;
        if (isset($permission) && in_array($module_name, (array)json_decode($permission)) == true) {
            return true;
        }
        if(auth()->user()->role == 'super-admin'){
            return true;
        }
        return false;
    }


}

