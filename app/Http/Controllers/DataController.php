<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DataController extends Controller
{

    public function setServiceStartTime(Request $request)
    {
        $startTime = $request->input('start_time');
        // Save the start time to the database or session
        session(['service_start_time' => $startTime]);
        return response()->json(['status' => 'success']);
    }

    public function getServiceStartTime()
    {
        // Retrieve the start time from the database or session
        $startTime = session('service_start_time', null);
        return response()->json(['start_time' => $startTime]);
    }

    public function monitorFetchData()
    {
        // Fetch all visitor data
        $data = Visitor::all();

        return response()->json($data);
        
    }    
    
    public function fetchData()
    {
        // Fetch all visitor data
        $data = Visitor::where('department', 'accounting')->get();
        return response()->json($data);
    }

    public function nextTicket(Request $request)
{
    // Find the current ticket for accounting visitors
    // $currentTicket = Visitor::where('status', 'serving')->where('department', 'accounting')->first();
    $currentTicket = Visitor::where('status', 'serving')->where('department', 'accounting')->first();
    if ($currentTicket) {
        // Mark the current ticket as completed
        $currentTicket->status = 'completed';
        $currentTicket->save();
    }

    // Find the next ticket in the queue for accounting visitors
    $nextTicket = Visitor::where('status', 'waiting')->where('department', 'accounting')->orderBy('created_at', 'asc')->first();
    if ($nextTicket) {
        // Mark the next ticket as serving
        $nextTicket->status = 'serving';
        $nextTicket->save();
    }

    // Fetch updated queue data for accounting visitors
    $queueData = Visitor::where('status', 'waiting')->where('department', 'accounting')->orderBy('created_at', 'asc')->get();

    return response()->json([
        'queueData' => $queueData, // Include the updated queue data in the response
        'message' => 'Next ticket processed successfully.'
    ]);
}

public function cancelTicket(Request $request)
{
    // Find the current ticket for accounting visitors
    $currentTicket = Visitor::where('status', 'serving')->where('department', 'accounting')->first();

    if ($currentTicket) {
        // Mark the current ticket as canceled
        $currentTicket->status = 'canceled';
        $currentTicket->save();
    }

    // Find the next ticket in the queue for accounting visitors
    $nextTicket = Visitor::where('status', 'waiting')->where('department', 'accounting')->orderBy('created_at', 'asc')->first();

    if ($nextTicket) {
        // Mark the next ticket as serving
        $nextTicket->status = 'serving';
        $nextTicket->save();
    }

    // Fetch updated queue data for accounting visitors
    $queueData = Visitor::where('status', 'waiting')->where('department', 'accounting')->orderBy('created_at', 'asc')->get();

    return response()->json([
        'queueData' => $queueData, // Include the updated queue data in the response
        'message' => 'Next ticket processed successfully.'
    ]);
}


    public function getVisitorData()
    {
        $visitors = Visitor::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                            ->groupBy('date')
                            ->orderBy('date', 'ASC')
                            ->get();

        return response()->json($visitors);
    }
    public function getVisitorCount()
    {
        $visitorCount = Visitor::count();

        return response()->json(['count' => $visitorCount]);
    }











    public function fetchdatas()
    {
        // Fetch all visitor data
        $data = Visitor::where('department', 'records')->get();
        return response()->json($data);
    }

    public function nextTicket1(Request $request)
{
    // Find the current ticket for accounting visitors
    // $currentTicket = Visitor::where('status', 'serving')->where('department', 'accounting')->first();
    $currentTicket = Visitor::where('status', 'serving')->where('department', 'records')->first();

    if ($currentTicket) {
        // Mark the current ticket as completed
        $currentTicket->status = 'completed';
        $currentTicket->save();
    } 

    // Find the next ticket in the queue for accounting visitors
    $nextTicket = Visitor::where('status', 'waiting')->where('department', 'records')->orderBy('created_at', 'asc')->first();
    if ($nextTicket) {
        // Mark the next ticket as serving
        $nextTicket->status = 'serving';
        $nextTicket->save();
    } 
    // Fetch updated queue data for accounting visitors
    $queueData = Visitor::where('status', 'waiting')->where('department', 'records')->orderBy('created_at', 'asc')->get();

    return response()->json([
        'queueData' => $queueData, // Include the updated queue data in the response
        'message' => 'Next ticket processed successfully.'
    ]);
}

public function cancelTicket1(Request $request)
{
    // Find the current ticket for accounting visitors
    $currentTicket = Visitor::where('status', 'serving')->where('department', 'records')->first();

    if ($currentTicket) {
        // Mark the current ticket as canceled
        $currentTicket->status = 'canceled';
        $currentTicket->save();
    }

    // Find the next ticket in the queue for accounting visitors
    $nextTicket = Visitor::where('status', 'waiting')->where('department', 'records')->orderBy('created_at', 'asc')->first();

    if ($nextTicket) {
        // Mark the next ticket as serving
        $nextTicket->status = 'serving';
        $nextTicket->save();
    }

    // Fetch updated queue data for accounting visitors
    $queueData = Visitor::where('status', 'waiting')->where('department', 'records')->orderBy('created_at', 'asc')->get();

    return response()->json([
        'queueData' => $queueData, // Include the updated queue data in the response
        'message' => 'Next ticket processed successfully.'
    ]);
}


    
}
