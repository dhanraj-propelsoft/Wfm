<?php

namespace App;

use Session;
use App\Organization;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        
        $this->connection = "mysql";
    }
    
    use HasApiTokens, Notifiable, EntrustUserTrait;

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'otp', 'otp_time', 'otp_sent', 'is_active', 'created_at', 'updated_at', 'status', 'person_id'
    ];

    public function roles()
    {
        return $this->belongsToMany(Config::get('entrust.role'), Config::get('entrust.role_user_table'), Config::get('entrust.user_foreign_key'), Config::get('entrust.role_foreign_key'))->where('role_user.organization_id', Session::get('organization_id'));
    }

    public function groupParent() {
        return $this->hasMany('App\AccountGroupParent');
    }

    public function group() {
        return $this->hasMany('App\AccountGroup');
    }

    public function ledger() {
        return $this->hasMany('App\AccountLedger');
    }

    public function voucher() {
        return $this->hasMany('App\AccountVoucher');
    }

    public function accountFormat() {
        return $this->hasMany('App\AccountVoucherFormat');
    }

    public function accountPrint() {
        return $this->hasMany('App\PrintTemplate');
    }

    public function setting() {
        return $this->hasMany('App\Setting');
    }

    public function availableModules() {
        return json_decode($this->modules()->get(), true);
    }

    public function hasAccountGroupParent($parent, $user_id) {
        if($this->find($user_id)->groupParent()->where('name', $parent)->first()) {
            return true;     
        }
        return false;
    }

    public function hasAccountGroup($group, $user_id) {
        if($this->find($user_id)->group()->where('name', $group)->first()) {
            return true;     
        }
        return false;
    }

    public function hasAccountLedger($ledger, $person_id, $business_id, $user_id) {
        if($this->find($user_id)->ledger()->where('name', $ledger)->where('person_id', $person_id)->where('business_id', $business_id)->first()) {
            return true;     
        }
        return false;
    }

    public function hasVoucher($voucher, $user_id) {
        if($this->find($user_id)->voucher()->where('name', $voucher)->first()) {
            return true;     
        }
        return false;
    }

    public function hasFormat($format, $user_id) {
        if($this->find($user_id)->accountFormat()->where('name', $format)->first()) {
            return true;     
        }
        return false;
    }

    public function hasPrint($print, $user_id) {
        if($this->find($user_id)->accountPrint()->where('name', $print)->first()) {
            return true;     
        }
        return false;
    }

    public function hasSetting($setting, $user_id) {
        if($this->find($user_id)->setting()->where('name', $setting)->first()) {
            return true;     
        }
        return false;
    }
}
