<?php

namespace App\Http\Controllers;

use App\Models\RoomOccupancyLogBook;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomOccupancyLogBookController extends Controller
{



    public function index(Request $request)
    {
        $logBooksQuery = RoomOccupancyLogBook::query();

        if ($request->has('room_id')) {
            $logBooksQuery->where('room_occupancy_log_books.room_id', $request->input('room_id'));
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay(); // Include end of day
            $kWh = Carbon::parse($request->input('usageMinutes'));

            $kWh = Carbon::parse($request->input('kWh'));
            $logBooksQuery->whereBetween('date', [$startDate, $endDate]);
        }

        // alaen tay idjay room table to retrieve the Room Number
        $logBooksQuery->leftJoin('rooms', 'room_occupancy_log_books.room_id', '=', 'rooms.id')
            ->select('room_occupancy_log_books.*', 'rooms.roomNumber');

        // sort  the log books by date
        $logBooksQuery->orderByDesc('date')->orderByDesc('endTime');
        $logBooks = $logBooksQuery->get();

        return response()->json($logBooks);
    }


    public function getChartData(Request $request)
    {
        $logBooksQuery = RoomOccupancyLogBook::query();

        // check if the room_id parameter is in the request and filter it by the ID.
        if ($request->has('room_id')) {
            $logBooksQuery->where('room_id', $request->input('room_id'));
        }

        // sort the log books by date
        $logBooksQuery->orderBy('date');

        // retrieve log books and group by date
        $logBooks = $logBooksQuery->get();
        $logBooksPerDay = $logBooks->groupBy('date');

        // calculate usage minutes per day
        $usageMinutesPerDay = $logBooksPerDay->map(function ($logBooks) {
            return $logBooks->sum('kWh');
        });

        // labels -> dates, data is usage minutes
        $labels = $usageMinutesPerDay->keys()->toArray();
        $data = $usageMinutesPerDay->values()->toArray();

        if ($request->input('period') === 'yearly') {
            // Group usage minutes by month
            $logBooksPerMonth = $logBooks->groupBy(function ($logBook) {
                return Carbon::parse($logBook->date)->format('Y-m');
            });

            // Calculate usage minutes per month
            $usageMinutesPerMonth = $logBooksPerMonth->map(function ($logBooks) {
                return $logBooks->sum('kWh');
            });

            // Labels -> months, data is usage minutes
            $labels = $usageMinutesPerMonth->keys()->map(function ($month) {
                return Carbon::parse($month)->format('Y-m');
            })->toArray();

            $data = $usageMinutesPerMonth->values()->toArray();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }


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
