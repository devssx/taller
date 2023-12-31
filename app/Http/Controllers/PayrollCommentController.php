<?php

namespace App\Http\Controllers;

use App\PayrollComment;
use Illuminate\Http\Request;

class PayrollCommentController extends Controller
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

    public function getUserComments(Request $request)
    {
        return PayrollComment::where(['week' => $request->get('week'), 'workshop_id' => $request->get('workshop')])->get();
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
     * @param  \App\PayrollComment  $payrollComment
     * @return \Illuminate\Http\Response
     */
    public function show(PayrollComment $payrollComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PayrollComment  $payrollComment
     * @return \Illuminate\Http\Response
     */
    public function edit(PayrollComment $payrollComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PayrollComment  $payrollComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PayrollComment $payrollComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PayrollComment  $payrollComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(PayrollComment $payrollComment)
    {
        //
    }
}
