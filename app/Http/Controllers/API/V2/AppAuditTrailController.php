<?php


namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\API\AppBaseController;
use App\Repositories\AppAuditTrailRepository;
use Illuminate\Http\Request;

class AppAuditTrailController extends AppBaseController
{
    private $auditTrailRepository;
    public function __construct(AppAuditTrailRepository $auditTrailRepository)
    {
        $this->auditTrailRepository = $auditTrailRepository;
    }

    public function logAppAuditTrail(Request $request)
    {
        $result = $this->auditTrailRepository->logAuditTrail($request->all(), $request->getContent());

        if ($result['status_code'] == STATUS_OK) {
            return $this->sendResponse($result['data'], $result['msg']);
        } else {
            return $this->sendError($result['msg'], $result['status_code']);
        }
    }
}
