<?php
namespace App\Http\Controllers\Tradewms\Jobcard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;


class JobCardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(JobCardService $serv)
    {
        $this->serv = $serv;
    }

    public function index(request $request)
    {
        Log::info('JobCardController->index:-Inside ');
        $entities = $this->serv->findAll($request->all());
        Log::info('JobCardController->index:- Return ');
        return $entities;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Log::info('JobCardController->Create:-Inside ');
        // Log::info('JobCardController->Create:-End ');
        // // Log::info('JobCardService->contruct:- org_id '.json_encode(Session::get('organization_id')));
        // return view('trade_wms.jobcard.JobCardDetail.JobCard-Detail');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info("JobCardController->store :- Inside ");
        // dd($request->all());
        $store_return = $this->serv->store($request->all());
        Log::info("JobCardController->store :- Return ");
        return $store_return;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {}

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::info("JobCardController->update :- Inside ");
        $response = $this->serv->store($request->all());
        Log::info("JobCardController->update :- Return ");
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info("JobCardController->destroy :- Inside ");
        $response = $this->serv->destroy($id);
        Log::info("JobCardController->destroy :- Return ");
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyJobCardImage($id)
    {
        Log::info("JobCardController->destroyJobCardImage :- Inside ");
        $response = $this->serv->destroyJobCardImage($id);
        Log::info("JobCardController->destroyJobCardImage :- Return ");
        return $response;
    }

    /*
     * to get item related datas..this function like get_order_details function in transaction controller
     * using for get item data in job card
     */
    public function findJobCardById($id)
    {
        Log::info("JobCardController->findJobCardById :- Inside ");
        $entities = $this->serv->findById($id);
        Log::info("JobCardController->findJobCardById :- Return ");
        return $entities;
    }

    public function changeStatus(request $request)
    {
        Log::info('JobCardController->changeStatus:-Inside ');
        $response = $this->serv->changeStatus($request);
        Log::info('JobCardController->changeStatus:-Return ');
        return response()->json($response);
    }

    public function findCustomerByMobile(Request $request)
    {
        Log::info('JobCardController->findCustomerByMobile:-Inside ');
        $response = $this->serv->findCustomerByMobile($request->all());
        Log::info('JobCardController->findCustomerByMobile:- Return ');

        return response()->json($response);
    }

    public function getMasterData($id = false)
    {
        Log::info('JobCardController->getMasterData:-Inside ');
        $response = $this->serv->getMasterData($id);
        Log::info('JobCardController->getMasterData:- Return ');
        return response()->json($response);
    }


    public function jobcard_advance(Request $request)
    {
        // jobcard_advance
        Log::info('JobCardController->jobcard_advance:-Inside ');
        $response = $this->serv->jobcard_advance($request);
        Log::info('JobCardController->jobcard_advance:- Return ' . json_encode($response));
        return response()->json($response);
    }

    public function print(Request $request)
    {
        // print
        Log::info('JobCardController->print:-Inside ');
        $response = $this->serv->print($request);
        Log::info('JobCardController->print:- Return ');
        return response()->json($response);
    }
    public function job_card_acknowledgement($id)
    {
        Log::info('JobCardController->job_card_acknowledgement:-Inside ');
        $response = $this->serv->job_card_acknowledgement($id);
        Log::info('JobCardController->job_card_acknowledgement:- Return ');
        return view('trade_wms.jobcard.JobCard-Ack',$response);
  
    }

    public function sendSMS($id)
    {
        Log::info('JobCardController->sendSMS:-Inside ');
        $response = $this->serv->sendSMS($id);
     //   dd($response);
        Log::info('JobCardController->sendSMS:- Return ');
        return $response;
  
    }
}
