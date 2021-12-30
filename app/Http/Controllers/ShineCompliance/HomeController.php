<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\ZoneService;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\HomeService;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    private $propertyService;
    private $homeService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        HomeService $homeService,
        PropertyService $propertyService,
        ClientService $clientService
    )
    {
        $this->propertyService = $propertyService;
        $this->clientService = $clientService;
        $this->homeService = $homeService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function decommissionDropdown(Request $request) {
        $type = $request->has('type') ? $request->type : '';
        if($type){
            $data = $this->homeService->getDecomissionReason($type);
        }
        return response()->json(['status_code' => 200, 'data' => $data]);
    }

    public function recommissionDropdown(Request $request) {
        $type = $request->has('type') ? $request->type : '';
        if($type){
            $data = $this->homeService->getRecomissionReason($type);
        }
        return response()->json(['status_code' => 200, 'data' => $data]);
    }
}
