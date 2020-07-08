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
