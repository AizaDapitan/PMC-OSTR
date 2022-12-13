<?php

namespace App\Http\Controllers;

use App\Models\RequestedItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class RequestedItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'description' => 'required',
                'requested_qty' => 'required',
            ]
        );
        RequestedItem::create(
            [
                'stock_code' => $request->stock_code,
                'description' => $request->description,
                'uom' => $request->uom,
                'available_qty' => $request->available_qty,
                'requested_qty' => $request->requested_qty,
                'requested_by' => $request->requested_by,
                'created_by' => auth()->user()->username,
            ]
        );
    }
    public function getRequestedItems(Request $request)
    {
        $requested_items = RequestedItem::where('requested_by', $request->requested_by)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $requested_items;
    }
    public function getRequestedItemsSaved(Request $request)
    {
        $requested_items = RequestedItem::where('transaction_no', $request->transaction_no)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $requested_items;
    }
    public function update(Request $request)
    {
        $request->validate(
            [
                'description' => 'required',
                'requested_qty' => 'required',
            ]
        );
        try {
            $item = RequestedItem::find($request->id);

            $item->update(
                [
                    'stock_code' => $request->stock_code,
                    'description' => $request->description,
                    'uom' => $request->uom,
                    'available_qty' => $request->available_qty,
                    'requested_qty' => $request->requested_qty,
                    'requested_by' => $request->requested_by,
                    'updated_by' => auth()->user()->username,
                ]
            );
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function delete(Request $request)
    {
        $request->validate(['id' => 'required']);
        try {
            $item = RequestedItem::find($request->id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => auth()->user()->username
            ]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
}
