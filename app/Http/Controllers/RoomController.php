<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view('index', [
        //     'rooms' => Room::take(10)->get(),
        // ]);
        $products = Room::all()->toArray();
        return array_reverse($products);
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
    public function show($id)
    {
        $product = Room::find($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $product = Room::find($id);
        $product->update($request->all());
        return response()->json('Room updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
