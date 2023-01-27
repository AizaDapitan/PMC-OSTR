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
use App\Services\AccessRightService;

class StockRequestController extends Controller
{
    protected $accessRightService;
    public function __construct(
        AccessRightService $accessRightService
    ) {
        $this->accessRightService = $accessRightService;
    }
    public function dashboard()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Main Dashboard");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        return view('stockrequest.dashboard');
    }
    public function index()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Manage Stock Request");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $delete = $rolesPermissions['delete'];
        // $this->updateRequestApproval();
        return view('stockrequest.index', compact('delete'));
    }
    public function create()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Stock Request");
        if (!$rolesPermissions['create']) {
            abort(401);
        }
        return view('stockrequest.create');
    }
    public function store(CreateStockRequest $request)
    {
        try {
            $stockRequest = StockRequest::find($request->id);
            if ($stockRequest) {
                $stockRequest->update([
                    'date_needed' => $request->date_needed,
                    'remarks' => $request->remarks,
                    'cost_code' => $request->cost_code,
                    'status' => 'Pending',
                    'isSaved' => 1

                ]);
                RequestedItem::where('requested_by', $request->requested_by)->update(['transaction_no' => $request->transaction_no]);
                return response()->json(['id' => $stockRequest->id, 'transaction_no' => $request->transaction_no]);
                // $this->createData($stockRequest);
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
                    'status' => 'Pending',
                    'isSaved' => 1


                ]);

                $trans_no =  $request->date_filed . '-' . str_pad($createdRequest->id, 6, '0', STR_PAD_LEFT);
                $createdRequest->update(['transaction_no' => $trans_no]);
                RequestedItem::where('requested_by', $request->requested_by)->update(['transaction_no' => $trans_no]);
                return response()->json(['id' => $stockRequest->id, 'transaction_no' => $request->transaction_no]);
            }
            // return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function createData($createdRequest)
    {
        $data = array(
            "type" => config('app.name'),
            "transid" => $createdRequest->transaction_no,
            "token" => config('app.key'),
            "refno" => $createdRequest->id,
            "sourceapp" => "Online Stock Transfer Request",
            "sourceurl" => config('app.url') . '/approvals/checkDetails/' . $createdRequest->id,
            "requestor" => auth()->user()->name,
            "department" => auth()->user()->dept,
            "email" => auth()->user()->email,
            "purpose" => $createdRequest->remarks,
            "name" => auth()->user()->name,
            "template_id"  => config('app.template_id'),
            "locsite" => ""
        );
        $this->insertDataintoWfs($data);
    }
    public function insertDataintoWfs($data)
    {
        define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
        $result = require(__ROOT__ . '\api\wfs-api.php');
        if ($result) {
            $stockRequest =  StockRequest::find($data['refno']);
            $stockRequest->update(['WFS_connection' => 1]);
        }
    }
    public function updateRequestApproval()
    {
        $stockRequests = StockRequest::where([['WFS_connection', 1], ['status', 'Submitted'], ['active', 1]])->get();
        $ids = "";
        foreach ($stockRequests as $stockRequest) {
            if ($ids == "") {
                $ids = $stockRequest->id;
            } else {
                $ids = $ids . "," . $stockRequest->id;
            }
        }
        define('__ROOT2__', dirname(dirname(dirname(dirname(__FILE__)))));

        $WFSrequests = require(__ROOT2__ . '\api\approval-status-api.php');
        foreach ($WFSrequests as $WFSrequest) {
            $WFSrequestArr = explode(':', $WFSrequest);
            $ref_req_no = $WFSrequestArr[0];
            $status = $WFSrequestArr[1];
            $request = StockRequest::find($ref_req_no);
            $request->update(['status' => $status]);
        }
    }
    public function insertIntoWFS()
    {
        $stockRequests = StockRequest::where('WFS_connection', 0)->orWhereNull('WFS_connection')->where([['active', 1], ['status', 'Submitted']])->get();
        foreach ($stockRequests as $stockRequest) {
            $this->createData($stockRequest);
        };
    }
    public function getRequests()
    {
        $requests = StockRequest::where([['isSaved', 1], ['active', 1], ['created_by', auth()->user()->username]])->orderBy('id', 'desc')->get();
        return $requests;
    }
    public function edit($id)
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Stock Request");
        if (!$rolesPermissions['edit']) {
            abort(401);
        }
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
                'isSaved' => 1,
                'WFS_connection' => 0

            ]);
            RequestedItem::where('requested_by', $request->requested_by)->update(['transaction_no' => $request->transaction_no]);
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function submit(CreateStockRequest $request)
    {
        try {
            $stockRequest = StockRequest::find($request->id);
            $stockRequest->update([
                'updated_by' => auth()->user()->username,
                'status' => 'Submitted',
                'isSaved' => 1,

            ]);
            $this->createData($stockRequest);
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function view($id)
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Stock Request");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
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
                return response()->json(['id' => $stockRequest->id, 'transaction_no' => $request->transaction_no]);
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
                return response()->json(['id' => $createdRequest->id, 'transaction_no' => $trans_no]);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage(), 500]);
        }
    }
    public function getRequestsUnsaved()
    {
        $requests = StockRequest::where([['isSaved', 0], ['active', 1], ['created_by', auth()->user()->username]])->orderBy('id', 'desc')->get();
        return $requests;
    }
    public function unsavedDashboard()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Unsaved Stock Request");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $delete = $rolesPermissions['delete'];
        return view('stockrequest.unsaved', compact('delete'));
    }
}
