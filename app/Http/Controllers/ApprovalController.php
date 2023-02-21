<?php

namespace App\Http\Controllers;

use App\Models\RequestedItem;
use App\Models\StockRequest;
use Illuminate\Http\Request;
use App\Services\AccessRightService;
use Carbon\Carbon;
use Exception;

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
    public function getRequests(Request $request)
    {
        $currentMonth = Carbon::now()->month;

        $firstDay = Carbon::createFromDate(null, $currentMonth, 1);
        $lastDay = Carbon::createFromDate(null, $currentMonth, $firstDay->daysInMonth);

        $dateFrom = $firstDay->toDateString();
        $dateTo = $lastDay->toDateString();

        if (isset($request->dateFrom)) {
            $dateFrom = $request->dateFrom;
        }
        if (isset($request->dateTo)) {
            $dateTo = $request->dateTo;
        }
        $requests = StockRequest::where([['isSaved', 1], ['active', 1],['status','Submitted'], ['dept', auth()->user()->dept]])
        ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
        ->orderBy('id', 'desc')->get();
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
    public function cancel(Request $request)
    {
        $request->validate(['id' => 'required']);
        try {
            $item = StockRequest::find($request->id)->update([
                'updated_by' => auth()->user()->username,
                'status' => 'Cancelled',
            ]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
}
