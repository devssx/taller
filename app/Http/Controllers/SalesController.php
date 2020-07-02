<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use App\Models\CarServiceItem;
use App\Models\Sale;
use App\Models\SaleService;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use App\Comment;

class SalesController extends Controller
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
        return view('sales.list');
    }

    public function create()
    {
        return view('sales.create');
    }

    public function receipt($id = 0)
    {
        if (!empty($id)) {
            $sale = Sale::with('saleServices')->with('saleServices.item')->with('client')->with('user')
                ->with(['car' => function ($query) {
                    $query->distinct('id');
                }])->with(['services' => function ($query) {
                    $query->distinct('id');
                }])->find($id);

            return view('sales.receipt', [
                'sale' => $sale,
            ]);
        }

        return view('sales.receipt');
    }

    public function get()
    {
        return Sale::with('saleServices')->with('client')->with('user')->with(['car' => function ($query) {
            $query->distinct('id');
        }])->with(['services' => function ($query) {
            $query->distinct('id');
        }])->where('done_on', NULL)->orWhere('done_on', '>', date('Y-m-d 00:00:00', strtotime('-7 days')))->paginate(10);
    }

    public function searchByDay(Request $request)
    {
        if ($request->has('start') && $request->has('end')) {
            return Sale::with('saleServices')->with('client')->with('user')->with(['car' => function ($query) {
                $query->distinct('id');
            }])->with(['services' => function ($query) {
                $query->distinct('id');
            }])
                ->where('created_at', '>=', $request->get('start'))
                ->where('created_at', '<=', $request->get('end'))
                ->paginate(10000);
        }

        return [];
    }

    public function searchByWeek(Request $request)
    {
        if ($request->has('start')) {
            $end = new DateTime($request->get("start"));
            $interval = new DateInterval('P6D'); // + 6 days
            $end->add($interval);

            return Sale::with('saleServices')->with('client')->with('user')->with(['car' => function ($query) {
                $query->distinct('id');
            }])->with(['services' => function ($query) {
                $query->distinct('id');
            }])
                ->where('created_at', '>=', $request->get('start'))
                ->where('created_at', '<=', $end->format("Y-m-d H:i:s"))
                ->paginate(10000);
        }

        return [];
    }

    public function searchReceiptByWeek(Request $request)
    {
        if ($request->has('start')) {

            $start = new DateTime($request->get('start')); // Lunes - 2 = Sabado
            $start->sub(new DateInterval('P2D'));

            $end = new DateTime($request->get('start'));
            $end->add(new DateInterval('P6D'));

            $result = Sale::with('saleServices')->with('client')->with('user')->with(['car' => function ($query) {
                $query->distinct('id');
            }])->with(['services' => function ($query) {
                $query->distinct('id');
            }])
                ->where('done_on', '>=', $start->format("Y-m-d H:i:s"))
                ->where('done_on', '<=', $end->format("Y-m-d H:i:s"))
                ->where('status', '=', Sale::TERMINADO)
                ->paginate(10000);

            $week = new DateTime($request->get('start'));
            $comment = Comment::firstOrCreate(['week' => $week->format("Ymd")]);
            return ['d' => $result, 'c' => $comment];
        }

        return [];
    }

    public function changeStatus(Request $request)
    {
        $sale = Sale::find($request->get('id'));

        $sale->status = $request->get('status');

        if ($sale->status == Sale::TERMINADO) {
            $sale->done_on = date('Y-m-d H:i:s');
            if ($request->has('concept')) {
                $sale->concept = $request->get('concept');
            }
            if ($request->has('total')) {
                $sale->total = $request->get('total');
            }
            if ($request->has('user')) {
                $sale->user_id = $request->get('user');
            }
            if ($request->has('phonenumber')) {
                $sale->phonenumber = $request->get('phonenumber');
            }
            if ($request->has('maker')) {
                $sale->maker = $request->get('maker');
            }
            if ($request->has('brand')) {
                $sale->brand = $request->get('brand');
            }
            if ($request->has('year')) {
                $sale->year = $request->get('year');
            }
            if ($request->has('color')) {
                $sale->color = $request->get('color');
            }
            if ($request->has('last_service')) {
                $sale->last_service = $request->get('last_service');
            }
            if ($request->has('next_service')) {
                $sale->next_service = $request->get('next_service');
            }
            if ($request->has('km')) {
                $sale->km = $request->get('km');
            }
            if ($request->has('details')) {
                $sale->details = $request->get('details');
            }
            if ($request->has('guaranty')) {
                $sale->guaranty = $request->get('guaranty');
            }
            if ($request->has('tax')) {
                $sale->tax = $request->get('tax');
            }
            if ($request->has('service_type')) {
                $sale->service_type = $request->get('service_type');
            }

            $sale->method = $request->get('method');
        }

        $sale->save();

        $sale = Sale::with('saleServices')->with('saleServices.item')->with('client')->with('user')
            ->with(['car' => function ($query) {
                $query->distinct('id');
            }])->with(['services' => function ($query) {
                $query->distinct('id');
            }])->find($sale->id);

        return response()->json([
            "success" => true,
            "sale" => $sale
        ]);
    }

    public function save(Request $request)
    {
        $year = $request->get('year');
        $price = $request->get('price');

        $car = Car::where('brand', '=', $request->get('brand'))
            ->where('start_year', '<=', $year)
            ->where('end_year', '>=', $year)
            ->first();

        if (!$car) {
            $car = Car::where('brand', '=', $request->get('brand'))
                ->where('start_year', '<=', $year + 1)
                ->where('end_year', '>=', $year + 1)
                ->first();
            if (!$car) {
                $car = Car::where('brand', '=', $request->get('brand'))
                    ->where('start_year', '<=', $year - 1)
                    ->where('end_year', '>=', $year - 1)
                    ->first();
                if (!$car) {
                    $car = Car::create([
                        'brand' => $request->get('brand'),
                        'start_year' => $year,
                        'end_year' => $year
                    ]);
                } else {
                    $car->end_year++;
                }
            } else {
                $car->start_year--;
            }
            $car->save();
        }

        $sale = new Sale();

        $sale->total = $request->get('total');
        if ($request->has('service') && $sale->total == 0) {
            $sale->total = CarServiceItem::where('car_id', $car->id)->where('service_id', $request->get('service'))->sum('price');
        }
        $sale->user_id = $request->get('user');
        $sale->client_id = 0;
        if ($request->get('client')) {
            $sale->client_id = $request->get('client');
        }
        if ($request->has('phonenumber')) {
            $sale->phonenumber = $request->get('phonenumber');
        }

        // Create a new client if id is not valid
        if ($sale->client_id == -1) {
            $clientName =  $request->has('clientname') ? $request->get('clientname') : "Cliente S/N";
            $phoneNumber = $request->has('phonenumber') ? $request->get('phonenumber') : "";
            $newClient = Client::firstOrCreate(['name' => $clientName, 'phonenumber' => $phoneNumber]);
            $sale->client_id = $newClient->id;
        }

        if ($request->has('concept')) {
            $sale->concept = $request->get('concept');
        }
        if ($request->has('color')) {
            $sale->color = $request->get('color');
        }
        if ($request->has('last_service')) {
            $sale->last_service = $request->get('last_service');
        }
        if ($request->has('next_service')) {
            $sale->next_service = $request->get('next_service');
        }
        if ($request->has('km')) {
            $sale->km = $request->get('km');
        }

        // Create Receipt
        if ($request->has('maker')) {
            $sale->maker = $request->get('maker');
        }
        if ($request->has('brand')) {
            $sale->brand = $request->get('brand');
        }
        if ($request->has('year')) {
            $sale->year = $request->get('year');
        }
        if ($request->has('status')) {
            $sale->status = $request->get('status');
            if ($sale->status == Sale::TERMINADO)
                $sale->done_on = date('Y-m-d H:i:s');
        }
        if ($request->has('details')) {
            $sale->details = $request->get('details');
        }
        if ($request->has('guaranty')) {
            $sale->guaranty = $request->get('guaranty');
        }
        if ($request->has('tax')) {
            $sale->tax = $request->get('tax');
        }
        if ($request->has('method')) {
            $sale->method = $request->get('method');
        }
        if ($request->has('service_type')) {
            $sale->service_type = $request->get('service_type');
        }

        $sale->save();

        if ($request->has('service')) {
            $items = CarServiceItem::where('car_id', $car->id)->where('service_id', $request->get('service'))->get();

            if ($items->isNotEmpty()) {
                $items->each(function ($item, $key) use ($sale, $year) {
                    SaleService::create([
                        'sale_id' => $sale->id,
                        'car_id' => $item->car_id,
                        'service_id' => $item->service_id,
                        'item_id' => $item->item_id,
                        'year' => $year,
                        'price' => $item->price,
                    ]);
                });
            } else {
                SaleService::create([
                    'sale_id' => $sale->id,
                    'car_id' => $car->id,
                    'service_id' => $request->get('service'),
                    'item_id' => 1,
                    'year' => $year,
                    'price' => 0,
                ]);
            }
        } else if ($request->has('services')) {
            foreach ($request->get('services') as $service) {

                // mixed price
                $price = $request->has('price') ? $request->get('price') : $service['selectedPrice'];

                foreach ($service['items'] as $item) {
                    if ($request->has('price')) {
                        SaleService::create([
                            'sale_id' => $sale->id,
                            'car_id' => $car->id,
                            'service_id' => $service['id'],
                            'item_id' => $item['id'],
                            'year' => $year,
                            'price' => $item[$price . '_price'],
                        ]);
                    } else {
                        // request comes from car services
                        SaleService::create([
                            'sale_id' => $sale->id,
                            'car_id' => $car->id,
                            'service_id' => $service['service_id'],
                            'item_id' => $item['item_id'],
                            'year' => $year,
                            'price' => $item[$price . '_price'],
                        ]);
                    }
                }
            }
        }

        return $sale;
    }
}
