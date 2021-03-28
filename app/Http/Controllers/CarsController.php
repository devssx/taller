<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetServicesRequest;
use App\Http\Requests\SaveCarRequest;
use App\Http\Requests\GetBrandsRequest;
use App\Models\Car;
use App\Models\CarService;
use App\Models\CarServiceItem;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {
        return view('cars.index');
    }

    //
    public function get(Request $request)
    {
        if ($request->has('all')) {
            return Car::all();
        }

        if ($request->filled('search')) {
            return Car::where('maker', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('start_year', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('end_year', 'LIKE', '%' . $request->get('search') . '%')
                ->paginate(10)
                ->setPath('')
                ->appends(array(
                    'search' => $request->get('search'),
                ));
        }

        return Car::paginate(10);
    }

    public function save(SaveCarRequest $request)
    {
        if ($request->has('id')) {
            return Car::updateOrCreate($request->only('id'), $request->except(['id', 'year', 'created_at', 'deleted_at', 'updated_at']));
        }

        $car = Car::where('brand', '=', $request->get('brand'))
            ->where('start_year', '=', $request->get('year')[0])
            ->where('end_year', '=', $request->get('year')[1])
            ->where('motor', '=', $request->get('motor')[1])
            ->first();

        if ($car) {
            return response()->json([
                'error' => 'El Carro ya existe',
            ]);
        }

        $newCar = Car::firstOrCreate([
            'maker' => $request->get('maker'),
            'brand' => $request->get('brand'),
            'motor' => $request->get('motor'),
            'image' => $request->get('image'),
            'start_year' => $request->get('year')[0],
            'end_year' => $request->get('year')[1],
        ]);

        // + Default services
        if ($request->has('services')) {
            $services = collect($request->get('services'));
            foreach ($services as $service) {
                $this->addService($service, $newCar->id);
            }

            /*$services->each(function ($item, $key) use ($car, $newCar, $this) {
                
            });*/
        }

        return $newCar;
    }

    public function addService($serviceObj, $car)
    {
        $service = $serviceObj['service'];

        $comment = '';
        $warranty = '';
        $exchange_rate = 0;
        $price = 0;
        $low = 0;
        $mid = 0;
        $high = 0;

        $newService = CarService::firstOrCreate([
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
        

        $items = collect($serviceObj['items']);
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

        return $newService->id;
    }

    public function searchCar(GetServicesRequest $request)
    {
        return Car::select('*')
            ->where('maker', '=', $request->get('maker'))
            ->where('brand', '=', $request->get('brand'))
            ->where('motor', '=', $request->get('motor'))

            ->where('start_year', '<=', $request->get('year'))
            ->where('end_year', '>=', $request->get('year'))
            //->first();

            // ->whereBetween('start_year', '=', $request->get('year'))
            // ->where('start_year', '>=', $request->get('year'))
            // ->where('end_year', '<=', $request->get('year'))
            ->get();
    }

    public function listBrands()
    {
        return Car::select('brand')->groupBy('brand')->orderBy('brand')->get();
    }

    public function listMakers()
    {
        return Car::select('maker')->groupBy('maker')->orderBy('maker')->get();
    }

    public function getBrandsOf(GetBrandsRequest $request)
    {
        return Car::select('brand')->where('maker', '=', $request->maker)->groupBy('brand')->orderBy('brand')->get();
    }

    public function delete($id)
    {
        return response()->json([
            'success' => Car::findOrFail($id)->delete(),
        ]);
    }
}
