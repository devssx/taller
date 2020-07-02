<?php

namespace App\Http\Controllers;

use App\Payroll;
use App\PayrollComment;
use App\Comment;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;

class PayrollController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function saveComment(Request $request)
    {
        $c = Comment::firstOrCreate(['week' => $request->get('week')]);
        $c->comment = $request->get('comment');
        $c->total = $request->get('total');
        $c->save();
    }

    public function getComment(Request $request)
    {
        return Comment::firstOrCreate(['week' => $request->get('week')]);
    }

    public function getPayroll(Request $request)
    {
        $fecha = new DateTime($request->get('day'));
        $fecha->sub(new DateInterval('P2D'));

        return ['fech' => $fecha->format('Ymd')];
    }

    public function saveWeek(Request $request)
    {
        // saves the comment
        if ($request->has('comment')) {
            $userComment =  PayrollComment::firstOrCreate(['week' => $request->get('week'), 'user_id' => $request->get('user_id')]);
            $userComment->comment = $request->get('comment');
            $userComment->save();
        }

        $payroll =  Payroll::firstOrCreate(['week' => $request->get('week'), 'user_id' => $request->get('user_id')]);
        $payroll->type = $request->get('type');
        $payroll->total_week = $request->get('total_week');
        $payroll->comission = $request->get('typcomissione');
        $payroll->discount = $request->get('discount');
        $payroll->salary = $request->get('salary');
        $payroll->total = $request->get('total');
        $payroll->save();

        //$table->unsignedInteger('week');
        //$table->unsignedInteger('user_id');

        /*
        $table->integer('type');
        $table->decimal('total_week');
        $table->decimal('comission');
        $table->decimal('discount');
        $table->decimal('salary');
        $table->decimal('total');
        */
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
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function show(Payroll $payroll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payroll $payroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payroll $payroll)
    {
        //
    }
}
