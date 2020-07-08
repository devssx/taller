<?php

namespace App\Http\Controllers;

use App\Models\Cleaning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use DateTime;
use DateInterval;

class CleaningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cars.index');
    }

    public function get(Request $request)
    {
        return DB::table('cleanings')
            ->join('users', 'users.id', 'cleanings.user_id')
            ->select('cleanings.*', 'users.name')
            ->orderBy('cleanings.start', 'asc')
            ->get();
    }

    public function search(Request $request)
    {
        // busca por id
        if ($request->has('id')) {
            return DB::table('cleanings')
                ->join('users', 'users.id', 'cleanings.user_id')
                ->select('cleanings.*', 'users.name')
                ->where('cleanings.id', '=', $request->get('id'))
                ->get();
        }

        // Join User Name
        // SELECT * FROM `cleanings` WHERE `start` > '2020-05-15' and `start` < '2020-05-16'
        //if ($request->has('start') && $request->has('end')) {
        if ($request->has('day')) {

            // if ($request->has('role')) {
            //     return User::role($request->get('role'))->get();
            // }

            // crear registros si no existen:
            //$users = User::with('roles')->all();
            $users = User::with('roles')->get();
            //$users = User::role('Limpieza')->get();
            foreach ($users as $u) {
                if ($request->has('format')) {
                    $dateFormat = $request->get('format');
                    Cleaning::firstOrCreate(['user_id' => $u->id, 'day' => $request->get('day')], [
                        'start' => $dateFormat,
                        'breakfast_start' => $dateFormat,
                        'breakfast_end' => $dateFormat,
                        'lunch_start' => $dateFormat,
                        'lunch_end' => $dateFormat
                    ]);
                } else {
                    Cleaning::firstOrCreate(['user_id' => $u->id, 'day' => $request->get('day')]);
                }
            }

            $data = DB::table('cleanings')
                ->join('users', 'users.id', 'cleanings.user_id')
                ->select('cleanings.*', 'users.name')
                ->where('cleanings.day', '=', $request->get('day'))
                ->orderBy('users.name', 'asc')
                ->get();

            return $data;
        }

        return $this->get($request);
    }

    public function searchWeek(Request $request)
    {
        // Join User Name
        // SELECT * FROM `cleanings` WHERE `start` > '2020-05-15' and `start` < '2020-05-16'
        if ($request->has('start')) {

            // week range
            /*$start = intval($request->get('start'));
            $end = $start + 6;

            $data = DB::table('cleanings')
                ->join('users', 'users.id', 'cleanings.user_id')
                ->select('cleanings.*', 'users.name')
                ->where('cleanings.day', '>=', $start)
                ->where('cleanings.day', '<=', $end)
                ->orderBy('cleanings.day', 'asc')
                ->get();*/

            $end = new DateTime($request->get("start"));
            $interval = new DateInterval('P6D'); // + 6 days
            $end->add($interval);

             $data = DB::table('cleanings')
                 ->join('users', 'users.id', 'cleanings.user_id')
                 ->select('cleanings.*', 'users.name')
                 ->where('cleanings.start', '>=', $request->get('start'))
                 ->where('cleanings.start', '<=', $end->format("Y-m-d H:i:s"))
                 ->orderBy('cleanings.day', 'asc')
                 ->get();

            return $data;
        }

        // all
        return $this->get($request);
    }

    public function save(Request $request)
    {
        // Update
        if ($request->has('id')) {
            return Cleaning::updateOrCreate($request->only('id'), $request->except(['id', 'created_at', 'deleted_at', 'updated_at']));
        }

        return Cleaning::firstOrCreate([
            'user_id' => $request->get('user_id'),
            'start' => $request->get('start'),
            'cleaning' => $request->get('cleaning'),
            'breakfast_start' => $request->get('breakfast_start'),
            'breakfast_end' => $request->get('breakfast_end'),
            'lunch_start' => $request->get('lunch_start'),
            'lunch_end' => $request->get('lunch_end'),
            'done' => $request->get('done'),
            'comment' => $request->get('comment'),
        ]);
    }

    public function delete($id)
    {
        return response()->json([
            'success' => Cleaning::findOrFail($id)->delete(),
        ]);
    }
}
