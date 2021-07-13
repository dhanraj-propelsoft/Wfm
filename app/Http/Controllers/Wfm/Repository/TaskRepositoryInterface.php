<?php
namespace App\Http\Controllers\Wfm\Repository;

use App\Http\Controllers\Wfm\Model\Task;
use App\Http\Controllers\Wfm\Model\ProjectTask;
use App\Http\Controllers\Wfm\Model\TaskPriority;
use App\Http\Controllers\Wfm\Model\TaskCreator;
use App\Http\Controllers\Wfm\Model\TaskWorkforce;
use App\Http\Controllers\Wfm\Model\TaskFlow;
use App\Http\Controllers\Wfm\Model\Tag;

interface TaskRepositoryInterface
{
	 public function findAll($orgId);

	 public function create();

	 public function TaskSave(Task $model);

	 public function TaskProjectSave(ProjectTask $model);

	 public function TaskPrioritySave(TaskPriority $model);

	 public function TaskCreatorSave(TaskCreator $model);

	 public function TaskWorkForcerSave(TaskWorkforce $model);

	 public function TaskFlowSave(TaskFlow $model);

	 public function TagSave(Tag $model)

	 public function destroyById(Project $model);

	}