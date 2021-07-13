<?php
namespace App\Http\Controllers\Wfm\Repository;

use App\Http\Controllers\Wfm\Model\Project;

interface ProjectRepositoryInterface
{
	 public function findAllList($orgId);

	 // public function create($orgId);
	 public function findAll($orgId);

	 public function save(Project $model, $id);

	 public function destroyById(Project $model);

}