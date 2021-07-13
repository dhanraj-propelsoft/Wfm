<?php

namespace App\Http\Controllers\Hrm\Repository;


interface EmployeeRepositoryInterface
{

    public function findAllEmployee($orgId);
}
