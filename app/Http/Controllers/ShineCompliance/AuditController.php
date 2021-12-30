<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ShineCompliance\AuditTrailService;

class AuditController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $auditTrailService;

    public function __construct(AuditTrailService $auditTrailService)
    {
        $this->auditTrailService = $auditTrailService;
    }

    public function index() {
        $audits = $this->auditTrailService->getAuditTrail(2234);
        return view('resources.audit_trail',['audits' => $audits]);
    }

    public function ajaxAudit(Request $request) {
        try {
            $id = $request->id;
            $type = $request->type;
            $tab = $request->tab;

            $this->auditTrailService->logAjaxAudit($id, $type, $tab);
            return response()->json(['status_code' => 200, 'msg' => 'Log audit success']);
        } catch (\Exception $e) {
             return response()->json(['status_code' => 500, 'msg' => $e->getMessage()]);
        }
    }
}
