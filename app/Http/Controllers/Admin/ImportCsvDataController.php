<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GlobalItemModel;
use Response;
use Session;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;




class ImportCsvDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function import_csv_data_to_table()
    {
        
        return view('admin.import_data_to_table');
    }


    public function uploadFile(Request $request)
    {
        //dd($request->all());
      try
      {




         if ($request->input('submit') != null )
        {

          $file = $request->file('file');
          //dd($file);
          if($file != null)
          {
             // File Details 
              $filename = $file->getClientOriginalName();
              $extension = $file->getClientOriginalExtension();
              $tempPath = $file->getRealPath();
              $fileSize = $file->getSize();
              $mimeType = $file->getMimeType();

              // Valid File Extensions
              $valid_extension = array("csv");

              // 2MB in Bytes
              $maxFileSize = 2097152; 

              // Check file extension
              if(in_array(strtolower($extension),$valid_extension)){

                // Check file size
                if($fileSize <= $maxFileSize){

                  // File upload location
                  //$location = 'uploads_csv_file';
                  $location =  public_path('uploads_csv_file');

                 

                  // Upload file
                  $file->move($location,$filename);

                  // Import CSV to Database
                  $filepath = public_path('uploads_csv_file/'.$filename);

                  // Reading file
                  $file = fopen($filepath,"r");

                  $importData_arr = array();
                  $i = 0;

                  while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                     $num = count($filedata );
                     
                     // Skip first row (Remove below comment if you want to skip the first row)
                     /*if($i == 0){
                        $i++;
                        continue; 
                     }*/
                     for ($c=0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata [$c];
                     }
                     $i++;
                  }
                  fclose($file);

                  // Insert to MySQL database
                    //dd($importData_arr);
                  $result = DB::transaction(function () use ($importData_arr)
                {


                  foreach($importData_arr as $key => $importData){
                  /* if($key == 2){
                    $importData[0] = null;
                   } */
                    $importData =  array_filter($importData);
                    Log::info('ImportCsvDataController->foreach try:-Inside '.json_encode($importData));

                    $insertData = array(
                       "name"=>$importData[0],
                       "display_name"=>$importData[1],
                       "mpn"=>$importData[2],
                       "hsn"=>$importData[3],
                       "identifier_a"=>$importData[4],
                       "category_id"=>$importData[5],
                       "type_id"=>$importData[6],
                       "make_id"=>$importData[7],
                       "description"=>$importData[8],
                       "status"=>$importData[9],
                       "created_by" => Auth::user()->id ,
                       "last_modified_by" => Auth::user()->id,
                       "created_at" => Carbon::now(),
                       "updated_at" => Carbon::now());
                   // GlobalItemModel::insertData($insertData);
                     DB::table('global_item_models')->insert($insertData);

                  }
                  return "Success";
                });
                Log::info('ImportCsvDataController->update try:-Inside '.$result);


                  Session::flash('message','Import Successful.');
                }else{
                  Session::flash('message','File too large. File must be less than 2MB.');
                }

              }else{
                 Session::flash('message','Invalid File Extension.');
              }

           }
         
        }

        // Redirect to index
        return redirect()->action('Admin\ImportCsvDataController@import_csv_data_to_table');

      }
      catch (\Exception $e) {
         return response()->json(['status' => 2, 'error' =>  $e->getMessage()]);
      }
       
    }
}
