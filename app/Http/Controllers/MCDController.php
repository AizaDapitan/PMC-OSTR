<?php

namespace App\Http\Controllers;

use App\Models\IssuedItem;
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
        $requests = StockRequest::where([['isSaved', 1], ['active', 1], ['status', 'fully approved'],['origin' ,'MCD']])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('id', 'desc')->get();
        return $requests;
    }
    public function completed()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Completed Request");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        return view('mcd.completed');
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
