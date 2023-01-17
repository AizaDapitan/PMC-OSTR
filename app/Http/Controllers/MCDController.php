<?php

namespace App\Http\Controllers;

use App\Models\StockRequest;
use Illuminate\Http\Request;
use App\Services\AccessRightService;
use Carbon\Carbon;
use Exception;

class MCDController extends Controller
{
    protected $accessRightService;
    public function __construct(
        AccessRightService $accessRightService
    ) {
        $this->accessRightService = $accessRightService;
    }
    public function index()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Manage Receiving");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        return view('mcd.index');
    }
    public function getRequests()
    {
        $requests = StockRequest::where([['isSaved', 1], ['active', 1], ['status','fully approved']])->orderBy('id', 'desc')->get();
        return $requests;
    }
    public function receive(Request $request)
    {
        try {
            $stockRequest = StockRequest::find($request->id);
            $stockRequest->update([
                'received_by' => auth()->user()->username,
                'isReceived' => 1,
                'received_at' => Carbon::now()

            ]);
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function view($id)
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Approval Stock Request");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $request = StockRequest::where('id', $id)->first();
        return view('mcd.view', compact('request'));
    }
    public function edit($id)
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Approval Stock Request");
        if (!$rolesPermissions['edit']) {
            abort(401);
        }
        $request = StockRequest::where('id', $id)->first();
        return view('mcd.edit', compact('request'));
    }
    
}
