<?php 

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

if (!function_exists('priority')) {
    /**
     * Returns a high priority icon
     *
     * 
     *
     * */
    function priority($id)
    {
        if($id==4)
        {

     return '<div class="pull-left" data-toggle="tooltip" title="High"><i class="fa fa-chevron-up priority_high" style="; position: relative;display: inherit;top: 6.5px"></i><i class="fa fa-chevron-up priority_high" style=" position: relative;top: -0.5px"></i></div>';

        }
        if($id==3)
        {
        
     return '<div class="pull-left" data-toggle="tooltip" title="Medium"><i class="fa fa-chevron-up " style="; position: relative;display: inherit;top: 6.5px;color:#0cc285"></i><i class="fa fa-chevron-up priority_medium" style=" position: relative;top: -0.5px;color:#0cc285"></i></div>';
        }

        if($id==2)
        {
     return '<div class="pull-left"  data-toggle="tooltip" title="Normal" ><i class="fa fa-chevron-up " style="; position: relative;display: inherit;top: 6.5px;color:#007bff"></i><i class="fa fa-chevron-up priority_medium" style=" position: relative;top: -0.5px;color:#007bff"></i></div>';
        }

        if($id==1)
        {
     return '<div class="pull-left" data-toggle="tooltip" title="Low"><i class="fa fa-chevron-up " style="; position: relative;display: inherit;top: 6.5px;color:#000000"></i><i class="fa fa-chevron-up priority_medium" style=" position: relative;top: -0.5px;color:#000000"></i></div>';
 	
    	}
	}
}

function project_status($id)
{
    if ($id == 1) {

        return 'Enabled';
    }
    if ($id == 2) {

        return 'Disabled';
    }
    if ($id == 3) {

        return 'closed';
    }
}
if (! function_exists('date_string')) {
    /**
     * Returns a high priority icon
     *
     * 
     *
     * */
    function date_string($date)
    {
        return date('Y-m-d', strtotime($date));
        // return "ai";
    }
}

if (! function_exists('date_')) {

    function date_($date)
    {
        return date('d-m-Y', strtotime($date));
    }
}

if (! function_exists('GetEmployeeData')) {

    function GetEmployeeData($organization_id, $person_id)
    {
        $EmployeeData = App\HrmEmployee::where('organization_id', $organization_id)->where('person_id', $person_id);
        if ($EmployeeData->exists() == true) {
            return $EmployeeData->first()->id;
        } else {
            return 0;
        }
    }
}

if (! function_exists('GetOrgName')) {

    function GetOrgName($organization_id)
    {
        $OrgData = App\Orangnization::where('organization_id', $organization_id);
        if ($OrgData->exists() == true) {
            return $OrgData->first()->name;
        } else {
            return 0;
        }
    }
}
if (! function_exists('GetEmployeeName')) {

    function GetEmployeeName($organization_id, $person_id)
    {
        $EmployeeName = App\HrmEmployee::select("hrm_employees.first_name")->where('organization_id', $organization_id)->where('person_id', $person_id);

        if ($EmployeeName->exists() == true) {
            return $EmployeeName->first()->first_name;
        } else {
            return 0;
        }
    }
}
if (! function_exists('GetEmployeeNameById')) {

    function GetEmployeeNameById($id)
    {
        $EmployeeName = App\HrmEmployee::select(DB::raw("hrm_employees.first_name"))->where('id', $id);

        if ($EmployeeName->exists() == true) {
            return $EmployeeName->first()->first_name;
        } else {
            return 0;
        }
    }
}
if (! function_exists('GetProjectNameById')) {

    function GetProjectNameById($id)
    {
        $PeojectName = App\WfmProjects::select(DB::raw("project_name"))->where('id', $id);

        if ($PeojectName->exists() == true) {
            return $PeojectName->first()->project_name;
        } else {
            return 0;
        }
    }
}
if (! function_exists('GetTaskAction')) {

    function GetTaskAction($id)
    {
        // dd($id);
        $TaskAction = App\WfmActions::select('task_action')->where('id', $id)->first()->task_action;
        // dd($TaskStatus);
        if ($TaskAction) {
            return $TaskAction;
        }
    }
}
if (! function_exists('GetTaskStatus')) {

    function GetTaskStatus($id)
    {
        $TaskStatus = App\WfmStatus::select('task_status')->where('id', $id)->first()->task_status;
        if ($TaskStatus) {
            return $TaskStatus;
        }
        return $id;
    }
}

if (! function_exists('GetLabelName')) {

    function GetLabelName($organization_id = '', $phrase)
    {
        // echo $phrase;
        $phrase_input = preg_replace('/\s+/', '_', $phrase);
        $LabelData = App\WfmLabel::where('organization_id', $organization_id)->where('phrase', $phrase_input);
        if ($LabelData->exists()) {
            if ($LabelData->first()->label_name != "") {
                return ucfirst($LabelData->first()->label_name);
            } else {

                return ucfirst($phrase);
            }
        } else {
            return ucfirst($phrase);
        }
    }
}

if (! function_exists('GetPharse')) {

    function GetPharse($name)
    {
        return str_replace('_', ' ', $name);
    }
}
if (! function_exists('data_href')) {

    function data_href($data_attr)
    {
        $option = [];

        foreach ($data_attr as $key => $value) {
            // code...
            $option[$key] = [
                "data-href" => $value
            ];
        }

        return $option;
    }
}

if (! function_exists('Project_code')) {

    function Project_code($organization_id)
    {
        $query = App\WfmProjects::select('project_code')->where('organization_id', $organization_id)->orderBy('id', 'desc');

        // dd($query->count());

        if ($query->count() > 0) {
            $Project_code = $query->first()->project_code;
            $Project_count = $query->count();
            if ($Project_code) {

                // $Project_code="P18109";
                $pr_count = substr($Project_code, - 3);
                // ($code)int;
                $new_count = (int) $pr_count + 1;
                $Project_count = str_pad($new_count, 3, "0", STR_PAD_LEFT);
                $Project_code = "P" . date("y") . $Project_count;
            } else {
                $count = $Project_count + 1;
                $Project_count = str_pad($count, 3, "0", STR_PAD_LEFT);
                $Project_code = "P" . date("y") . $Project_count;
            }
        } else {
            $count = 1;
            $Project_count = str_pad($count, 3, "0", STR_PAD_LEFT);
            $Project_code = "P" . date("y") . $Project_count;
        }
        return $Project_code;
    }
}
if (! function_exists('Task_code')) {

    function Task_code($organization_id, $project_id)
    {
        $query = App\WfmProjects::select(DB::raw('COUNT(wfm_tasks.id) as taskcount'), 'project_code', 'task_code')->leftjoin('wfm_tasks', 'wfm_tasks.project_id', '=', 'wfm_projects.id')
            ->where('wfm_projects.organization_id', $organization_id)
            ->where('wfm_projects.id', $project_id)
            ->orderBy('wfm_tasks.id', 'desc');
        // dd($query->toSql());
        if ($query->count() > 0) {
            $task_code = $query->first()->task_code;
            $project_code = $query->first()->project_code;
            $task_count = $query->first()->taskcount;

            if ($task_code) {
                // $Project_code="P18109";
                $tsk_count = substr($task_code, - 3);
                // ($code)int;
                $new_count = (int) $tsk_count + 1;
                $Task_count = str_pad($new_count, 3, "0", STR_PAD_LEFT);
                $task_code = $project_code . "T" . $Task_count;
            } else {
                $count = $task_count + 1;
                $Task_count = str_pad($count, 3, "0", STR_PAD_LEFT);
                $task_code = $project_code . "T" . $Task_count;
            }
            // dd($task_code);
        } else {
            $count = 1;
            $Task_count = str_pad($count, 3, "0", STR_PAD_LEFT);
            $task_code = $project_code . "T" . $Task_count;
        }
        return $task_code;
    }
}

if (! function_exists('comment_attachment_path')) {

    function comment_attachment_path($org_id, $project_id)
    {
        return public_path('attachment/org_' . $org_id . '/pro_' . $project_id . '/');
    }
}
if (! function_exists('get_attachment_path')) {

    function get_attachment_path()
    {
        return public_path('attachment');
    }
}
if (! function_exists('GetAttachmentsByAttachId')) {

    function GetAttachmentsByAttachId($where_clause)
    {
        // $where_clause=explode(" and ",$where_clause);
        if (isset($where_clause['attach_id']) && $where_clause['attach_id'] != "" && isset($where_clause['attach_type']) && $where_clause['attach_type'] != "")
            $Return_result = DB::select("select * from attachments where attach_id=" . $where_clause['attach_id'] . " AND attach_type=" . $where_clause['attach_type'] . "");
        return $Return_result;
    }
}

if (! function_exists('GetAttachmentById')) {

    function GetAttachmentById($where_clause)
    {
        // $where_clause=explode(" and ",$where_clause);
        if (isset($where_clause['id']) && $where_clause['id'] != "")
            $Return_result = DB::select("select * from attachments where id=" . $where_clause['id'] . "");
        return $Return_result;
    }
}

function job_item_status($id)
{
    if ($id == 1) {

        return 'Open';
    }
    if ($id == 0) {

        return 'Closed';
    }
}
// This function return success status
if (! function_exists('pStatusSuccess')) {

    /**
     * Returns a status string
     *
     * @return string
     *
     */
    function pStatusSuccess()
    {
        return 'SUCCESS';
    }
}

// This function return failed status
if (! function_exists('pStatusFailed')) {

    /**
     * Returns a status string
     *
     * @return string
     *
     */
    function pStatusFailed()
    {
        return 'FAILED';
    }
}
// This function return the alert requested, params can be send as array
if (!function_exists('pEmailParser')) {

    /**
     * Returns a alert message string
     *
     * parameters will be replaced in the alert message if any
     *
     * @return string
     *
     */
    function pEmailParser($type, $params = false)
    {
        Log::info('Helper->pEmailParser :- Inside ');
        $file = file_get_contents(base_path('assets/data/core/emailContent.json'));
        $emailContents = json_decode($file, true);

        Log::channel('daily_data')->debug('Helper->pEmailParser :- get requested EmailParser - ' . json_encode($emailContents));

        $emailContent = $emailContents[$type];

        if ($params && $emailContent) {
            foreach ($params as $key => $data) {
               // Log::info('Helper->pEmailParser :- param key - ' . $key);
               // Log::info('Helper->pEmailParser :- param value - ' . $data);
                $emailContent = str_replace($key, $data, $emailContent);
               // Log::info('Helper->pEmailParser:- param value applied to the alert - ' . $emailContent);
            }
        }
        if ($emailContent) {
            Log::channel('daily_data')->debug('Helper->pEmailParser :- Return '.json_encode($emailContent));
            Log::info('Helper->pEmailParser :- Return ');
            return $emailContent;
        } else {
            Log::channel('daily_data')->debug("Helper->pEmailParser :- Return Email Content Dosen't Exist.");
            Log::info('Helper->pEmailParser :- Return ');
            return false;
        }
    }
}

// This function return the email template requested, params can be send as array
if (!function_exists('pEmailTemplate')) {

    /**
     * Returns a alert message string
     *
     * parameters will be replaced in the alert message if any
     *
     * @return string
     *
     */
    function pEmailTemplate($name)
    {
        $file = file_get_contents(base_path('assets/data/core/emailTemplate.json'));
        $emailTemplates = json_decode($file, true);

        Log::channel('daily_data')->debug('Helper->pEmailTemplate :- EmailTemplates - ' . json_encode($emailTemplates));

        $emailTemplate = $emailTemplates[$name];

        if ($emailTemplate) {
            return $emailTemplate;
        }

        return false;
    }
}

// This function will return filtering date range
if (!function_exists('dateRangeFilter')) {

  /**
   * Returns a array
   *
   * @return array
   *
   */
  function dateRangeFilter()
  {

    Log::info("Helper->dateRangeFilter :- Inside ");
    $data = ['LAST_24_HOURS' => 'Last 24 hours', 'LAST_ONE_WEEK' => 'Last 1 Week', 'LAST_ONE_MONTH' => 'Last 1 Month', 'LAST_THREE_MONTH' => 'Last 3 Month', 'LAST_SIX_MONTH' => 'Last 6 Month', "CUSTOM" => 'custom'];
    Log::info("Helper->dateRangeFilter :- Return ");
    return $data;
  }
}

// This function will return jobcard from date for listing
if (!function_exists('dateRange')) {

  /**
   * Returns a date
   *
   * @return date
   *
   */
  function dateRange()
  {

    Log::info("Helper->DateRange :- Inside ");

    $data = [
      "LAST_24_HOURS" => [
        "fromDate" => Carbon::now()->subDay(1)->toDateString(),
        "toDate" => Carbon::now()->toDateString()
      ],
      "LAST_ONE_WEEK" => [
        "fromDate" => Carbon::now()->subDays(7)->toDateString(),
        "toDate" => Carbon::now()->toDateString()
      ],
      "LAST_ONE_MONTH" => [
        "fromDate" => Carbon::now()->subDays(30)->toDateString(),
        "toDate" => Carbon::now()->toDateString()
      ],
      "LAST_THREE_MONTH" => [
        "fromDate" => Carbon::now()->subDays(90)->toDateString(),
        "toDate" => Carbon::now()->toDateString()
      ],
      "LAST_SIX_MONTH" => [
        "fromDate" => Carbon::now()->subDays(180)->toDateString(),
        "toDate" => Carbon::now()->toDateString()
      ],
    ];

    Log::info("Helper->DateRange :- Return ");

    return $data;

  }
}


// This function will return JobCard Status
if (!function_exists('jobCardStatuses')) {

  /**
   * Returns a array
   *
   * @return array
   *
   */
  function jobCardStatuses()
  {
    Log::info("Helper->jobCardStatuses :- Inside ");
    $data = ['ALL' => 'All (except Closed)', 1 => 'New', 2 => 'First Inspected', 3 => 'Estimation Pending', 4 => 'Estimation Approved', 5 => 'Work in Progress', 6 => 'Final Inspected', 7 => 'Vehicle Ready', 8 => 'Closed'];
    Log::info("Helper->jobCardStatuses :- Return ");
    return $data;
  }
}


// This function will return dropDown array
if (!function_exists('defalutSelectDropDownArray')) {

  /**
   * Returns a array
   *
   * @return array
   *
   */
  function defalutSelectDropDownArray($fieldlabel)
  {
    return ["" => " ---  Select ".$fieldlabel." --- "];
  }
}


// This function return current date with Y-m-d H:i:s format
if (!function_exists('currentDate')) {

  /**
   * Returns a status string
   *
   * @return string
   *
   */
  function currentDate($format = false )
  {
       $now = Carbon::now();

       // format field like y-m-d
       if($format){
         $currentDate =  $now->format($format);
       }else{
        $currentDate = $now;
       }
       return $currentDate;
  }
}

// This function return image path
if (!function_exists('jobCardImagePath')) {

  /**
   * Returns a status string
   *
   * @return string
   *
   */
  function jobCardImagePath($orgId = false)
  {
    if(!$orgId){
        $orgId = Session::get('organization_id');
    }
    $path= 'wms_attachments/org_'.$orgId.'/temp';
    return $path;
  }
}


// This function return encrypted url
if (!function_exists('generateEncryptedURL')) {

  /**
   * Returns encrypted url
   *
   * @return string
   *
   */
  function generateEncryptedURL($url,$param)
  {
    return $url . '/' . encrypt($param);    
  }
}
if (!function_exists('pGenarateOTP')) {
 function pGenarateOTP($num)
    {
        $x = $num - 1;

        $min = pow(10, $x);
        $max = pow(10, $x + 1) - 1;
        $value = rand($min, $max);

        return $value;
    }
}
?>