<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    public function taskSummary()
    {
        // 1. Hitung jumlah tugas yang sudah selesai untuk user saat ini
        $completedTasksCount = Task::where('user_id', Auth::id())
            ->where('is_completed', true)
            ->count();

        // 2. Hitung jumlah tugas yang belum selesai untuk user saat ini
        $incompleteTasksCount = Task::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->count();

        // 3. Rekap harian tugas selesai
        $dailyCompletedTasks = Task::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('user_id', Auth::id())
            ->where('is_completed', true)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // 4. Rekap harian tugas belum selesai
        $dailyIncompleteTasks = Task::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('user_id', Auth::id())
            ->where('is_completed', false)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Kirim semua data ke view
        return view('charts.index', compact(
            'completedTasksCount',
            'incompleteTasksCount',
            'dailyCompletedTasks',
            'dailyIncompleteTasks'
        ));
    }
}
