<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStockRequest;
use App\Http\Requests\ItemRequest;
use App\Models\RequestedItem;
use App\Models\StockRequest;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class StockRequestController extends Controller
{
    public function dashboard()
    {
        return view('stockrequest.dashboard');
    }
    public function index()
    {
        return view('stockrequest.index');
    }
    public function create()
    {
        return view('stockrequest.create');
    }
    public function store(CreateStockRequest $request)
    {
        try {
            $createdRequest = StockRequest::create([
                'date_filed' => $request->date_filed,
                'time_filed' => $request->time_filed,
                'date_needed' => $request->date_needed,
                'dept' => $request->dept,
                'cost_code' => $request->cost_code,
                'remarks' => $request->remarks,
                'requested_by' => $request->requested_by,
                'created_by' => auth()->user()->username,
                'status' => 'Pending',
                'isSaved' => 1

            ]);
            $trans_no =  $request->date_filed . '-' . str_pad($createdRequest->id, 6, '0', STR_PAD_LEFT);
            $createdRequest->update(['transaction_no' => $trans_no]);
            RequestedItem::where('requested_by', $request->requested_by)->update(['transaction_no' => $trans_no]);
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function getRequests()
    {
        $requests = StockRequest::where([['isSaved', 1], ['active', 1], ['created_by', auth()->user()->username]])->orderBy('id','desc')->get();
        return $requests;
    }
    public function edit($id)
    {
        $request = StockRequest::where('id', $id)->first();
        return view('stockrequest.edit', compact('request'));
    }
    public function update(CreateStockRequest $request)
    {
        try {
            $stockRequest = StockRequest::find($request->id);
            $stockRequest->update([
                'date_needed' => $request->date_needed,
                'remarks' => $request->remarks,
                'cost_code' => $request->cost_code,
                'updated_by' => auth()->user()->username,
                'status' => 'Pending',
                'isSaved' => 1

            ]);
            RequestedItem::where('requested_by', $request->requested_by)->update(['transaction_no' => $request->transaction_no]);
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function view($id)
    {
        $request = StockRequest::where('id', $id)->first();
        return view('stockrequest.view', compact('request'));
    }
    public function delete(Request $request)
    {
        $request->validate(['id' => 'required']);
        try {
            $stockRequest = StockRequest::find($request->id);
            $stockRequest->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => auth()->user()->username,
                'active' => false
            ]);
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function autosave(Request $request)
    {
        try {
            $stockRequest = StockRequest::find($request->id);
            if ($stockRequest) {
                $stockRequest->update([
                    'date_needed' => $request->date_needed,
                    'remarks' => $request->remarks,
                    'cost_code' => $request->cost_code,
                    'status' => 'Pending'

                ]);
                RequestedItem::where('requested_by', $request->requested_by)->update(['transaction_no' => $request->transaction_no]);
                return response()->json(['id' => $stockRequest->id,'transaction_no' => $request->transaction_no]);

            } else {
                $createdRequest = StockRequest::create([
                    'date_filed' => $request->date_filed,
                    'time_filed' => $request->time_filed,
                    'date_needed' => $request->date_needed,
                    'dept' => $request->dept,
                    'cost_code' => $request->cost_code,
                    'remarks' => $request->remarks,
                    'requested_by' => $request->requested_by,
                    'created_by' => auth()->user()->username,
                    'status' => 'Pending'

                ]);

                $trans_no =  $request->date_filed . '-' . str_pad($createdRequest->id, 6, '0', STR_PAD_LEFT);
                $createdRequest->update(['transaction_no' => $trans_no]);
                RequestedItem::where('requested_by', $request->requested_by)->update(['transaction_no' => $trans_no]);
            }
            return response()->json(['id' => $createdRequest->id,'transaction_no' => $trans_no]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function getRequestsUnsaved()
    {
        $requests = StockRequest::where([['isSaved', 0], ['active', 1], ['created_by', auth()->user()->username]])->orderBy('id','desc')->get();
        return $requests;
    }
    public function unsavedDashboard()
    {
        return view('stockrequest.unsaved');
    }
}
