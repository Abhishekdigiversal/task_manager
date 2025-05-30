<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string',
            'status' => 'required|in:Active,Paused,Closed',
            'recurrence' => 'required|in:daily,weekly,monthly,custom',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return $task->load('project');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:Active,Paused,Closed',
            'recurrence' => 'sometimes|required|in:daily,weekly,monthly,custom',
            'start_date' => 'sometimes|required|date',
            'due_date' => 'sometimes|required|date|after_or_equal:start_date',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function completeTask(Task $task)
    {
        $project = $task->project;

        // Mark the task as Closed (completed)
        $task->status = 'Closed';
        $task->save();

        // Only create next task if project is Active
        if ($project->status === 'Active') {
            // Calculate next task dates based on recurrence
            $nextStartDate = Carbon::parse($task->start_date);
            $nextDueDate = Carbon::parse($task->due_date);

            switch ($task->recurrence) {
                case 'daily':
                    $nextStartDate->addDay();
                    $nextDueDate->addDay();
                    break;
                case 'weekly':
                    $nextStartDate->addWeek();
                    $nextDueDate->addWeek();
                    break;
                case 'monthly':
                    $nextStartDate->addMonth();
                    $nextDueDate->addMonth();
                    break;
                case 'custom':
                    // For simplicity, do not auto-create custom recurrence
                    return response()->json([
                        'message' => 'Task completed. Custom recurrence tasks are not auto-created.'
                    ]);
            }

            // Create next task
            $nextTask = Task::create([
                'project_id' => $project->id,
                'title' => $task->title,
                'status' => 'Active',
                'recurrence' => $task->recurrence,
                'start_date' => $nextStartDate->toDateString(),
                'due_date' => $nextDueDate->toDateString(),
            ]);

            return response()->json([
                'message' => 'Task completed and next task created.',
                'next_task' => $nextTask,
            ]);
        } else {
            return response()->json([
                'message' => 'Task completed but project is not Active, no new task created.'
            ]);
        }
    }

     public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }


}
