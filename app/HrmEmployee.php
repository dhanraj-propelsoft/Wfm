<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrmEmployee extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $connection;
    
    public function __construct(){
        parent::__construct();
        
        $this->connection = "mysql";
    }

   	/**
	 * Convert to ProperCase.
	 *
	 * @return string
	 */
	public function getLastNameAttribute($value)
	{
		$value = strtolower($value);
		return ucwords($value);
	}
	/**
	 * Get the user's full name.
	 *
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		 $first_name = strtolower($this->first_name);
		 $last_name = strtolower($this->last_name);
		 return ucwords($first_name." ".$last_name);
	}

	 /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords($value);
    }

	 /**
     * Set the user's last name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords($value);
    }

}
