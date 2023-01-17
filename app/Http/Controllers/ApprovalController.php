<?php

namespace App\Http\Controllers;

use App\Models\RequestedItem;
use App\Models\StockRequest;
use Illuminate\Http\Request;
use App\Services\AccessRightService;

class ApprovalController extends Controller
{
    protected $accessRightService;
    public function __construct(
        AccessRightService $accessRightService
    ) {
        $this->accessRightService = $accessRightService;
    }
    public function index()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Manage Stock Request");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        return view('approval.index');        
    }
    public function getRequests()
    {
        $requests = StockRequest::where([['isSaved', 1], ['active', 1], ['dept', auth()->user()->dept]])->orderBy('id', 'desc')->get();
        return $requests;
    }
    public function view($id)
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Approval Stock Request");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $request = StockRequest::where('id', $id)->first();
        return view('approval.view', compact('request'));
    }
    public function checkDetails($id)
    {
        $request = StockRequest::where('id', $id)->first();
        return view('approval.checkdetails', compact('request'));
    }
    public function getRequestedItemsSaved(Request $request)
    {
        $requested_items = RequestedItem::where('transaction_no', $request->transaction_no)->whereNull('deleted_at')->orderBy('id','desc')->get();
        return $requested_items;
    }
}
