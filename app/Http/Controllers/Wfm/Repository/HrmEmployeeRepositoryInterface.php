<?php
namespace App\Http\Controllers\Wfm\Repository;

use App\Http\Controllers\Wfm\Model\WfmDepartment;

interface HrmEmployeeRepositoryInterface
{
	 public function getEmployeeDatasByOrgId($orgId);

	 
}