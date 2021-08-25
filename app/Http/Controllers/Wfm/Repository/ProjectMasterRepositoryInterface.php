<?php
namespace App\Http\Controllers\Wfm\Repository;
use App\Http\Controllers\Wfm\Model\Category;


interface ProjectMasterRepositoryInterface
{
	 public function findAll($orgId);

	 public function save(Category $model, $id);

	 // public function destroyById(Category $model);
}