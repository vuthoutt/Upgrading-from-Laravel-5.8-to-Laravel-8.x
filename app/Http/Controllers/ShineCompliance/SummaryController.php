<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Export\CollectionExport;
use App\Services\ShineCompliance\AuditTrailService;
use App\Services\ShineCompliance\SummaryService;
use Carbon\Carbon;
use App\Models\ShineCompliance\ComplianceAuditTrail;
use App\Models\ShineCompliance\ComplianceProgramme;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SummaryController extends Controller
{
    public $col = 0;
    private $summaryService;

    public function __construct(AuditTrailService $auditTrailService, SummaryService $summaryService)
    {
        $this->auditTrailService = $auditTrailService;
        $this->summaryService = $summaryService;
    }


    public function reportSummary()
    {
        return view('shineCompliance.reporting.summary');
    }

    public function generalSummary()
    {
        $type = null;
        return view('shineCompliance.reporting.general_summary', ['type' => $type]);
    }

    public function fireSummary()
    {
        $type = FIRE;
        return view('shineCompliance.reporting.fire_summary.fire_document', ['type' => $type]);
    }

    public function fireHazardSummary()
    {
        $type = FIRE;
        return view('shineCompliance.reporting.fire_summary.fire_hazard', ['type' => $type]);
    }

    public function fireAssessmentSummary()
    {
        $type = FIRE;
        return view('shineCompliance.reporting.fire_summary.fire_assessment', ['type' => $type]);
    }

    public function waterSummary()
    {
        $type = WATER;
        return view('shineCompliance.reporting.water_summary', ['type' => $type]);
    }

    public function exportFireAssessmentSummary()
    {
        $data = $this->summaryService->getFireAssessmentSummary();

        $comment = \Auth::user()->full_name . " exported Fire Assessment Summary";
        \ComplianceHelpers::logAudit(SUMMARY_TYPE, 0, AUDIT_ACTION_EXPORT, 0, 0, $comment);

        $title = [
            'Assessment Reference','Assessment Type','Assessment Status','Aborted Reason','Risk Warning','Property Reference','Property Block','Property Name','Shine Reference','Property Group','Asset Class','Asset Type','Tenure Type','Created Date','Assessment Started Date','Assessment Finished','Assessment Due Date','Completed Date','Days Remaining','Turnaround','No. of Hazards','No. Of Rejections','Commissioned By','Fire Lead','Second Fire Lead','Fire Risk Assessor','Quality Checked By','Linked Project','WR Reference','WR Type','WR Requester'
        ];
        $fileName = 'Fire_Assessment_Summary' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function exportSummary()
    {
        $data = $this->PreplanSummary();

        $comment = \Auth::user()->full_name . " exported Pre-Planned Maintenance Summary";
        \ComplianceHelpers::logAudit(SUMMARY_TYPE, 0, AUDIT_ACTION_EXPORT, 0, 0, $comment);

        $title = [
            'System Reference','System Type','System Name','Programme Type', 'Programme Reference', 'Previous Date','Next Date','Risk Warning','Property Name','Property Group','UBRN','UPRN'
        ];
        $fileName = 'Pre-Planned_Maintenance_Summary' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function fireDocumentsSummary() {
        $data = $this->summaryService->getFireDocumentsSummary();

        $comment = \Auth::user()->full_name . " exported Fire Documents Summary";
        \ComplianceHelpers::logAudit(FIRE_DOCUMENTS_SUMMARY_TYPE, 0, AUDIT_ACTION_EXPORT, 0, 0, $comment);

        $title = [
            'Document Reference','Document Title','Document Category','Document Type', 'Parent', 'Document Date','Enforcement Date','Document Status','Document Upload Date','User', 'Shine Reference','Property UPRN','Property Block', 'Property Name', 'Property Group'
        ];
        $fileName = 'Fire_Documents_Summary' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function exportfireHazardSummary() {
        $data = $this->summaryService->getFireHazardARSummary();

        $comment = \Auth::user()->full_name . " exported Fire Hazard AR Summary";
        \ComplianceHelpers::logAudit(FIRE_HAZARD_AR_SUMMARY_TYPE, 0, AUDIT_ACTION_EXPORT, 0, 0, $comment);

        $title = [
            'Reference','Name','Type','Hazard Potential', 'Likelihood of Harm', 'Score', 'ORA', 'Extent', 'Creation Date', 'Due Date',
            'Risk Warning', 'Verb', 'Noun', 'Action/recommendation', 'Action Responsibility', 'Comment', 'Shine Reference', 'Property Name',
            'Property Reference', 'Parent', 'Block Reference', 'Estate Code', 'Property Group', 'Asset Class', 'Asset Type', 'Area/floor Reference', 'Area/floor Description',
            'Room/location Reference', 'Room/location Description'
        ];
        $fileName = 'Fire_Hazard_AR_Summary' . Carbon::now()->format('d_m_y') . '.csv';
        return \Excel::download(new CollectionExport($data, $title), $fileName);
    }

    public function PreplanSummary() {

        $data = ComplianceProgramme::with('property','property.zone','system','system.systemType','documentInspection')->get();
        foreach ($data as $preplan){
                $data_prep['system_reference'] = $preplan->system->reference ?? '';
                $data_prep['system_type'] = $preplan->system->systemType->description ?? '';
                $data_prep['system_name'] = $preplan->system->name ?? '';
                $data_prep['programe_type'] = $preplan->name ?? '';
                $data_prep['programe_reference'] = $preplan->reference ?? '';
                $data_prep['previous_date'] = $preplan->documentInspection->date ?? '';
                $data_prep['next_date'] = $preplan->next_inspection_display ?? '';
                $next_date = $preplan->next_inspection_display ?? '';
                $inspect_date = $preplan->documentInspection->date ?? '';

                $compare_date = NULL;
            if(!empty($next_date) && !empty($inspect_date)){
                $to = Carbon::createFromFormat('d/m/Y', $inspect_date);
                $from = Carbon::createFromFormat('d/m/Y', $next_date);
                $compare_date = $from->diffInDays($to);
            }
            if(!empty($compare_date)){
                if ($compare_date > 120){
                    $data_prep['risk_warning'] = 'Deadline';
                }elseif ($compare_date > 60){
                    $data_prep['risk_warning'] = 'Attention';
                }elseif ($compare_date > 30){
                    $data_prep['risk_warning'] = 'Important';
                }elseif ($compare_date > 14){
                    $data_prep['risk_warning'] = 'Urgent';
                }elseif ($compare_date > 0){
                    $data_prep['risk_warning'] = 'Critical';
                }elseif ($compare_date <= 0){
                    $data_prep['risk_warning'] = 'Overdue';
                }
            }else{
                $data_prep['risk_warning'] = 'Missing';
            }
                $data_prep['pro_name'] = $preplan->property->name ?? '';
                $data_prep['zone_name'] = $preplan->property->zone->name ?? '';
                $data_prep['pro_plock'] = $preplan->property->plock ?? '';
                $data_prep['pro_property_reference'] = $preplan->property->property_reference ?? '';

                $dataSum[] = $data_prep;
        }
        return $dataSum ?? [];
    }

    public function getAuditTrail(Request $request) {
        if ($request->ajax()) {
            $audits = $this->auditTrailService->getAuditTrail($request);
            return DataTables::of($audits)
                ->editColumn('shine_reference', function ($data) {
                    return $data->shine_reference ?? '';
                })
                ->editColumn('object_reference', function ($data) {
                    return $data->object_reference ?? '';
                })
                ->editColumn('action_type', function ($data) {
                    return $data->action_type ?? '';
                })
                ->editColumn('user_name', function ($data) {
                    return $data->user_name ?? '';
                })
                ->editColumn('date', function ($data) {
                    return $data->date ?? '';
                })
                ->editColumn('date', function ($data) {
                    return $data->date ?? '';
                })
                ->editColumn('comments', function ($audit) {
                    return $audit->comments ?? '';
                })
                ->addColumn('date_hour_display', function ($audit) {
                    return $audit->date_hour;
                })
                ->filter(function ($query) {
                })
                ->rawColumns(['shine_reference', 'object_reference', 'action_type', 'user_name', 'date', 'date_hour_display', 'comments'])
                ->make(true);
        }
        return view('shineCompliance.reporting.audit_trail');
    }
}
