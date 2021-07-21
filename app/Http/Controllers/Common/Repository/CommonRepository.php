<?php

namespace App\Http\Controllers\Common\Repository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Session;
use DB;
use App\Person;


class CommonRepository
{
     public function getPersonByParameters($mobileNo=false,$email = false,$person_id =false)
        {

            $datas = Person::with('personMobile','user');
                   $datas->whereHas('personMobile', function ($query) use ($mobileNo)
                    {
                        $query->where(['mobile_no' => $mobileNo]);
                    });
            $data = $datas->first();
          return $data;

        }
}