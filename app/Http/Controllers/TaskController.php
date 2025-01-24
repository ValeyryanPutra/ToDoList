<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'All');
        
        // Filter tasks berdasarkan kategori jika ada
        $tasks = Task::when($category !== 'All', function ($query) use ($category) {
            return $query->where('category', $category);
        })->get();
    
        // Group tasks berdasarkan kategori waktu
        $today = now()->startOfDay();
        
        // Mengelompokkan tugas berdasarkan tanggal (tanpa Masa Lalu)
        $groupedTasks = [
            'Hari Ini' => $tasks->filter(fn($task) => $task->created_at->isToday()), // Tugas yang dibuat hari ini
            'Masa yang Akan Datang' => $tasks->filter(fn($task) => $task->created_at->gt($today)), // Tugas yang dibuat setelah hari ini
        ];
    
        return view('task.index', compact('groupedTasks', 'category'));
    }
    
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:All,Work,Diary,Practice',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
        ]);

        Task::create($request->all());

        return redirect()->route('task.index')->with('success', 'Task created successfully.');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:All,Work,Diary,Practice',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->all());

        return redirect()->route('task.index')->with('success', 'Task updated successfully.');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('task.index')->with('success', 'Task deleted successfully.');
    }}
