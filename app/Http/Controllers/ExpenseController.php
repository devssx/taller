<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
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


    public function searchWeek(Request $request)
    {
        return Expense::where(['week' => $request->get('week'), 'workshop_id' => $request->get('workshop')])->get();
    }

    public function save(Request $request)
    {
        if ($request->has('id')) {
            $c = Expense::firstOrCreate(['id' => $request->get('id')]);
            $c->week = $request->get('week');
            $c->type = $request->get('type');
            $c->workshop_id = $request->get('workshop');
            $c->concept = $request->get('concept');
            $c->amount = $request->get('amount');
            $c->iva = $request->get('iva');
            $c->total = $request->get('total');
            $c->save();
        } else {
            $c = new Expense();
            $c->week = $request->get('week');
            $c->type = $request->get('type');
            $c->workshop_id = $request->get('workshop');
            $c->concept = $request->get('concept');
            $c->amount = $request->get('amount');
            $c->iva = $request->get('iva');
            $c->total = $request->get('total');
            $c->save();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
