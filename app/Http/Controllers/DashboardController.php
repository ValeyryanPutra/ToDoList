<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil jumlah total user
        $userCount = User::count();

        // Menghitung jumlah tugas selesai dan tidak selesai
        $completedTasks = Task::where('is_completed', true)->count();
        $incompleteTasks = Task::where('is_completed', false)->count();
        
        // Data untuk bar chart (misal berdasarkan bulan)
        $barData = [
            ['January', Task::whereMonth('created_at', 1)->count()],
            ['February', Task::whereMonth('created_at', 2)->count()],
            ['March', Task::whereMonth('created_at', 3)->count()],
            ['April', Task::whereMonth('created_at', 4)->count()],
            ['May', Task::whereMonth('created_at', 5)->count()],
            ['June', Task::whereMonth('created_at', 6)->count()],
        ];

        // Data untuk donut chart
        $donutData = [
            ['label' => 'Completed', 'data' => $completedTasks, 'color' => '#3c8dbc'],
            ['label' => 'Incomplete', 'data' => $incompleteTasks, 'color' => '#0073b7'],
        ];

        return view('admin.index', compact('userCount', 'completedTasks', 'incompleteTasks', 'barData', 'donutData'));
    }
}
