<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCarServiceRequest;
use App\Models\Service;
use App\Models\CarService;
use App\Models\CarServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CarServicesController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('carservices.index');
    }

    public function create(Request $request)
    {
        return view('carservices.create');
    }

    public function edit($id)
    {
        $carService = CarService::find($id);

        $items = CarServiceItem::where([
            'car_id' => $carService->car_id,
            'service_id' => $carService->service_id,
        ])->with('item')->get();

        return view('carservices.edit', ['carService' => $carService, 'items' => $items]);
    }

    public function get(Request $request)
    {
        if ($request->has('id')) {
            $CarID = $request->get('id');
            //$carServices = CarService::where(['car_id' => $CarID])->get();

            $carServices = DB::table('car_services')
                ->join('services', 'services.id', 'car_services.service_id')
                ->select('car_services.*', 'services.name')
                ->where(['car_services.car_id' => $CarID])
                ->get();

            foreach ($carServices as $service) {
                // Nombre del servicio
                // $service->service = Service::select('id', 'name','description')->where('id', $service->service_id)->first();

                // le busca los items al servicio
                $service->items = CarServiceItem::where([
                    'car_id' => $service->car_id,
                    'service_id' => $service->service_id,
                ])->with('item')->get();
            }

            return $carServices;
        }

        if ($request->has('all')) {
            if ($request->has('sort')) {
                if ($request->get('order') == "ascending") {
                    return CarService::with('car')->with('service')->get()->sortBy($request->get('sort'))->values();
                }
                if ($request->get('order') == "descending") {
                    return CarService::with('car')->with('service')->get()->sortByDesc($request->get('sort'))->values();
                }
            }
            return CarService::with('car')->with('service')->get();
        }

        return CarService::with('car')->with('service')->paginate(10);
    }

    public function save(SaveCarServiceRequest $request)
    {
        $car = $request->get('car');
        $service = $request->get('service');

        $comment = $request->has('comment') ? $request->get('comment') : '';
        $warranty = $request->has('warranty') ? $request->get('warranty') : '';
        $exchange_rate = $request->has('exchange_rate') ? $request->get('exchange_rate') : 0;
        $price = $request->has('price') ? $request->get('price') : 0;
        $low = $request->has('low') ? $request->get('low') : 0;
        $mid = $request->has('mid') ? $request->get('mid') : 0;
        $high = $request->has('high') ? $request->get('high') : 0;


        if ($request->has('csid')) {
            $carService = CarService::find($request->get('csid'));
            if ($carService) {
                $carService->comment = $comment;
                $carService->warranty = $warranty;
                $carService->exchange_rate = $exchange_rate;
                $carService->low = $low;
                $carService->mid = $mid;
                $carService->high = $high;
                $carService->save();
            } else {
                CarService::firstOrCreate([
                    'car_id' => $car,
                    'service_id' => $service,
                    'comment' => $comment,
                    'warranty' => $warranty,
                    'exchange_rate' => $exchange_rate,
                    'price' => $price,
                    'low' => $low,
                    'mid' => $mid,
                    'high' => $high,
                ]);
            }
        } else {
            CarService::firstOrCreate([
                'car_id' => $car,
                'service_id' => $service,
                'comment' => $comment,
                'warranty' => $warranty,
                'exchange_rate' => $exchange_rate,
                'price' => $price,
                'low' => $low,
                'mid' => $mid,
                'high' => $high,
            ]);
        }

        $items = collect($request->get('items'));

        $ids = CarServiceItem::select('id')->where([
            'car_id' => $car,
            'service_id' => $service,
        ])->get();

        $ids->each(function ($id, $key) use ($items) {
            if (!$items->contains('id', $id->id)) {
                CarServiceItem::find($id->id)->delete();
            }
        });

        $items->each(function ($item, $key) use ($car, $service) {
            $item['car_id'] = $car;
            $item['service_id'] = $service;
            $item['item_id'] = $item['id'];

            if (isset($item['name'])) {
                unset($item['id']);
                unset($item['name']);
                unset($item['description']);
            }

            if (isset($item['item'])) {
                $item['item_id'] = $item['item']['id'];
                unset($item['item']);
            }

            unset($item['deleted_at']);

            if (isset($item['id'])) {
                CarServiceItem::find($item['id'])->update($item);
            } else {
                CarServiceItem::firstOrCreate($item);
            }
        });

        return response()->json([
            'success' => true,
        ]);
    }

    public function delete($id)
    {
        $carService = CarService::find($id);

        if (!$carService) {
            return response()->json(['errors' => ['missing' => ['Servicio no existe']]], 422);
        }

        CarServiceItem::where([
            'car_id' => $carService->car_id,
            'service_id' => $carService->service_id,
        ])->delete();

        return response()->json([
            'success' => $carService->delete(),
        ]);
    }
}
