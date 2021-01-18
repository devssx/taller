<?php

namespace App\Http\Controllers;

use App\WorkShop;
use Illuminate\Http\Request;

class WorkShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WorkShop::get();
    }

    public function workshops()
    {
        return view('workshops.index');
    }


    public function save(Request $request)
    {
        if($request->has('id'))
        {
            $wks = WorkShop::findOrFail($request->get('id'));
            $wks->name = $request->get('name');
            $wks->address = $request->get('address');
            $wks->code = $request->get('code');
            $wks->save();
        }
        else
        {
            $wks = WorkShop::create();
            $wks->name = $request->get('name');
            $wks->address = $request->get('address');
            $wks->code = $request->get('code');
            $wks->save();
        }
        return $wks;
    }

    public function delete($id)
    {
        $wks = WorkShop::findOrFail($id);
        $wks->deleted = 1;
        $wks->save();

        return response()->json([
            'success' => $wks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WorkShop  $workShop
     * @return \Illuminate\Http\Response
     */
    public function show(WorkShop $workShop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WorkShop  $workShop
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkShop $workShop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkShop  $workShop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkShop $workShop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkShop  $workShop
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkShop $workShop)
    {
        //
    }
}
