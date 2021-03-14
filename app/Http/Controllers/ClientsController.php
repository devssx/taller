<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveClientRequest;
use App\Models\Client;
use App\WorkShop;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function get(Request $request)
    {
        if ($request->has('all')) {
            if ($request->has('workshop'))
                return Client::where('workshop_id', $request->get('workshop'))->get();
            else
                return Client::all();
        }

        if ($request->has('reminders')) {
            return $this->getReminders();
        }

        if ($request->filled('search')) {
            return Client::where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('phonenumber', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('address', 'LIKE', '%' . $request->get('search') . '%')
                ->paginate(10)
                ->setPath('')
                ->appends(array(
                    'search' => $request->get('search'),
                ));
        }

        return Client::paginate(10);
    }

    public function getReminders()
    {
        $today = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $tomorrow = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        return Client::where('reminder', '!=', '')
            ->where('reminder_date', '>=', $today)
            ->where('reminder_date', '<=', $tomorrow)
            ->get();
    }

    public function index()
    {
        $workshops = WorkShop::get();
        return view('clients.index', [
            'workshops' => $workshops
        ]);
    }

    public function save(SaveClientRequest $request)
    {
        if ($request->has('id')) {
            return Client::updateOrCreate($request->only('id'), $request->except('id'));
        }

        return Client::firstOrCreate($request->all());
    }

    public function delete($id)
    {
        return response()->json([
            'success' => Client::findOrFail($id)->delete(),
        ]);
    }
}
