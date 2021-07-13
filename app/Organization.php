<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OrganizationPackage;
use Illuminate\Support\Facades\Log;

class Organization extends Model
{

    protected $guarded = [
        '_token'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'organization_person', 'organization_id', 'person_id');
    }
     public function persons()
    {
        return $this->belongsToMany('App\Person', 'organization_person', 'organization_id', 'person_id');
    }
    
    
    public function modules()
    {
        return $this->belongsToMany('App\Module');
    }

    public function groupParent()
    {
        return $this->hasMany('App\AccountGroupParent');
    }

    public function group()
    {
        return $this->hasMany('App\AccountGroup');
    }

    public function ledger()
    {
        return $this->hasMany('App\AccountLedger');
    }

    public function voucher()
    {
        return $this->hasMany('App\AccountVoucher');
    }

    public function accountFormat()
    {
        return $this->hasMany('App\AccountVoucherFormat');
    }

    public function accountPrint()
    {
        return $this->hasMany('App\PrintTemplate');
    }

    public function setting()
    {
        return $this->hasMany('App\Setting');
    }

    public function inclusion()
    {
        return $this->hasMany('App\HrmEmployeeSalaryInclusion');
    }

    public function person_type()
    {
        return $this->hasMany('App\HrmPersonType');
    }

    public function employment_type()
    {
        return $this->hasMany('App\HrmEmploymentType');
    }

    public function attendance_type()
    {
        return $this->hasMany('App\HrmAttendanceType');
    }

    public function attendance_setting()
    {
        return $this->hasMany('App\HrmAttendanceSetting');
    }

    public function service_type()
    {
        return $this->hasMany('App\ServiceType');
    }

    public function tax()
    {
        return $this->hasMany('App\Tax');
    }

    public function tax_group()
    {
        return $this->hasMany('App\TaxGroup');
    }

    public function unit()
    {
        return $this->hasMany('App\Unit');
    }

    public function inventoryCategory()
    {
        return $this->hasMany('App\InventoryCategory');
    }

    public function inventoryItem()
    {
        return $this->hasMany('App\inventoryItem');
    }

    public function inventoryStore()
    {
        return $this->hasMany('App\InventoryStore');
    }

    public function inventoryDiscount()
    {
        return $this->hasMany('App\Discount');
    }

    public function break()
    {
        return $this->hasMany('App\HrmBreak');
    }

    public function department()
    {
        return $this->hasMany('App\HrmDepartment');
    }

    public function designation()
    {
        return $this->hasMany('App\HrmDesignation');
    }

    public function shift()
    {
        return $this->hasMany('App\HrmShift');
    }

    public function leaveTypes()
    {
        return $this->hasMany('App\HrmLeaveType');
    }

    public function holidayTypes()
    {
        return $this->hasMany('App\HrmHolidayType');
    }

    public function holidays()
    {
        return $this->hasMany('App\HrmHoliday');
    }

    public function weekoff()
    {
        return $this->hasMany('App\HrmWeekOff');
    }

    public function payrollFrequency()
    {
        return $this->hasMany('App\HrmPayrollFrequency');
    }

    public function salaryscale()
    {
        return $this->hasMany('App\HrmSalaryScale');
    }

    public function shipmentMode()
    {
        return $this->hasMany('App\ShipmentMode');
    }

    public function store()
    {
        return json_decode($this->modules()->get(), true);
    }

    public function hasAccountGroupParent($parent, $user_id)
    {
        if ($this->find($user_id)
            ->groupParent()
            ->where('name', $parent)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasAccountGroup($group, $user_id)
    {
        if ($this->find($user_id)
            ->group()
            ->where('name', $group)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasAccountLedger($ledger, $person_id, $business_id, $user_id)
    {
        if ($this->find($user_id)
            ->ledger()
            ->where('name', $ledger)
            ->where('person_id', $person_id)
            ->where('business_id', $business_id)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasVoucher($voucher, $user_id)
    {
        if ($this->find($user_id)
            ->voucher()
            ->where('name', $voucher)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasFormat($format, $user_id)
    {
        if ($this->find($user_id)
            ->accountFormat()
            ->where('name', $format)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasPrint($print, $user_id)
    {
        if ($this->find($user_id)
            ->accountPrint()
            ->where('name', $print)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasSetting($setting, $user_id)
    {
        if ($this->find($user_id)
            ->setting()
            ->where('name', $setting)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasInclusion($inclusion, $user_id)
    {
        if ($this->find($user_id)
            ->inclusion()
            ->where('name', $inclusion)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasPersonType($person_type, $user_id)
    {
        if ($this->find($user_id)
            ->person_type()
            ->where('name', $person_type)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasEmploymentType($employment_type, $organization_id)
    {
        if ($this->find($organization_id)
            ->employment_type()
            ->where('name', $employment_type)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasAttendanceType($attendance_type, $organization_id)
    {
        if ($this->find($organization_id)
            ->attendance_type()
            ->where('name', $attendance_type)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasAttendanceSetting($attendance_setting, $organization_id)
    {
        if ($this->find($organization_id)
            ->attendance_setting()
            ->where('name', $attendance_setting)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasServiceType($service_type, $organization_id)
    {
        if ($this->find($organization_id)
            ->service_type()
            ->where('name', $service_type)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasAnyModule($modules, $organization_id)
    {
        if (is_array($modules)) {
            foreach ($modules as $module) {
                if ($this->hasModule($module, $organization_id)) {
                    return true;
                }
            }
        } else {
            if ($this->hasModule($modules, $organization_id)) {
                return true;
            }
        }
        return false;
    }

    public static function checkModuleExists($modules, $organization_id)
    {
        Log::info('OrganizationModule->checkModuleExists Inside Of The Function');

        Log::info('module->' . json_encode($modules));

        if ($modules && $organization_id) {

            Log::info('OrganizationModule-> Inside Of The  First Main Condition');

            if (self::find($organization_id)->modules()
                ->where('name', $modules)
                ->first() && $modules && $organization_id) {

                Log::info('OrganizationModule-> Inside Of The  Second Main Condition');

                return true;
            }

            return false;
        }

        return false;
    }

    public static function checkPlan($plan_name, $organization_id, $return_plan = false)
    {

        // dd($plan_name);
        $subscription_plan = OrganizationPackage::select('organization_packages.plan_id', 'organization_packages.organization_id', 'subscription_plans.name')->leftjoin('subscription_plans', 'subscription_plans.id', '=', 'organization_packages.plan_id')
            ->where('organization_packages.organization_id', $organization_id)
            ->whereIn('subscription_plans.name', $plan_name)
            ->first();

        if ($return_plan) { // get plan name - using include file (menu)

            return $subscription_plan->name;
        }

        if ($subscription_plan) {
            return true;
        }
        return false;
    }

    public function hasModule($module, $organization_id)
    {
        if ($this->find($organization_id)
            ->modules()
            ->where('name', $module)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasOrganizations($id)
    {
        return $this->users()->where('organization_id', $id);
    }

    public function hasTax($tax, $organization_id)
    {
        if ($this->find($organization_id)
            ->tax()
            ->where('name', $tax)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasTaxGroup($group, $organization_id)
    {
        if ($this->find($organization_id)
            ->tax_group()
            ->where('name', $group)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasUnit($unit, $organization_id)
    {
        if ($this->find($organization_id)
            ->unit()
            ->where('name', $unit)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasInventoryCategory($category, $organization_id)
    {
        if ($this->find($organization_id)
            ->inventoryCategory()
            ->where('name', $category)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasInventoryItem($item, $organization_id)
    {
        if ($this->find($organization_id)
            ->inventoryItem()
            ->where('name', $item)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasDiscount($discount, $organization_id)
    {
        if ($this->find($organization_id)
            ->inventoryDiscount()
            ->where('name', $discount)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasInventoryStore($store, $organization_id)
    {
        if ($this->find($organization_id)
            ->inventoryStore()
            ->where('name', $store)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasShipmentMode($shipmentMode, $organization_id)
    {
        if ($this->find($organization_id)
            ->shipmentMode()
            ->where('name', $shipmentMode)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasPayrollFrequency($payrollFrequency, $organization_id)
    {
        if ($this->find($organization_id)
            ->payrollFrequency()
            ->where('name', $payrollFrequency)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasWeekoff($weekoff, $organization_id)
    {
        if ($this->find($organization_id)
            ->weekoff()
            ->where('name', $weekoff)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasHolidays($holidays, $organization_id)
    {
        if ($this->find($organization_id)
            ->holidays()
            ->where('name', $holidays)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasHolidayTypes($holidayTypes, $organization_id)
    {
        if ($this->find($organization_id)
            ->holidayTypes()
            ->where('name', $holidayTypes)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasLeaveTypes($leaveTypes, $organization_id)
    {
        if ($this->find($organization_id)
            ->leaveTypes()
            ->where('name', $leaveTypes)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasShift($shift, $organization_id)
    {
        if ($this->find($organization_id)
            ->shift()
            ->where('name', $shift)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasDesignation($designation, $organization_id)
    {
        if ($this->find($organization_id)
            ->designation()
            ->where('name', $designation)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasDepartment($department, $organization_id)
    {
        if ($this->find($organization_id)
            ->department()
            ->where('name', $department)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasSalaryScale($salaryscale, $organization_id)
    {
        if ($this->find($organization_id)
            ->salaryscale()
            ->where('name', $salaryscale)
            ->first()) {
            return true;
        }
        return false;
    }

    public function hasBreak($break, $organization_id)
    {
        if ($this->find($organization_id)
            ->break()
            ->where('name', $break)
            ->first()) {
            return true;
        }
        return false;
    }
}
