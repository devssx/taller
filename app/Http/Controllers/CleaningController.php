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
        // if ($request->has('id')) {
        //     return Cleaning::updateOrCreate($request->only('id'), $request->except(['id', 'year', 'created_at', 'deleted_at', 'updated_at']));
        // }

        // $car = Cleaning::where('brand', '=', $request->get('brand'))
        //     ->where('start_year', '=', $request->get('year')[0])
        //     ->where('end_year', '=', $request->get('year')[1])
        //     ->first();

        // if ($car) {
        //     return response()->json([
        //         'error' => 'El Carro ya existe',
        //     ]);
        // }

        // return Cleaning::firstOrCreate([
        //     'maker' => $request->get('maker'),
        //     'brand' => $request->get('brand'),
        //     'motor' => $request->get('motor'),
        //     'image' => $request->get('image'),
        //     'start_year' => $request->get('year')[0],
        //     'end_year' => $request->get('year')[1],
        // ]);
    }

    public function delete($id)
    {
        return response()->json([
            'success' => Cleaning::findOrFail($id)->delete(),
        ]);
    }
}
