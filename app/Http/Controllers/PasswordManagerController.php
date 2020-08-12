<?php

namespace App\Http\Controllers;

use App\PasswordManager;
use Illuminate\Http\Request;

class PasswordManagerController extends Controller
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


    public function getPassword(Request $request)
    {
        $pass = PasswordManager::where(['name' => $request->get('name'), 'workshop_id' => $request->get('workshop'), 'password' => $request->get('password')])->get();
        return count($pass);
    }

    public function savePassword(Request $request)
    {
        $p = PasswordManager::firstOrCreate(['name' => $request->get('name'), 'workshop_id' => $request->get('workshop')]);
        $p->password = $request->get('password');
        $p->save();
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
     * @param  \App\PasswordManager  $passwordManager
     * @return \Illuminate\Http\Response
     */
    public function show(PasswordManager $passwordManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PasswordManager  $passwordManager
     * @return \Illuminate\Http\Response
     */
    public function edit(PasswordManager $passwordManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PasswordManager  $passwordManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PasswordManager $passwordManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PasswordManager  $passwordManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(PasswordManager $passwordManager)
    {
        //
    }
}
