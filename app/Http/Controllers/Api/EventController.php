<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\models\EventCategory; // Assuming you have an Event model


class EventController extends Controller
{
    //index
    public function index($request)
    {
        //event by category_id
        $events = Event::where('event_category_id', $request->category_id)->get();
        //if category_id all
        if ($request->category_id == 'all') {
            $events = Event::all();
        }
        //all event
        //$events = Event::all(); 
        //load event_category and vendor
        $events->load(['eventCategory', 'vendor']);
        return response()->json([
            'status' => 'success',
            'message' => 'Events fetched successfully',
            'data' => $events, 
        ]);
    }

    //get all events categories
    public function categories()
    {
        $categories = EventCategory::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Event categories fetched successfully',
            'data' => $categories,
        ]);
    }

    //detail event and sku by event_id
    public function detail($request)
    {
        //event by event_id
        $event = Event::find($request->event_id);
        //load event_category and vendor
        $event->load(['eventCategory', 'vendor']);
        $skus = $event->skus;
        $event['skus'] = $skus;
        return response()->json([
            'status' => 'success',
            'message' => 'Event details fetched successfully',
            'data' => $event,
        ]);
    }
}
