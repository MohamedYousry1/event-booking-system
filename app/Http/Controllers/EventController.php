<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;

class EventController extends Controller
{

    public function index()
    {
        $events = Event::with('category')->paginate(10);
        return EventResource::collection($events);
    }

    public function store(EventRequest $request)
    {
        $event = Event::create($request->validated());
        return new EventResource($event);
    }

    public function show(Event $event)
    {
        $event = Event::findOrFail($event->id);
        return new EventResource($event);
    }

    public function update(EventRequest $request, Event $event)
    {
        $event->update($request->validated());
        return new EventResource($event);
    }


    public function destroy(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $event->delete();
        return response()->json([
            "message" => "Event deleted successfully",
        ], 200);
    }
}
