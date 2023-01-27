<?php

namespace App\Http\Controllers;

use App\Models\IssuedItem;
use Exception;
use Illuminate\Http\Request;

class IssuedItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'issuance_qty' => 'required',
            ]
        );  
        try {
            $balance = $request->balance - $request->issuance_qty;
            IssuedItem::create(
                [
                    'item_id' => $request->item_id,
                    'item_code' => $request->item_code,
                    'issuance_qty' => $request->issuance_qty,
                    'balance' =>  $balance,
                    'received_by' => $request->received_by,
                    'issued_by' => auth()->user()->username,
                ]
            );
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function getIssuanceHistory(Request $request)
    {
        $issuances = IssuedItem::where('item_id', $request->id)->get();
        return $issuances;
    }
}
