<?php

namespace App\Http\Controllers;

use App\Models\Cleaning;
use Illuminate\Http\Request;

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
        if ($request->has('all')) {
            return Cleaning::all();
        }

        // if ($request->filled('search')) {
        //     return Cleaning::where('maker', 'LIKE', '%' . $request->get('search') . '%')
        //         ->orWhere('start_year', 'LIKE', '%' . $request->get('search') . '%')
        //         ->orWhere('end_year', 'LIKE', '%' . $request->get('search') . '%')
        //         ->paginate(10)
        //         ->setPath('')
        //         ->appends(array(
        //             'search' => $request->get('search'),
        //         ));
        // }

        return Cleaning::paginate(10);
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
