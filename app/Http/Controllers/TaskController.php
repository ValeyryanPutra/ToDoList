<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $categoryId = $request->get('category', 'All');
        $today = Carbon::today();

        // Ambil semua kategori dari database
        $categories = Category::all();
        $status = $request->get('status', 'all');
        // Query untuk mengambil tugas berdasarkan kategori yang dipilih
        $tasks = Task::where('user_id', Auth::id())
            ->when($categoryId !== 'All', function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })        ->when($status === 'completed', function ($query) {
                return $query->where('is_completed', true);
            })
            ->when($status === 'pending', function ($query) {
                return $query->where('is_completed', false);
            })
            ->orderBy('deadline', 'asc')
            ->get();

            

        $groupedTasks = collect();

        // Tugas yang sudah selesai
        if ($tasks->where('is_completed', true)->isNotEmpty()) {
            $groupedTasks->put('Selesai', $tasks->where('is_completed', true));
        }

        // Tugas untuk hari ini
        if ($tasks->where('is_completed', false)->filter(fn($task) => $task->deadline && Carbon::parse($task->deadline)->isSameDay($today))->isNotEmpty()) {
            $groupedTasks->put('Hari Ini', $tasks->where('is_completed', false)->filter(fn($task) => $task->deadline && Carbon::parse($task->deadline)->isSameDay($today)));
        }

        // Tugas untuk masa mendatang
        if ($tasks->where('is_completed', false)->filter(fn($task) => $task->deadline && Carbon::parse($task->deadline)->isAfter($today))->isNotEmpty()) {
            $groupedTasks->put('Masa Mendatang', $tasks->where('is_completed', false)->filter(fn($task) => $task->deadline && Carbon::parse($task->deadline)->isAfter($today)));
        }

        // Tugas yang sudah lewat deadline
        if ($tasks->where('is_completed', false)->filter(fn($task) => $task->deadline && Carbon::parse($task->deadline)->isBefore($today))->isNotEmpty()) {
            $groupedTasks->put('Sudah Lewat', $tasks->where('is_completed', false)->filter(fn($task) => $task->deadline && Carbon::parse($task->deadline)->isBefore($today)));
        }

        // Tugas tanpa deadline
        if ($tasks->where('is_completed', false)->whereNull('deadline')->isNotEmpty()) {
            $groupedTasks->put('Tanpa Deadline', $tasks->where('is_completed', false)->whereNull('deadline'));
        }

        return view('task.index', compact('groupedTasks', 'categoryId', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|string|in:Low,Medium,High',
            'deadline' => 'nullable|date|after_or_equal:today',
            'category_id' => 'required|exists:categories,id', // Fix validasi category_id
        ]);

        $task = new Task($request->all());
        $task->user_id = Auth::id();
        $task->is_completed = false; // Default Pending
        $task->status = 'Pending';
        $task->save();

        return redirect()->route('task.index')->with('success', 'Task created successfully.');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);

        return response()->json($task);
    }
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date|after_or_equal:today',
            'is_completed' => 'boolean',
        ]);

        $task->update($validatedData);
        $task->status = $task->is_completed ? 'Completed' : 'Pending';
        $task->save();

        return redirect()->route('task.index')->with('success', 'Task updated successfully.');
    }

    public function updatePriority(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->priority = $request->prioritas;
        $task->save();

        return response()->json(['success' => true]);
    }



    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('task.index')->with('success', 'Task deleted successfully.');
    }

    public function toggleComplete(Task $task)
    {
        $task->is_completed = !$task->is_completed;
        $task->status = $task->is_completed ? 'Completed' : 'Pending';
        $task->save();

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    public function getTasksByDate(Request $request)
    {
        $date = Carbon::parse($request->input('date'));
        $tasks = Task::where('user_id', Auth::id())
            ->whereDate('deadline', $date)
            ->get();

        return response()->json($tasks);
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);

        $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $task->update(['is_completed' => $request->is_completed]);

        return response()->json(['success' => true, 'message' => 'Task status updated successfully.']);
    }
}
