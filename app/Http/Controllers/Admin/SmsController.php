<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use App\Sms;

class SmsController extends Controller
{
    public function sender_id()
    {

        $url = 'http://trans.smsfresh.co/api/getsenderids.php?user='.config('constants.sms.user').'&pass='.config('constants.sms.pass');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ]);

        $searchResults = (explode('<br>', curl_exec($ch)));

        array_pop($searchResults);

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($searchResults);

        //Define how many items we want to be visible in each page
        $perPage = 10;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage -1 ) * $perPage, $perPage)->all();

        //Create our paginator and pass it to the view
        $senders = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);

        return view('admin.sender_id', compact('senders'));

    }

    public function sent_sms()
    {
        $sms_list = Sms::all();
        return view('admin.sms', compact('sms_list'));
    }
}
