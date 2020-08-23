<?php

namespace App\Http\Controllers;

use App\Guarantee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\WorkShop;
use App\User;

class GuaranteeController extends Controller
{
    // Ingresos y Gastos
    public function reports()
    {
        $workshops = WorkShop::get();
        $myUser = User::where('id', auth()->id())->get();
        return view('reports.index', [
            'workshops' => $workshops,
            'myUser' =>  $myUser,
            'multiWorkshop' => auth()->user()->can('cambiar de taller')
        ]);
    }

        // Garantias
        public function guarantee()
        {
            $workshops = WorkShop::get();
            $myUser = User::where('id', auth()->id())->get();
            return view('guarantee.index', [
                'workshops' => $workshops,
                'myUser' =>  $myUser,
                'multiWorkshop' => auth()->user()->can('cambiar de taller')
            ]);
        }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return Guarantee::where(['year' => $request->get('year'), 'workshop_id' => $request->get('workshop')])->get();

        return DB::table('guarantees')
            ->join('sales', 'sales.id', 'guarantees.sale_id')
            ->select('guarantees.*', 'sales.total', 'sales.done_on')
            ->where(['guarantees.year' => $request->get('year'), 'guarantees.workshop_id' => $request->get('workshop')])
            ->orderBy('guarantees.new_date', 'asc')
            ->get();
    }


    public function save(Request $request)
    {
        if ($request->has('id')) {
            $c = Guarantee::firstOrCreate(['id' => $request->get('id')]);
            $c->sale_id = $request->get('sale_id');
            $c->comment = $request->get('comment');
            $c->employee = $request->get('employee');
            $c->solution = $request->get('solution');
            $c->new_date = $request->get('new_date');
            $c->save();
        } else {
            $c = new Guarantee();
            $c->year = $request->get('year');
            $c->workshop_id = $request->get('workshop');
            $c->sale_id = $request->get('sale_id');
            $c->comment = $request->get('comment');
            $c->employee = $request->get('employee');
            $c->solution = $request->get('solution');
            $c->new_date = $request->get('new_date');
            $c->save();
        }
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
     * @param  \App\Guarantee  $guarantee
     * @return \Illuminate\Http\Response
     */
    public function show(Guarantee $guarantee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guarantee  $guarantee
     * @return \Illuminate\Http\Response
     */
    public function edit(Guarantee $guarantee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Guarantee  $guarantee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guarantee $guarantee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Guarantee  $guarantee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guarantee $guarantee)
    {
        //
    }
}
