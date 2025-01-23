<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('task.index', compact('tasks'));
    }

    // Simpan task baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:All,Work,Diary,Practice',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
        ]);
    
        // Debugging: log data
        Log::info('Validated data:', $validated);
    
        // Simpan ke database
        $task = Task::create($validated);
    
        return response()->json(['task' => $task], 200);
    }
    

    // Update task
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:All,Work,Diary,Practice',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
        ]);

        $task->update($request->all());
        return response()->json(['task' => $task], 200);
    }

    // Hapus task
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true], 200);
    }
}
