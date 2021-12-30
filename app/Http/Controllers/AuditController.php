<?php

namespace App\Http\Controllers;

use App\Repositories\AppAuditTrailRepository;
use Illuminate\Http\Request;
use App\Repositories\AuditTrailRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\SurveyRepository;
use App\Repositories\ItemRepository;

class AuditController extends Controller
{
    private $appAuditTrailRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuditTrailRepository $auditTrailRepository,
                                AppAuditTrailRepository $appAuditTrailRepository,
                                ItemRepository $itemRepository,
                                PropertyRepository $propertyRepository,
                                SurveyRepository $surveyRepository)
    {
        $this->auditTrailRepository = $auditTrailRepository;
        $this->appAuditTrailRepository = $appAuditTrailRepository;
        $this->propertyRepository = $propertyRepository;
        $this->itemRepository = $itemRepository;
        $this->surveyRepository = $surveyRepository;
    }

    public function index() {
        $audits = $this->auditTrailRepository->getAuditTrail(2234);
        return view('resources.audit_trail',['audits' => $audits]);
    }

    public function ajaxAudit(Request $request) {
        try {
            $id = $request->id;
            $type = $request->type;
            $tab = $request->tab;

            $this->auditTrailRepository->logAjaxAudit($id, $type, $tab);
            return response()->json(['status_code' => 200, 'msg' => 'Log audit success']);
        } catch (Exception $e) {
             return response()->json(['status_code' => 500, 'msg' => $e->getMessage()]);
        }
    }

    public function indexAppAudit()
    {
        $audits = $this->appAuditTrailRepository->getAppAuditTrail(2234);
        return view('resources.app_audit_trail',['audits' => $audits]);
    }
}
