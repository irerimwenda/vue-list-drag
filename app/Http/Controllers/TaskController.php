<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Fetch all project tasks.
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function fetch(Project $project): JsonResponse
    {
        return response()->json($project->tasks()->orderBy('priority')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request, Project $project)
    {
        $this->validate($request, [
            'title' => 'required'
        ], [
            'title.required' => 'Task title is required.'
        ]);

        $latestPriority = $project->tasks()->max('priority');

        $project->tasks()->create([
            'title' => $request->input('title'),
            'priority' => $latestPriority + 1
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws ValidationException
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'title' => 'required'
        ], [
            'title.required' => 'Task title is required.'
        ]);

        $task->update([
            'title' => $request->input('title')
        ]);
    }

    /**
     * Update task priority order.
     *
     * @param Request $request
     * @param Project $project
     * @return void
     */
    public function updatePriority(Request $request, Project $project)
    {
        $oldPriority = ($request->input('itemOldDragIndex') + 1); // Priority 4
        $newPriority = ($request->input('itemNewDragIndex') + 1); // Priority 2

        $newListedTask = $project->tasks()->where('priority', $oldPriority)->first(); // Task 4

        $replacedTask = $project->tasks()->where('priority', $newPriority)->first(); // Task 2

        $inBetweenTasks = $project->tasks()->orderBy('priority')->whereBetween('priority', [$newPriority, $oldPriority])->get(); // Task 2

        foreach ($inBetweenTasks as $task) {
            $task->update([
                'priority' => $task->priority + 1
            ]);
        }

        $newListedTask->update([
            'priority' => $newPriority
        ]);

        if (count($inBetweenTasks) === 0) {
            $replacedTask->update([
                'priority' => $oldPriority
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
    }
}
