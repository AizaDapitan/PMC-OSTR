<?php

namespace App\Http\Controllers;

use App\Models\IssuedItem;
use Exception;
use Illuminate\Http\Request;

class IssuedItemController extends Controller
{
    public function store(Request $request)
    {
        try {
            foreach ($request->items as $item)
                if ($item['issuance_qty'] != null || $item['issuance_qty'] != 0) {
                    $pre_balance = $item['requested_qty'] - $item['issued_qty'];
                    $post_balance = $pre_balance- $item['issuance_qty'];
                    IssuedItem::create(
                        [
                            'item_id' => $item['id'],
                            'item_code' => $item['stock_code'],
                            'issuance_qty' => $item['issuance_qty'],
                            'balance' => $post_balance,
                            'received_by' => $request->requested_by,
                            'issued_by' => auth()->user()->username,
                        ]
                    );
                }

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
