<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceItem\ServiceItemStoreRequest;
use App\Http\Resources\ServiceItem\ServiceItemResource;
use App\Service;
use App\ItemService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Service $service)
    {
        return response()->json(['data' => new ServiceItemResource($service)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceItemStoreRequest $request, Service $service)
    {
        $service->items()->detach();
        foreach ($request->all() as $key => $service_item) {
            ItemService::updateOrCreate([
                'service_id' => $service->id,
                'item_id' => $service_item['item_id'],
                'quantity' => $service_item['quantity'],
                'price' => $service_item['price']
            ]);
        }

        $service->invoice()->updateOrCreate([
            'total_price' => $request->total_price,
            'opened_date' => Carbon::createFromFormat('/d/m/Y', now());
        ]);

        return response()->json($service, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }
}