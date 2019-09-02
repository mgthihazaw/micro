<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemType\ItemTypeStoreRequest;
use App\Http\Requests\ItemType\ItemTypeUpdateRequest;
use App\Http\Resources\ItemType\ItemTypeResource;
use App\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->page) {
            $item_types = ItemType::orderBy('id', 'DESC')->paginate(10);

        } else {
            $item_types = ItemType::orderBy('id', 'DESC')->get();

        }

        return ItemTypeResource::collection($item_types);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemTypeStoreRequest $request)
    {
        $item_type = ItemType::create($request->all());

        return response()->json([
            'data' => new ItemTypeResource($item_type),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ItemType $itemtype)
    {
        return response()->json(['data' => new ItemTypeResource($itemtype)], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemTypeUpdateRequest $request, ItemType $itemtype)
    {
        $itemtype->update($request->only(['name']));

        return response()->json(['data' => new ItemTypeResource($itemtype), 'message' => 'Item Type Updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemType $itemtype)
    {
        $itemtype->delete();

        return response()->json([], 204);
    }

}
