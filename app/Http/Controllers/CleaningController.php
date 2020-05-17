<?php

namespace App\Http\Controllers;

use App\Models\Cleaning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CleaningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function get(Request $request)
    {
        return DB::table('cleanings')
            ->join('users', 'users.id', 'cleanings.user_id')
            ->select('cleanings.*', 'users.name')
            ->get();
    }

    public function searchBetween(Request $request)
    {
        // Join User Name
        // SELECT * FROM `cleanings` WHERE `start` > '2020-05-15' and `start` < '2020-05-16'
        if ($request->has('start') && $request->has('end')) {

            $data = DB::table('cleanings')
                ->join('users', 'users.id', 'cleanings.user_id')
                ->select('cleanings.*', 'users.name')
                ->where('cleanings.start', '>=', $request->get('start'))
                ->where('cleanings.start', '<=', $request->get('end'))
                ->get();

            return $data;
        }

        if ($request->has('today')) {

            return DB::table('cleanings')
                ->join('users', 'users.id', 'cleanings.user_id')
                ->select('cleanings.*', 'users.name')
                ->where('cleanings.start', '>=', $request->get('today'))
                ->get();
        }


        // all
        return DB::table('cleanings')
            ->join('users', 'users.id', 'cleanings.user_id')
            ->select('cleanings.*', 'users.name')
            ->get();
    }

    public function save(Request $request)
    {
        // Update
        if ($request->has('id')) {
            return Cleaning::updateOrCreate($request->only('id'), $request->except(['id', 'created_at', 'deleted_at', 'updated_at']));
        }

        // $car = Cleaning::where('brand', '=', $request->get('brand'))
        //     ->where('start_year', '=', $request->get('year')[0])
        //     ->where('end_year', '=', $request->get('year')[1])
        //     ->first();

        // if ($car) {
        //     return response()->json([
        //         'error' => 'El Carro ya existe',
        //     ]);
        // }

        return Cleaning::firstOrCreate([
            'user_id' => $request->get('user_id'),
            'start' => $request->get('start'),
            'cleaning' => $request->get('cleaning'),
            'breakfast_start' => $request->get('breakfast_start'),
            'breakfast_end' => $request->get('breakfast_end'),
            'lunch_start' => $request->get('lunch_start'),
            'lunch_end' => $request->get('lunch_end'),
            'done' => $request->get('done'),
            'comment' => $request->get('comment'),
        ]);
    }

    public function delete($id)
    {
        return response()->json([
            'success' => Cleaning::findOrFail($id)->delete(),
        ]);
    }
}
