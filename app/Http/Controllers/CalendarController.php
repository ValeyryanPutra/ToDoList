<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Event;
use Illuminate\Http\Request;

class CalendarController extends Controller
{

    public function index()
    {
        $events = Event::select('id', 'name as title', 'date as start')->get();
        return view('calendar.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        Event::create($request->all());
        return response()->json(['message' => 'Event created successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json(['message' => 'Event updated successfully']);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }
}
