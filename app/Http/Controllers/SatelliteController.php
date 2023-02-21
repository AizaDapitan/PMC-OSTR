<?php

namespace App\Http\Controllers;

use App\Models\Satellite;
use Illuminate\Http\Request;
use App\Services\AccessRightService;
use Exception;

class SatelliteController extends Controller
{
    protected $accessRightService;

    public function __construct(
        AccessRightService $accessRightService
    ) {
        $this->accessRightService = $accessRightService;
    }
    public function index()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Satellites");

        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $satellites = Satellite::where('name','<>','MCD')->orderBy('name', 'asc')->get();
        $satellites = json_encode($satellites);
        return view('satellite.index', compact('satellites'));
    }
    public function getSatellites()
    {
        $satellites = Satellite::where([['active', 1],['name','<>','MCD']])->OrderBy('name')->get();

        return $satellites;
    }
    public function create()
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Satellites");

        if (!$rolesPermissions['create']) {
            abort(401);
        }
        return view('satellite.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',

        ]);

        $created_at = \Carbon\Carbon::now();
        try {
           Satellite::create([
                'name' => strtoupper($request->name),
                'description' => $request->description,
                'active'    => $request->active,
                'app'    => 'OSTR',
            ]);
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['error' =>  $e->getMessage()], 500);
        }
    }
    public function edit($id)
    {
        $rolesPermissions = $this->accessRightService->hasPermissions("Satellites");

        if (!$rolesPermissions['edit']) {
            abort(401);
        }
        $satellite = Satellite::where('id', $id)->first();
        return view('satellite.edit', compact('satellite'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $updated_at = \Carbon\Carbon::now();
        try {
            $role = Satellite::find($request->id);

            $data = [
                'name' => strtoupper($request->name),
                'description' => $request->description,
                'active'    => $request->active,
                'updated_at' => $updated_at
            ];

            $role->update($data);
            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['error' =>  $e->getMessage()], 500);
        }
    }
}
