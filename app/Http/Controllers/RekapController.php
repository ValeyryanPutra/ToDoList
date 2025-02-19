<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index()
    {
        $users = User::all(); // Mengambil semua user
        return view('admin.rekapUsers', compact('users'));
    }

    // Menampilkan rekap task per user
    public function viewTasks($id)
    {
        $user = User::with('tasks')->findOrFail($id); // Mengambil user beserta task-nya
        return view('admin.rekapUsers', compact('user'));
    }
}
