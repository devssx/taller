<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return User::role('Limpieza')->get();
        return User::where('id', auth()->id())->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $client = new Client();
        // $client->user_id = auth()->id();
        // $client->name = $request->name;
        // $client->rfc = $request->rfc;
        // $client->email = $request->email;
        // $client->address = $request->address;
        // $client->phone = $request->phone;
        // $client->phone2 = $request->phone2;
        // $client->save();
        
        // return $client;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $client = Client::find($id);
        // $client->name = $request->name;
        // $client->rfc = $request->rfc;
        // $client->email = $request->email;
        // $client->address = $request->address;
        // $client->phone = $request->phone;
        // $client->phone2 = $request->phone2;
        // $client->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $client = Client::find($id);
        // $client->delete();
    }
}
