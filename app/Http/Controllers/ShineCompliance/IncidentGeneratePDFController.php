<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Models\ShineCompliance\IncidentReport;
use App\Services\Incident\IncidentGeneratePDFService;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use \PDF;
use Illuminate\Http\Request;
use mikehaertl\pdftk\Pdf as PDFTK;
use ZipArchive;
use App\Jobs\SendClientEmailNotification;
use App\Jobs\SendApprovalEmail;

class IncidentGeneratePDFController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $incidentGeneratePDFService;
    public function __construct(IncidentGeneratePDFService $incidentGeneratePDFService)
    {
        $this->incidentGeneratePDFService = $incidentGeneratePDFService;
    }

    public function publishIncidentPDF($incident_id, Request $request){
        $is_draft = $request->has('incident_draf') ? true : false;
        $incident_data = IncidentReport::with('property','equipment','system','documents',
            'incidentType','involvedPersons','publishedIncidentReport','reportedUser','hsLead')->where('id',$incident_id)->first();
        if (is_null($incident_data)) {
            return redirect()->back()->with('err', 'Certificate does not exist');
        }
        $publish_incident = $this->incidentGeneratePDFService->publishIncidentPDF($is_draft, $incident_data);
        if($publish_incident && isset($publish_incident['status_code']) && $publish_incident['status_code'] = 200){
            return redirect()->back()->with('msg', $publish_incident['msg']);
        } else {
            return redirect()->back()->with('err', $publish_incident['msg']);
        }
    }

    public function viewPDF(Request $request){
        $type = $request->type;
        $id = $request->id;
        if($type == VIEW_SURVEY_PDF) {
            return $this->incidentGeneratePDFService->viewPDFIncident($id);
        }
        return false;
    }
    public function downloadPDF($type, $id){
        return $this->incidentGeneratePDFService->downloadPDFIncident($id);
        return abort(404);
    }
}
