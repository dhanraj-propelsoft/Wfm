<?php



namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\VehicleCategory;

use App\VehicleMake;

use App\VehicleType;

use  App\VehicleModel;

use App\VehicleVariant;

use App\User;

use DB;

use App\Custom;

use Session;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Input;





class VehicleMasterController extends Controller

{

    public function index()

    {

        $categories = VehicleCategory::select('vehicle_categories.status','vehicle_categories.id', 'vehicle_categories.name', 'users.name AS user_name',DB::raw('DATE_FORMAT(vehicle_categories.created_at, "%d %M, %Y") AS start_date'),'organizations.name AS companyname','vehicle_types.display_name as type')

            ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_categories.type_id')

            ->leftjoin('users','users.id','=','vehicle_categories.created_by')

             ->leftjoin('persons','persons.id','=','users.person_id')

            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')

            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')

            ->groupBy('vehicle_categories.id')

            ->get();

//dd( $categories );

        return view('admin.VehicleMasters_Category', compact('categories'));

    }

     public function vehiclecategory_status(Request $request){

//dd($request->all());    

        if($request->input('status')==="1")

        {

            $UpdateData=['status' => $request->input('status')];

        }else{

            $UpdateData=['status' => $request->input('status')];

        }

        VehicleCategory::where('id', $request->input('id'))->update($UpdateData);

        return response()->json(array('result' => "success",'status'=>$UpdateData));

    }

     public function category_create(){

     

           $type = VehicleType::pluck('display_name','id');

           $type->prepend('Select Type','');

          return view('admin.VehicleMasters_Category_Create',compact('type'));

           

    }

    public function category_checkcreate(Request $request){

        // dd($request->all());     



         $organization_id=session::get('organization_id');



        $category = VehicleCategory::where('name', $request->name)

               ->where('id','!=', $request->id)->first();

        if(!empty($category->id)) {

            echo 'false';

        } else {

            echo 'true';

        }

    }

public function vehiclemasters_categorystore(Request $request){

    

    $category= new \App\VehicleCategory;

    $category->type_id = $request->input('type_id');

    $category->name=$request->get('name');

    $category->display_name=$request->get('name');

    $category->status=1;

    $organization_id=session::get('organization_id');

    $category->organization_id=$organization_id;

    $category->created_by=Auth::user()->id;

    $category->last_modified_by=Auth::user()->id;

    $category->save();



    $created_by =($category->created_by != null) ? User::findorFail($category->created_by)->name : "";



    $type = VehicleType::select('display_name')->where('id',$category->type_id)->first();

    //dd($type);

          

    return response()->json(['status' => 1, 'message' => 'Vehicle Category'.config('constants.flash.added'), 'data' =>['id'=>$category->id,'name'=>$category->name,'created_by'=>$created_by,'created_at'=> $category->created_at->format('d F, Y'),'status'=>$category->status,'type'=>$type]]);

             

       



        }



public function category_edit($id){



       $category = VehicleCategory::where('id', $id)->first();

     

        $category_details= VehicleCategory::select('id','type_id','display_name','description')->where('id',$id)->first();



        $selected_type = VehicleCategory::select('vehicle_types.display_name','vehicle_types.id')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_categories.type_id')->where('vehicle_categories.id',$id)->first();

      //dd($selected_type);



        $type = VehicleType::pluck('display_name','id');

        $type->prepend('Select Type','');



         return view('admin.VehicleMasters_Category_Edit', compact('category','category_details','type','selected_type'));



    }



    public function vehiclecategory_checkedit(Request $request){

//dd($request->all());     

        $category = VehicleCategory::where('id','!=', $request->categoryid)

       

        ->where('name', $request->category)

          ->first();

        if(!empty($category->id)) {

            echo 'false';

        } else {

            echo 'true';

        }



    }



  public function vehiclecategory_update(Request $request){

    //dd($request->all());



        $category = VehicleCategory::findOrFail($request->input('id'));

        $category->type_id = $request->input('type');

        $category->name = $request->input('category');

        $category->display_name=$request->input('category');

        $category->save();



        $created_by =($category->created_by != null) ? User::findorFail($category->created_by)->name : "";

         

        $created_at=($category->created_at)?$category->created_at->format('d F, Y'): "";



         $type = VehicleType::select('display_name')->where('id',$category->type_id)->first();



       return response()->json([ 'message' => 'Vehicle Category'.config('constants.flash.updated'), 'data' =>['id'=>$category->id,'name'=>$category->name,'created_by'=>$created_by,'created_at'=> $created_at,'status'=>$category->status,'type'=>$type]]);



    }    



        //,'status'=>$make->status

    public function make()

    {

        $makes = VehicleMake::select('vehicle_makes.status','vehicle_makes.id', 'vehicle_makes.name', 'users.name AS user_name','vehicle_makes.display_name AS make',DB::raw('DATE_FORMAT(vehicle_makes.created_at, "%d %M, %Y") AS start_date'),'organizations.name AS companyname')

            ->leftjoin('users','users.id','=','vehicle_makes.created_by')

             ->leftjoin('persons','persons.id','=','users.person_id')

            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')

            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')

              ->where('vehicle_makes.status', 1)

        ->orWhere(function ($query) {

            $query->where('vehicle_makes.status', '=', 0);

        })

         ->groupBy('vehicle_makes.id')

                ->get();

//dd($makes);

        return view('admin.VehicleMasters_Makes', compact('makes'));

    }





    public function vehiclemake_status(Request $request){

 // var_dump($request->all());

 // die("ok");

        if($request->input('status')==="1")

        {

            $UpdateData=['status' => $request->input('status')];

        }else{

            $UpdateData=['status' => $request->input('status')];

        }

        VehicleMake::where('id', $request->input('id'))->update($UpdateData);

        return response()->json(array('result' => "success",'status'=>$UpdateData));

    }





    public function make_create(){

     

      

          return view('admin.VehicleMasters_Makes_Create');

           

    }

    

    public function make_checkcreate(Request $request){

    //($request->all());     

        $make = VehicleMake::where('name', $request->make)

       

          ->first();

        if(!empty($make->id)) {

            echo 'false';

        } else {

            echo 'true';

        }

    }

     public function make_store(Request $request){

        

    $make= new \App\VehicleMake;

        $make->name=$request->get('make');

        $make->display_name=$request->get('make');

              

               $make->status=1;

                 $organization_id=session::get('organization_id');

                  $make->organization_id=$organization_id;

                       $make->created_by=Auth::user()->id;

         $make->last_modified_by=Auth::user()->id;

        // dd($request->get('make'));

        $make->save();



        $vehicle_make_id = ($request->input('make') != null) ? VehicleMake::findorFail($make->id)->name : "";



          $created_by =($make->created_by != null) ? User::findorFail($make->created_by)->name : "";

         

        $response['status']=1;



       return response()->json(['status' => 1, 'message' => 'Vehicle Make'.config('constants.flash.added'), 'data' =>['id'=>$make->id,'name'=>$make->name,'make'=>$vehicle_make_id,'created_by'=>$created_by,'created_at'=> $make->created_at->format('d F, Y'),'status'=>$make->status]]);

             

       



        }

public function make_edit($id){

      

        $make= VehicleMake::select('id', 'name')

              ->where('id',$id)->first();

//dd($make);

         return view('admin.VehicleMasters_Makes_Edit', compact('make'));

       





    }

    public function make_checkedit(Request $request){

   //dd($request->all());     

        $make = VehicleMake::where('id','!=', $request->makeid)

       

        ->where('name', $request->make)

          ->first();

        if(!empty($make->id)) {

            echo 'false';

        } else {

            echo 'true';

        }



    }

  public function make_update(Request $request){

// dd($request->all());



        $make = VehicleMake::findOrFail($request->input('makeid'));

        $make->name = $request->input('make');

         $make->display_name=$request->input('make');

         //dd($make);

        $make->save();

        //dd($make);

        $created_by =($make->created_by != null) ? User::findorFail($make->created_by)->name : "";

 $created_at=($make->created_at)?$make->created_at->format('d F, Y'): "";

             

              return response()->json([ 'message' => 'Vehicle Make'.config('constants.flash.updated'), 'data' =>['id'=>$make->id,'name'=>$make->name,'make'=>$make->name,'created_by'=>    $created_by,'created_at'=> $created_at,'status'=>$make->status]]);

      



    }

    public function model()

    {

        $models = VehicleModel::select('vehicle_models.status','vehicle_models.id', 'vehicle_models.name','vehicle_makes.display_name AS make', 'vehicle_models.display_name AS model','users.name AS user_name',DB::raw('DATE_FORMAT(vehicle_models.created_at, "%d %M, %Y") AS start_date'),'organizations.name AS companyname','vehicle_types.display_name as type','vehicle_categories.display_name as category')

            ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_models.vehicle_type_id')

            ->leftjoin('vehicle_categories','vehicle_categories.id','vehicle_models.vehicle_category_id')

            ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_models.vehicle_make_id')

            ->leftjoin('users','users.id','=','vehicle_models.created_by')

            ->leftjoin('persons','persons.id','=','users.person_id')

            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')

            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')

             ->where('vehicle_models.status', 1)

        ->orWhere(function ($query) {

            $query->where('vehicle_models.status', '=', 0);

        })

          ->groupBy('vehicle_models.id')

                ->get();

//dd($model);

        return view('admin.VehicleMasters_Models', compact('models'));

    }

   

    public function vehiclemodel_status(Request $request){

 // var_dump($request->all());

 // die("ok");

        if($request->input('status')==="1")

        {

            $UpdateData=['status' => $request->input('status')];

        }else{

            $UpdateData=['status' => $request->input('status')];

        }

        VehicleModel::where('id', $request->input('id'))->update($UpdateData);

        return response()->json(array('result' => "success",'status'=>$UpdateData));

    }



    public function  model_create(){



       $type = VehicleType::pluck('display_name','id')->prepend('Select Vehicle Type','');

       $category = VehicleCategory::pluck('display_name','id')->prepend('Select Vehicle Category','');

        $itemtype=VehicleMake::pluck('display_name', 'id');

        $itemtype->prepend('Select Makename', '');

    

// dd($itemtype);

     return view('admin.VehicleMasters_Model_Create', compact('itemtype','category','type'));



    }

    public function model_checkcreate(Request $request){

   

         $vehicle_model = VehicleModel::where('name', $request->model)->where('vehicle_category_id',$request->category)->where('vehicle_make_id',$request->make)
             ->first();

        if(!empty($vehicle_model->id)) {

            echo 'false';

        } else {

            echo 'true';

        }  

       

    }

     public function model_store(Request $request){

         //dd($request->all());

    $model= new \App\VehicleModel;

    $model->vehicle_type_id=$request->input('type');

    $model->vehicle_make_id=$request->input('make');

    $model->vehicle_category_id=$request->input('category');

    $model->name=$request->input('model');

    $model->display_name=$request->input('model');  

    $organization_id=session::get('organization_id');

    $model->organization_id=$organization_id;

    $model->created_by=Auth::user()->id;

    $model->last_modified_by=Auth::user()->id;

    $model->status=1;

    $model->save();

    if($model != null){

      $make_id = ($model->vehicle_make_id != null) ? VehicleMake::findorFail($request->make)->name : "";
      $model_id = ($request->input('model') != null) ? VehicleModel::findorFail($model->id)->name : "";
      $vehicle_confi = ($make_id)."/".($model_id)."/Any";
      $vehicle_configuration = ($make_id)."/".($model_id)."/Any/0000";

      $vehicle_variant = new VehicleVariant;
      $vehicle_variant->type_id = $model->vehicle_type_id;
      $vehicle_variant->category_id = $model->vehicle_category_id;
      $vehicle_variant->vehicle_make_id = $model->vehicle_make_id;
      $vehicle_variant->vehicle_model_id = $model->id;
      $vehicle_variant->name = 'Any';
      $vehicle_variant->display_name = 'Any';
      $vehicle_variant->version= '0000';
      $vehicle_variant->vehicle_confi= $vehicle_confi;
      $vehicle_variant->vehicle_configuration= $vehicle_configuration;
      $vehicle_variant->organization_id = $organization_id;
      $vehicle_variant->status = 1;
      $vehicle_variant->created_by = Auth::user()->id;
      $vehicle_variant->last_modified_by = Auth::user()->id;
      $vehicle_variant->save();
    }



    $vehicle_make_id = ($request->input('make') != null) ? VehicleMake::findorFail($model->vehicle_make_id)->name : "";



    $vehicle_model_id = ($request->input('model') != null) ? VehicleModel::findorFail($model->id)->name : "";

    $type = VehicleType::select('display_name')->where('id',$model->vehicle_type_id)->first();

    $category = vehiclecategory::select('display_name')->where('id',$model->vehicle_category_id)->first();

    $created_by =($model->created_by != null) ? User::findorFail($model->created_by)->name : "";





         

       return response()->json(['status' => 1, 'message' => 'Vehicle Model'.config('constants.flash.added'), 'data' =>['id'=>$model->id,'type'=>$type,'category'=>$category,'name'=>$model->name,'make'=>$vehicle_make_id,'model'=>$vehicle_model_id,'created_by'=>$created_by,'created_at'=> $model->created_at->format('d F, Y')]]);

            

        //dd($response);

      



       }

public function model_edit($id){



  $vehicle_model = VehicleModel::where('id', $id)->first();



  $selected_type = VehicleModel::select('vehicle_models.vehicle_type_id as id')->where('vehicle_models.id',$id)->first();

  $type = VehicleType::pluck('display_name','id')->prepend('select Type', '');



    $category = vehiclecategory::pluck('display_name','id')->prepend('Select Category','');

    $selected_category = VehicleModel::select('vehicle_categories.display_name','vehicle_categories.id')->leftjoin('vehicle_categories','vehicle_categories.id','=','vehicle_models.vehicle_category_id')->where('vehicle_models.id',$id)->first();



    //dd($selected_category->id);



      

        $make= VehicleMake::select('id','display_name')->pluck('display_name','id');

        $model=VehicleModel::select('id', 'name','vehicle_make_id')

              ->where('id',$id)->first();



//dd($make,$model);

         return view('admin.VehicleMasters_Model_Edit', compact('make','model','selected_category','category','selected_type','type','vehicle_model'));

       





    }

     public function model_checkedit(Request $request){

 // dd($request->all());     

        $make = VehicleModel::where('id','!=', $request->modelid)

        ->where('Vehicle_make_id', $request->make)  

        ->where('name', $request->model)

          ->first();

        if(!empty($make->id)) {

            echo 'false';

        } else {

            echo 'true';

        }



    }

     

  public function model_update(Request $request){

 //dd($request->all());



        $model = VehicleModel::findOrFail($request->input('modelid'));

        $model->name = $request->input('model');

         $model->display_name=$request->input('model');

         $model->vehicle_type_id=$request->input('type');

      $model->vehicle_category_id=$request->input('category');

        $model->save();

         $vehicle_make_id = ($model->vehicle_make_id != null)  ? VehicleMake::findorFail($model->vehicle_make_id)->name : "";



        $vehicle_model_id = ($request->input('model') != null) ? VehicleModel::findorFail($model->id)->name : "";



        $type = VehicleType::select('display_name')->where('id',$model->vehicle_type_id)->first();

        $category = vehiclecategory::select('display_name')->where('id',$model->vehicle_category_id)->first();



          $created_by =($model->created_by != null) ? User::findorFail($model->created_by)->name : "";



           $created_at=($model->created_at)?$model->created_at->format('d F, Y'): "";



        //dd($make);    $response['status']=1;

       return response()->json([ 'message' => 'Vehicle Model'.config('constants.flash.updated'), 'data' =>['id'=>$model->id,'type'=>$type,'category'=>$category,'name'=>$model->name,'make'=>   $vehicle_make_id,'model'=>$model->name,'created_by'=>$created_by,'created_at'=> $created_at,'status'=>$model->status]]);

   

    }

    public function varient()

    {

        $variants = VehicleVariant::select('vehicle_variants.status','vehicle_variants.id', 'vehicle_variants.name','vehicle_makes.display_name AS make', 'vehicle_models.display_name AS model','vehicle_variants.version', 'users.name AS user_name',DB::raw('DATE_FORMAT(vehicle_variants.created_at, "%d %M, %Y") AS start_date'),'organizations.name AS companyname','vehicle_types.display_name as type','vehicle_categories.display_name as category')

            ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_variants.type_id')

            ->leftjoin('vehicle_categories','vehicle_categories.id','=','vehicle_variants.category_id')

            ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_variants.vehicle_make_id')

            ->leftjoin('vehicle_models','vehicle_models.id','=','vehicle_variants.vehicle_model_id')

            ->leftjoin('users','users.id','=','vehicle_variants.created_by')

            ->leftjoin('persons','persons.id','=','users.person_id')

            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')

            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')

              ->where('vehicle_variants.status', 1)

        ->orWhere(function ($query) {

            $query->where('vehicle_variants.status', '=', 0);

        })

         ->groupBy('vehicle_variants.id')

         ->paginate(10);

       

      

     /* $varients = VehicleType::select('vehicle_types.display_name AS TYPE','vehicle_categories.display_name AS category','vehicle_makes.display_name AS make','vehicle_models.display_name AS model','vehicle_variants.display_name AS varient','vehicle_variants.version')

                  ->leftjoin('vehicle_categories','vehicle_categories.type_id','=','vehicle_types.id')

                  ->leftjoin('vehicle_models','vehicle_models.vehicle_category_id','=','vehicle_categories.id')

                  ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_models.vehicle_make_id')

                  ->leftjoin('vehicle_variants','vehicle_variants.vehicle_model_id','=','vehicle_models.id')*/



        return view('admin.VehicleMasters_Variants', compact('variants'));

    }



    function varient_pagination(Request $request)

    {

      //  dd($request->all());

     if($request->ajax())

     {

        $variant = VehicleVariant::select('vehicle_variants.status','vehicle_variants.id', 'vehicle_variants.name','vehicle_makes.display_name AS make', 'vehicle_models.display_name AS model','vehicle_variants.version', 'users.name AS user_name',DB::raw('DATE_FORMAT(vehicle_variants.created_at, "%d %M, %Y") AS start_date'),'organizations.name AS companyname','vehicle_types.display_name as type','vehicle_categories.display_name as category')

            ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_variants.type_id')

            ->leftjoin('vehicle_categories','vehicle_categories.id','=','vehicle_variants.category_id')

            ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_variants.vehicle_make_id')

            ->leftjoin('vehicle_models','vehicle_models.id','=','vehicle_variants.vehicle_model_id')

            ->leftjoin('users','users.id','=','vehicle_variants.created_by')

            ->leftjoin('persons','persons.id','=','users.person_id')

            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')

            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')

              ->where('vehicle_variants.status', 1)

        ->orWhere(function ($query) {

            $query->where('vehicle_variants.status', '=', 0);

        })

         ->groupBy('vehicle_variants.id');

        

         

         if(Input::has('entrires')) {

             // Do something!

             $entrires=(is_numeric($request->input('entrires')))?$request->input('entrires'):10;

           //  dd($entrires);

            $variants=$variant->paginate($entrires);

        }else{



            $variants=$variant->paginate(10);

        }



        return view('admin.VehicleMasters_Variants_Pagination', compact('variants'))->render();

     }

    }



    function varient_global_search(Request $request)

    {

        //Search column

        $columnsToSearch = ['vehicle_variants.id', 'vehicle_variants.name','vehicle_makes.display_name', 'vehicle_models.display_name','vehicle_variants.version', 'users.name','vehicle_types.display_name','vehicle_categories.display_name'];



        $searchQuery = '%' . $request->search . '%';

        //Search query

      //  dd($searchQuery);

        $variant_query = VehicleVariant::select('vehicle_variants.status','vehicle_variants.id', 'vehicle_variants.name','vehicle_makes.display_name AS make', 'vehicle_models.display_name AS model','vehicle_variants.version', 'users.name AS user_name',DB::raw('DATE_FORMAT(vehicle_variants.created_at, "%d %M, %Y") AS start_date'),'organizations.name AS companyname','vehicle_types.display_name as type','vehicle_categories.display_name as category')

                    ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_variants.type_id')

                    ->leftjoin('vehicle_categories','vehicle_categories.id','=','vehicle_variants.category_id')

                    ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_variants.vehicle_make_id')

                    ->leftjoin('vehicle_models','vehicle_models.id','=','vehicle_variants.vehicle_model_id')

                    ->leftjoin('users','users.id','=','vehicle_variants.created_by')

                    ->leftjoin('persons','persons.id','=','users.person_id')

                    ->leftjoin('organization_person','organization_person.person_id','=','persons.id')

                    ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')

                    

                    ->Where(function ($query) {

                        $query->where('vehicle_variants.status', 1);

                        $query->orwhere('vehicle_variants.status', '=', 0);

                    })->groupBy('vehicle_variants.id');



                    

                  //  $variant_query->where('vehicle_variants.id', 'LIKE', $searchQuery);

                    $variant_query->Where(function ($query) use ($columnsToSearch,$searchQuery)  {

                   

                        foreach($columnsToSearch as $column) {

                        

                            $query->orWhere($column, 'LIKE', $searchQuery);

                        }  

                }); 

                   // $variant->groupBy('vehicle_variants.id')

              //  $variants = $variant_query->paginate(100);

                if(Input::has('entrires')) {

                // Do something!

                        $entrires=($request->input('entrires')!="false")?$request->input('entrires'):10;

              //  dd($entrires);

                        $variants=$variant_query->paginate($entrires);

                        

                        }else{

   

                             $variants=$variant_query->paginate(10);

                    } 

                    $variants->appends(['search'=>$request->search]);

                    

                return view('admin.VehicleMasters_Variants_Pagination', compact('variants'))->render();



    }











public function vehiclevariant_status(Request $request){



        if($request->input('status')==="1")

        {

            $UpdateData=['status' => $request->input('status')];

        }else{

            $UpdateData=['status' => $request->input('status')];

        }

        VehicleVariant::where('id', $request->input('id'))->update($UpdateData);

        return response()->json(array('result' => "success",'status'=>$UpdateData));

    }

public function  varient_create(Request $request){



  //$type = VehicleType::pluck('display_name','id')->prepend('select Type', '');

  $model = vehiclemodel::pluck('display_name','id')->prepend('select Type', '');

  $category = vehiclecategory::pluck('display_name','id')->prepend('select Category','');

  $type = VehicleType::pluck('display_name','id')->prepend('select Type','');

    

     return view('admin.VehicleMasters_Variants_Create', compact('model','category','type'));



    }

   

    public function  getmodellist($id){

        $model = DB::table("vehicle_models")

                    ->where("vehicle_make_id",$id)

                    ->where('status',1)

                    ->pluck("display_name","id");

                   // dd($model);

                 

        return response()->json($model);

    }

     public function vehiclecheck_varient(Request $request){

   //dd($request->all());  

    // var_dump($request->all());

    // die();   

      $variant = Vehiclevariant::where('Vehicle_make_id', $request->make)

        ->where('Vehicle_model_id', $request->model)

         ->where('version', $request->version)

       

        ->where('name', $request->variant)

     

          ->first();

        if(!empty($variant->id)) {

            echo 'false';

        } else {

            echo 'true';

        }

   }

public function vehicle_varient_store(Request $request)

    {

          //dd($request->all());

       $this->validate($request, [

            'varient' => 'required',       

            'make_id' => 'required',       

            'model_id' => 'required',

            'version' => 'required'

        ]);

         $ver =$request->version;

          for($j=0;$j<count($ver);$j++)

        {

            $organization_id = Session::get('organization_id');

            $variant_name = VehicleVariant::where('name',$request->name)

            ->where('vehicle_make_id','=', $request->make_id)

            ->where('vehicle_model_id','=',$request->model_id)

            ->where('version','=',$ver[$j])

            ->exists();

             if($variant_name)

        {

            //dd("true");

            return response()->json(['status' => 2,'message' => 'Already Exist']);



        }

        else

        {

            //dd("false");

            $make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($request->make_id)->name : "";

            $model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($request->model_id)->name : "";

            //dd($make_id);



            //dd($config);

            //$version = explode(',',$request->input('version'));

            $version = $request->input('version');

            //dd($version);

            for($i=0;$i<count($version);$i++)

            {

            $config=($make_id)."/".($model_id)."/".($request->varient)."/".($version[$i]);

            $confi=($make_id)."/".($model_id)."/".($request->varient);





            $vehicle_variant = new VehicleVariant;

            $vehicle_variant->name = $request->input('varient');

            $vehicle_variant->display_name = $request->input('varient');

            $vehicle_variant->vehicle_make_id = $request->input('make_id');

            $vehicle_variant->vehicle_model_id = $request->input('model_id');

            $vehicle_variant->category_id = $request->input('category_id');

            $vehicle_variant->type_id = $request->input('type_id');

            $vehicle_variant->version= $version[$i];

            $vehicle_variant->vehicle_confi=$confi;



            $vehicle_variant->vehicle_configuration=$config;

            $vehicle_variant->description = $request->input('description');

            $vehicle_variant->organization_id = $organization_id;

            $vehicle_variant->created_by=Auth::user()->id;

            $vehicle_variant->last_modified_by=Auth::user()->id;



            $vehicle_variant->save();

            }



            $type = VehicleType::select('display_name')->where('id',$vehicle_variant->type_id)->first();

            $category = vehiclecategory::select('display_name')->where('id',$vehicle_variant->category_id)->first();



            $vehicle_make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($vehicle_variant->vehicle_make_id)->name : "";

            //dd(  $vehicle_make_id);



            $vehicle_model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($vehicle_variant->vehicle_model_id)->name : "";



            Custom::userby($vehicle_variant, true);

            Custom::add_addon('records');



           $created_by =($vehicle_variant->created_by != null) ? User::findorFail($vehicle_variant->created_by)->name : "";

           //dd(  $created_by );



           

            

            return response()->json(['status' => 1, 'message' => 'Vehicle Variant'.config('constants.flash.added'), 'data' => ['id' => $vehicle_variant->id,'type'=>$type,'category'=>$category,'name' => $vehicle_variant->name, 'display_name' => $vehicle_variant->display_name, 'make' => $vehicle_make_id, 'model' => $vehicle_model_id, 'version' => $vehicle_variant->version,'config' => $vehicle_variant->vehicle_configuration, 'created_at'=> $vehicle_variant->created_at->format('d F, Y'),'created_by'=>  $created_by,'status' => $vehicle_variant->status]]);

        }

        }

    }





    public function addversion()

    {

       $vehicle_version_id = VehicleVariant::where('vehicle_confi','!=','null')->orderBy('vehicle_confi')->groupby('vehicle_confi')->pluck('vehicle_confi', 'id');

        $vehicle_version_id->prepend('Select Vehicle Variant', '');



        return view('admin.VehicleMasters_Variants_addversion',compact('vehicle_version_id'));



    }

      public function get_make_name(Request $request)

    {



       $vehicle_con = VehicleVariant::findorfail($request->variant);



        $version_name = VehicleVariant::findorfail($request->variant)->name;



        $versions = VehicleVariant::select('vehicle_variants.id','vehicle_variants.vehicle_make_id','vehicle_variants.vehicle_model_id','vehicle_makes.name as make_name','vehicle_models.name as model_name','version','vehicle_variants.type_id','vehicle_variants.category_id')

        ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_variants.vehicle_make_id')

        ->leftjoin('vehicle_models','vehicle_models.id','=','vehicle_variants.vehicle_model_id')

        ->where('vehicle_variants.vehicle_confi',$vehicle_con->vehicle_confi)

        ->first();

       // dd($versions);





        $version = VehicleVariant::select(DB::raw('group_concat(version) as version'))

        ->where('vehicle_confi',$vehicle_con->vehicle_confi)

        ->get();

       

        //dd($version);



      //dd($versions);

      //dd(VehicleMake::findorFail($versions->vehicle_make_id)->id) ;

        //dd($make_id);

        //$model_id = ($versions->vehicle_model_id != null) ? VehicleModel::findorFail($versions->vehicle_model_id)->name : "";

       return response()->json(['status' => 1, 'data' => $versions,'version' => $version]);

    }

     public function version_store(Request $request)

    {

        //dd($request->all());

         $this->validate($request, [

            'id' => 'required',       

            'make_id' => 'required',       

            'model_id' => 'required',

            'version' => 'required'

        ]);

        //$ver = explode(' ',$request->version);

        //dd($ver);

        $ver =$request->version;

        $variant_names = VehicleVariant::findorfail($request->id)->name;

        //dd($variant_name);

        for($j=0;$j<count($ver);$j++)

        {

            $organization_id = Session::get('organization_id');

            $variant_name = VehicleVariant::where('name',$variant_names)

            ->where('vehicle_make_id','=', $request->make_id)

            ->where('vehicle_model_id','=',$request->model_id)

            ->where('version','=',$ver[$j])

            ->exists();

             if($variant_name)

        {

            //dd("true");

            return response()->json(['status' => 2,'message' => 'Already Exist']);



        }

        else

        {

            //dd("false");

            $make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($request->make_id)->name : "";

            $model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($request->model_id)->name : "";

            //dd($make_id);



            //dd($config);

            //$version = explode(',',$request->input('version'));

            $version = $request->input('version');

            //dd($version);

            for($i=0;$i<count($version);$i++)

            {

            $config=($make_id)."/".($model_id)."/".($variant_name)."/".($version[$i]);

            $con=($make_id)."/".($model_id)."/".($variant_names);



            //dd($config);



            $vehicle_variant = new VehicleVariant;

            $vehicle_variant->name = $variant_names;

            $vehicle_variant->display_name = $variant_names;

            $vehicle_variant->type_id = $request->input('type_id');

            $vehicle_variant->category_id = $request->input('category_id');

            $vehicle_variant->vehicle_make_id = $request->input('make_id');

            $vehicle_variant->vehicle_model_id = $request->input('model_id');

            $vehicle_variant->version= $version[$i];

            $vehicle_variant->vehicle_confi=$con;

            $vehicle_variant->vehicle_configuration=$config;

            $vehicle_variant->description = $request->input('description');

            $vehicle_variant->organization_id = $organization_id;

            $vehicle_variant->created_by=Auth::user()->id;

            $vehicle_variant->last_modified_by=Auth::user()->id;



            $vehicle_variant->save();

            }



            $vehicle_make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($vehicle_variant->vehicle_make_id)->name : "";

            $vehicle_model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($vehicle_variant->vehicle_model_id)->name : "";



            // Custom::userby($vehicle_variant, true);

            // Custom::add_addon('records');

            

            $ver = VehicleVariant::where('vehicle_make_id','=',$request->input('make_id'))

            ->where('vehicle_model_id','=',$request->input('model_id'))

            ->where('name',$variant_names)

            ->whereIn('version',$version)

            ->get();

            //dd($ver);

            $created_by =($vehicle_variant->created_by != null) ? User::findorFail($vehicle_variant->created_by)->name : "";



           return response()->json(['status' => 1, 'message' => 'Vehicle Variant addversions'.config('constants.flash.added'), 'data' => ['id' => $vehicle_variant->id, 'name' => $vehicle_variant->name,'make' => $vehicle_make_id,'model' => $vehicle_model_id,  'version' => $vehicle_variant->version,'created_by'=>$created_by,'created_at'=> $vehicle_variant->created_at->format('d F, Y'), 'status' => $vehicle_variant->status]]);

        }

        }

    }





public function vehicle_variant_edit($id)

{

      $vehicle_variant = VehicleVariant::where('id', $id)->first(); 

       $model = vehiclemodel::pluck('display_name','id')->prepend('select Model','');

       $category = vehiclecategory::pluck('display_name','id')->prepend('select Category','');

       $type = VehicleType::pluck('display_name','id')->prepend('select Type','');

       $selected_values = VehicleVariant::select('vehicle_models.display_name','vehicle_models.id','vehicle_makes.display_name as make','vehicle_makes.id as make_id','vehicle_categories.display_name as category','vehicle_categories.id as category_id','vehicle_types.display_name as type','vehicle_types.id as type_id','vehicle_variants.display_name as varient','vehicle_variants.version')

       ->leftjoin('vehicle_models','vehicle_models.id','=','vehicle_variants.vehicle_model_id')

       ->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_variants.vehicle_make_id')

       ->leftjoin('vehicle_categories','vehicle_categories.id','=','vehicle_variants.category_id')

       ->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_variants.type_id')

       ->where('vehicle_variants.id',$id)->first();

         return view('admin.VehicleMasters_Variants_Edit', compact('model','selected_values','vehicle_variant','category','type'));

}

     public function variant_editcheck(Request $request)

    {

      //dd($request->all());



        $variant = Vehiclevariant::where('id','!=', $request->variantid)

        ->where('Vehicle_make_id', $request->make)  

        ->where('vehicle_model_id', $request->model)

        ->where('name', $request->variant)

            ->where('version', $request->version)

          ->first();

        if(!empty($variant->id)) {

            echo 'false';

        } else {

            echo 'true';

        }



    }

//     

   public function variant_update(Request $request)

   {

       //dd($request->all());

        $this->validate($request, [

            'varient' => 'required',       

            'version' => 'required'

        ]);

        $ver = $request->version;



        $variant_name = VehicleVariant::where('name',$request->varient)

            ->where('vehicle_make_id','=', $request->make_id)

            ->where('vehicle_model_id','=',$request->model_id)

            ->where('version','=',$ver)

            ->where('id','!=',$request->input('id'))

            ->exists();



        /*$ver = $request->version;

        for($j=0;$j<count($ver);$j++)

        {

            $variant_name = VehicleVariant::where('name',$request->name)

            ->where('vehicle_make_id','=', $request->make_id)

            ->where('vehicle_model_id','=',$request->model_id)

            ->where('version','=',$ver[$j])

            ->exists();*/



            if($variant_name)

            {

                return response()->json(['status' =>2 ,'message' => 'Already Exits']);

            }

            else

            {

            $make_name = VehicleMake::findorfail($request->make_id)->name;

            //dd($make_name);

            $model_name = VehicleModel::findorfail($request->model_id)->name;

            $version = $request->input('version');

            //dd($version);

                for($i=0;$i<count($version);$i++)

                {

                    $config = ($make_name).'/'.($model_name).'/'.($request->varient).'/'.($version[$i]);



                    $vehicle_variant = VehicleVariant::findOrFail($request->input('id'));

                    //dd($vehicle_variant);

                    $vehicle_variant->name = $request->input('varient');

            $vehicle_variant->display_name = $request->input('varient');

            $vehicle_variant->type_id = $request->input('type_id');

            $vehicle_variant->category_id = $request->input('category_id');

            $vehicle_variant->vehicle_make_id = $request->input('make_id');

            $vehicle_variant->vehicle_model_id = $request->input('model_id');

            $vehicle_variant->version= $version[$i];

            $vehicle_variant->vehicle_configuration=$config;

            $vehicle_variant->description = $request->input('description');

             $vehicle_variant->created_by=Auth::user()->id;

                        $vehicle_variant->last_modified_by=Auth::user()->id;

                    $vehicle_variant->save();

                }



          $type = VehicleType::select('display_name')->where('id',$vehicle_variant->type_id)->first();

          $category = VehicleCategory::select('display_name')->where('id',$vehicle_variant->category_id)->first();

            $vehicle_make_id = ($request->input('make_id') != null) ? VehicleMake::findorFail($vehicle_variant->vehicle_make_id)->name : "";

            $vehicle_model_id = ($request->input('model_id') != null) ? VehicleModel::findorFail($vehicle_variant->vehicle_model_id)->name : "";



            //Custom::userby($vehicle_variant, false);



  $created_by =($vehicle_variant->created_by != null) ? User::findorFail($vehicle_variant->created_by)->name : "";



$created_at=($vehicle_variant->created_at)?$vehicle_variant->created_at->format('d F, Y'): "";

            return response()->json(['message' => 'Vehicle Variant'.config('constants.flash.updated'), 'data' => ['id' => $vehicle_variant->id,'type'=>$type,'category'=>$category,'name' => $vehicle_variant->name,'make' => $vehicle_make_id,'model' => $vehicle_model_id,  'version' => $vehicle_variant->version,'created_by'=>$created_by,'created_at'=> $created_at, 'status' => $vehicle_variant->status]]);

           

        }

    }

    public function get_category(Request $request){

     // dd($request->all());

      $category = VehicleCategory::select('display_name','id')->where('type_id',$request->type_id)->get();

      //dd($category);

       return response()->json($category);

    }

     public function get_details(Request $request){

      //dd($request->all());

       $deatils = vehiclemodel::select('vehicle_models.id AS model_id','vehicle_models.display_name AS model','vehicle_makes.id AS make_id','vehicle_makes.display_name AS make','vehicle_categories.id AS category_id','vehicle_categories.display_name AS category','vehicle_types.id AS type_id','vehicle_types.display_name as type')->leftjoin('vehicle_makes','vehicle_makes.id','=','vehicle_models.vehicle_make_id')->leftjoin('vehicle_categories','vehicle_categories.id','=','vehicle_models.vehicle_category_id')->leftjoin('vehicle_types','vehicle_types.id','=','vehicle_categories.type_id')->where('vehicle_models.id',$request->model_id)->first();

         $category = vehiclecategory::pluck('display_name','id')->prepend('select Category','');

        $type = VehicleType::pluck('display_name','id')->prepend('select Type','');

        return response()->json(['data'=>$deatils,'category' => $category,'type' => $type]);

     

    }

   public function get_typecategory(Request $request){

       // dd($request->all());



    $category = VehicleType::select('vehicle_categories.id',

 'vehicle_categories.display_name')->leftjoin('vehicle_categories','vehicle_categories.type_id','=','vehicle_types.id')->where('vehicle_types.id',$request->type_id)->get();

    return response()->json($category);

   }



}