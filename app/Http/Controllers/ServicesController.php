<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetServicesRequest;
use App\Http\Requests\SaveServiceRequest;
use App\Models\Car;
use App\Models\CarServiceItem;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
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
        return view('services.index');
    }

    public function get(Request $request)
    {
        if ($request->has('all')) {
            return Service::all();
        }

        if ($request->filled('search')) {
            return Service::where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->paginate(10)
                ->setPath('')
                ->appends(array(
                    'search' => $request->get('search'),
                ));
        }

        return Service::paginate(10);
    }

    public function save(SaveServiceRequest $request)
    {
        if ($request->has('id')) {
            return Service::updateOrCreate($request->only('id'), $request->except('id'));
        }

        return Service::firstOrCreate($request->all());
    }

    public function getByCar(GetServicesRequest $request)
    {
        $car = Car::has('carServices')
            ->where('brand', '=', $request->get('brand'))
            ->where('start_year', '<=', $request->get('year'))
            ->where('end_year', '>=', $request->get('year'))
            ->first();

        if (!$car) {
            return [];
        }

        $carServiceItems = CarServiceItem::where('car_id', '=', $car->id)
            ->with('service')->with('item')->get();

        $services = collect();

        $carServiceItems->map(function ($carServiceItem) use ($services) {
            $service = collect($carServiceItem->service);
            if ($services->get($carServiceItem->service_id)) {
                $service = $services->get($carServiceItem->service_id);
            }

            $items = collect();
            if ($service->has('items')) {
                $items = $service->get('items');
            }
            $item = $carServiceItem->item->toArray();

            $item['price'] = floatval($carServiceItem->price);
            $item['low'] = $carServiceItem->low;
            $item['low_price'] = floatval($carServiceItem->low_price);
            $item['mid'] = $carServiceItem->mid;
            $item['mid_price'] = floatval($carServiceItem->mid_price);
            $item['high'] = $carServiceItem->high;
            $item['high_price'] = floatval($carServiceItem->high_price);

            $items->put($carServiceItem->item_id, $item);

            $service->put('items', $items);

            $services->put($carServiceItem->service_id, $service);
        });

        return $services->values()->toArray();
    }

    public function getServicesOfCar(GetServicesRequest $request)
    {
        $car = Car::has('carServices')
            ->where('maker', '=', $request->get('maker'))
            ->where('brand', '=', $request->get('brand'))
            ->where('motor', '=', $request->get('motor'))
            ->where('start_year', '>=', $request->get('year'))
            //->where('end_year', '<=', $request->get('year'))
            ->first();

        if (!$car) {
            return [];
        }

        $carServiceItems = CarServiceItem::where('car_id', '=', $car->id)
            ->with('service')->with('item')->get();

        $services = collect();

        $carServiceItems->map(function ($carServiceItem) use ($services) {
            $service = collect($carServiceItem->service);
            if ($services->get($carServiceItem->service_id)) {
                $service = $services->get($carServiceItem->service_id);
            }

            $items = collect();
            if ($service->has('items')) {
                $items = $service->get('items');
            }
            $item = $carServiceItem->item->toArray();

            $item['price'] = floatval($carServiceItem->price);
            $item['low'] = $carServiceItem->low;
            $item['low_price'] = floatval($carServiceItem->low_price);
            $item['mid'] = $carServiceItem->mid;
            $item['mid_price'] = floatval($carServiceItem->mid_price);
            $item['high'] = $carServiceItem->high;
            $item['high_price'] = floatval($carServiceItem->high_price);

            $items->put($carServiceItem->item_id, $item);

            $service->put('items', $items);

            $services->put($carServiceItem->service_id, $service);
        });

        return $services->values()->toArray();
    }

    public function delete($id)
    {
        return response()->json([
            'success' => Service::findOrFail($id)->delete(),
        ]);
    }
}
