<?php

namespace App\Http\Controllers;

use App\Payroll;
use App\PayrollComment;
use App\Comment;
use App\WorkShop;
use App\User;
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
        $workshops = WorkShop::get();
        $myUser = User::where('id', auth()->id())->get();

        return view('payroll.index', [
            'workshops' => $workshops,
            'myUser' =>  $myUser,
            'multiWorkshop' => auth()->user()->can('cambiar de taller')
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

    public function SelectPayrollByYear(Request $request)
    {
        if ($request->has('year')) {
            $start = $request->get('year') . '0101';
            $end = $request->get('year') . '1231';

            return Payroll::where(['workshop_id' => $request->get('workshop')])
            ->where('week', '>=', $start)
            ->where('week', '<=', $end)
            ->orderBy('week')
            ->get();
        }

        return [];
    }

    public function saveComment(Request $request)
    {
        $c = Comment::firstOrCreate(['week' => $request->get('week'), 'workshop_id' => $request->get('workshop')]);
        $c->comment = $request->get('comment');
        $c->total = $request->get('total');
        $c->save();
    }

    public function getComment(Request $request)
    {
        return Comment::firstOrCreate(['week' => $request->get('week'), 'workshop_id' => $request->get('workshop')]);
    }

    public function getPayroll(Request $request)
    {
        return Payroll::where(['week' => $request->get('week'), 'workshop_id' => $request->get('workshop')])->get();
    }

    public function saveWeek(Request $request)
    {
        // saves the comment
        if ($request->has('comment')) {
            $userComment =  PayrollComment::firstOrCreate(['week' => $request->get('week'), 'user_id' => $request->get('userID'), 'workshop_id' => $request->get('workshop')]);
            $userComment->comment = $request->get('comment');
            $userComment->save();
        }

        // AC
        if ($request->has('totalWeekAc')) {
            $payroll =  Payroll::firstOrCreate(['week' => $request->get('week'), 'user_id' => $request->get('userID'), 'workshop_id' => $request->get('workshop'), 'type' => '1']);
            $payroll->total_week = $request->get('totalWeekAc');
            $payroll->comission = $request->get('commissionAc');
            $payroll->discount = $request->get('discountsAc');
            $payroll->salary = $request->get('salaryAc');
            $payroll->total = $request->get('totalAc');
            $payroll->save();
        }

        // Mecanico
        if ($request->has('totalWeekMec')) {
            $payroll =  Payroll::firstOrCreate(['week' => $request->get('week'), 'user_id' => $request->get('userID'), 'workshop_id' => $request->get('workshop'), 'type' => '2']);
            $payroll->total_week = $request->get('totalWeekMec');
            $payroll->comission = $request->get('commissionMec');
            $payroll->discount = $request->get('discountsMec');
            $payroll->salary = $request->get('salaryMec');
            $payroll->total = $request->get('totalMec');
            $payroll->save();
        }

        // Electrico
        if ($request->has('totalWeekEle')) {
            $payroll =  Payroll::firstOrCreate(['week' => $request->get('week'), 'user_id' => $request->get('userID'), 'workshop_id' => $request->get('workshop'), 'type' => '3']);
            $payroll->total_week = $request->get('totalWeekEle');
            $payroll->comission = $request->get('commissionEle');
            $payroll->discount = $request->get('discountsEle');
            $payroll->salary = $request->get('salaryEle');
            $payroll->total = $request->get('totalEle');
            $payroll->save();
        }
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
