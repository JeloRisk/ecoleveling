<?php

namespace App\Http\Controllers;

use App\Models\RoomOccupancyLogBook;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomOccupancyLogBookController extends Controller
{

    // public function index()
    // {
    //     $logBooks = RoomOccupancyLogBook::all();

    //     $logBooksPerDay = $logBooks->groupBy('date');

    //     $usageMinutesPerDay = $logBooksPerDay->map(function ($logBooks) {
    //         return $logBooks->sum('usageMinutes');
    //     });

    //     return response()->json($usageMinutesPerDay);
    // }

    // public function index(Request $request)
    // {
    //     $logBooksQuery = RoomOccupancyLogBook::query();

    //     if ($request->has('start_date')) {
    //         $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
    //         $logBooksQuery->where('date', '>=', $startDate);
    //     }

    //     if ($request->has('end_date')) {
    //         $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
    //         $logBooksQuery->where('date', '<=', $endDate);
    //     }

    //     $logBooks = $logBooksQuery->get();

    //     $logBooksPerDay = $logBooks->groupBy('date');


    //     $usageMinutesPerDay = $logBooksPerDay->map(function ($logBooks) {
    //         return $logBooks->sum('usageMinutes');
    //     });


    //     $labels = $usageMinutesPerDay->keys()->toArray();
    //     $data = $usageMinutesPerDay->values()->toArray();

    //     return response()->json([
    //         'labels' => $labels,
    //         'data' => $data
    //     ]);
    // }

    // public function index(Request $request)
    // {
    //     $logBooksQuery = RoomOccupancyLogBook::query();

    //     // check if the room_id parameter is in the request and filter it by the ID.
    //     if ($request->has('room_id')) {
    //         $logBooksQuery->where('room_id', $request->input('room_id'));
    //     }

    //     // retrieve log books and group by date
    //     $logBooks = $logBooksQuery->get();
    //     $logBooksPerDay = $logBooks->groupBy('date');

    //     // calculate usage minutes per day
    //     $usageMinutesPerDay = $logBooksPerDay->map(function ($logBooks) {
    //         return $logBooks->sum('usageMinutes');
    //     });

    //     // labels -> dates, data is usage minutes
    //     $labels = $usageMinutesPerDay->keys()->toArray();
    //     $data = $usageMinutesPerDay->values()->toArray();

    //     return response()->json([
    //         'labels' => $labels,
    //         'data' => $data
    //     ]);
    // }

    public function index(Request $request)
    {
        $logBooksQuery = RoomOccupancyLogBook::query();

        // check if the room_id parameter is in the request and filter it by the ID.
        if ($request->has('room_id')) {
            $logBooksQuery->where('room_id', $request->input('room_id'));
        }

        // Sort the log books by date
        $logBooksQuery->orderBy('date');

        // retrieve log books and group by date
        $logBooks = $logBooksQuery->get();
        $logBooksPerDay = $logBooks->groupBy('date');

        // calculate usage minutes per day
        $usageMinutesPerDay = $logBooksPerDay->map(function ($logBooks) {
            return $logBooks->sum('usageMinutes');
        });

        // labels -> dates, data is usage minutes
        $labels = $usageMinutesPerDay->keys()->toArray();
        $data = $usageMinutesPerDay->values()->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
