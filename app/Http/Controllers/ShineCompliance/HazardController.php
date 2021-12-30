<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\HazardService;
use App\Services\ShineCompliance\ProjectService;
use App\Services\ShineCompliance\PropertyService;
use Illuminate\Http\Request;

class HazardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $hazardService;
    private $assessmentService;
    private $propertyService;
    private $projectService;

    public function __construct(
        AssessmentService $assessmentService,
        PropertyService $propertyService,
        HazardService $hazardService,
        ProjectService $projectService
    )
    {
        $this->assessmentService = $assessmentService;
        $this->propertyService = $propertyService;
        $this->hazardService = $hazardService;
        $this->projectService = $projectService;
    }

    public function getHazardDetail($id){

        if(!$hazard = $this->hazardService->getHazardDetail($id)){
            abort(404);
        }
        $assessment = null;
        $can_update  = true;
        if (!\CommonHelpers::isSystemClient() and ($hazard->assess_id == 0)) {
            $can_update  = false;
        } elseif(\CommonHelpers::isSystemClient() and ($hazard->assess_id == 0)) {
            // check update permission for fire
            if ($hazard->assess_type == ASSESSMENT_FIRE_TYPE) {
                if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_REGISTER_FIRE_HAZARDS, JOB_ROLE_FIRE)) {
                    $can_update = false;
                }
            } else {
                // check update permission for water
                if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_REGISTER_WATER_HAZARDS, JOB_ROLE_WATER)) {
                    $can_update = false;
                }
            }

        $assessment = $this->assessmentService->getAssessmentDetail($hazard->assess_id);
        }
        $projects = $this->projectService->getPropertyProjects($hazard->property_id);
        //log audit
        $comment = \Auth::user()->full_name . " viewed hazard detail";
        \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_VIEW, $hazard->reference, $hazard->property_id, $comment);
        return view('shineCompliance.hazard.get_detail_hazard',compact('hazard','assessment', 'can_update', 'projects'));
    }

    public function getAddHazard($property_id, Request $request){

        $assess_id = 0;
        $question_id = $request->question_id;
        $hazard_comment = '';
        if($question_id){
            $hazard_name = $this->assessmentService->getDataAssessmentQuestions($question_id)->description;
            $hazard_comment = $this->assessmentService->getDataAssessmentQuestions($question_id)->preloaded_comment;
        }else{
            $hazard_name = $request->name;
        }
        $verb_id = $request->verb_id;
        $noun_id = $request->noun_id;
        $assessment = NULL;
        if ($request->has('assess_id')) {
            $assess_id = $request->assess_id;
            $assessment = $this->assessmentService->getAssessmentDetail($assess_id);
        }

        $property =  $this->propertyService->getProperty($property_id);

        $inaccessible_reason = $this->hazardService->getInaccessibleReasonHazard();
        $areas = $this->assessmentService->getAreaAssessment($assess_id, $property_id);
        if ($assessment) {
            $hazard_types = $this->hazardService->getHazardType($assessment->classification);
            $actions_recommendation_nouns = $this->hazardService->getHazardActionRecommendationNoun($assessment->classification);
            $actions_recommendation_verbs = $this->hazardService->getHazardActionRecommendationVerb($assessment->classification);
            $specificLocations = $this->hazardService->getHazardSpecificLocation(4);
        } else {
            $hazard_types = $this->hazardService->getHazardType($request->assess_type);
            $actions_recommendation_nouns = $this->hazardService->getHazardActionRecommendationNoun($request->assess_type);
            $actions_recommendation_verbs = $this->hazardService->getHazardActionRecommendationVerb($request->assess_type);
            $specificLocations = $this->hazardService->getHazardSpecificLocation(4);
        }

        $hazard_action_responsibilities = $this->hazardService->getHazardActionResponsibilities();
        $hazard_potentials = $this->hazardService->getHazardPotential();
        $hazard_likelihood_harms = $this->hazardService->getHazardLikelihoodHarm();
        $extends = $this->hazardService->getHazardExtent(367);

        //log audit
        $comment = \Auth::user()->full_name . " viewed add hazard form for property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(HAZARD_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);
        return view('shineCompliance.hazard.get_add_hazard', compact('assessment','areas','hazard_types','inaccessible_reason',
            'hazard_potentials','hazard_likelihood_harms','specificLocations','extends','assess_id','property', 'property_id','question_id',
            'verb_id', 'noun_id', 'hazard_name', 'hazard_action_responsibilities',
            'actions_recommendation_nouns','actions_recommendation_verbs','hazard_comment'));
    }

    public function postAddHazard($assess_id, Request $request){
        $validated = $request->validate([
            'location_photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'hazard_photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'additional_photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
        ]);
        $updateOrCreateHazard = $this->hazardService->updateOrCreateHazard($request->all());
        if (isset($updateOrCreateHazard) and !is_null($updateOrCreateHazard)) {
            if ($updateOrCreateHazard['status_code'] == 200) {
                //todo redirect to hazard detail
                return redirect()->route('shineCompliance.assessment.get_hazard_detail',[
                    'id' => $updateOrCreateHazard['data']->id ?? 0
                ])->with('msg', $updateOrCreateHazard['msg']);
            } else {
                return redirect()->back()->with('err', $updateOrCreateHazard['msg']);
            }
        }
    }

    public function getEditHazard($hazard_id){
        if(!$hazard = $this->hazardService->getHazardDetail($hazard_id)){
            abort(404);
        }
        $areas = $this->assessmentService->getAreaAssessment($hazard->assess_id, $hazard->property_id);
        $assessment = $hazard->assessment ?? null;
        $inaccessible_reason = $this->hazardService->getInaccessibleReasonHazard();
        $locations = $this->assessmentService->getLocationsByAssessmentAndArea($hazard->assess_id, $hazard->area_id);

        if ($assessment) {
            $hazard_types = $this->hazardService->getHazardType($assessment->classification);
        } else {
            $hazard_types = $this->hazardService->getHazardType($hazard->assess_type);
        }
        $hazard_potentials = $this->hazardService->getHazardPotential();
        $hazard_likelihood_harms = $this->hazardService->getHazardLikelihoodHarm();
        $specificLocations = $this->hazardService->getHazardSpecificLocation(4);
        $selectedSpecificLocations = $this->hazardService->getSpecificlocationValue($hazard_id);
        $otherSpecificLocation = $hazard->hazardSpecificLocation->other ?? '';
        $extends = $this->hazardService->getHazardExtent(367);
        $actions_recommendation_nouns = $this->hazardService->getHazardActionRecommendationNoun($hazard->assess_type);
        $actions_recommendation_verbs = $this->hazardService->getHazardActionRecommendationVerb($hazard->assess_type);
        $hazard_action_responsibilities = $this->hazardService->getHazardActionResponsibilities();

        //log audit
        $comment = \Auth::user()->full_name . " viewed hazard to edit";
        \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_VIEW, $hazard->reference, $hazard->property_id, $comment);
        return view('shineCompliance.hazard.get_edit_hazard', compact('hazard','assessment','inaccessible_reason',
        'areas','locations','hazard_types','hazard_potentials','hazard_likelihood_harms','specificLocations','extends',
        'actions_recommendation_nouns','actions_recommendation_verbs','selectedSpecificLocations','otherSpecificLocation','hazard_action_responsibilities'));
    }

    public function postEditHazard($hazard_id, Request $request){
        if(!$hazard = $this->hazardService->getHazardDetail($hazard_id)){
            abort(404);
        }
        $validated = $request->validate([
            'location_photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'hazard_photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'additional_photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
        ]);
        $updateOrCreateHazard = $this->hazardService->updateOrCreateHazard($request->all(), $hazard_id);
        if (isset($updateOrCreateHazard) and !is_null($updateOrCreateHazard)) {
            if ($updateOrCreateHazard['status_code'] == 200) {
                //todo redirect to hazard detail
                return redirect()->route('shineCompliance.assessment.get_hazard_detail',[
                    'id' => $updateOrCreateHazard['data']->id ?? 0
                ])->with('msg', $updateOrCreateHazard['msg']);
            } else {
                return redirect()->back()->with('err', $updateOrCreateHazard['msg']);
            }
        }
    }

    public function decommissionHazard($hazard_id, Request $request){

        if(!$hazard = $this->hazardService->getHazardDetail($hazard_id)){
            abort(404);
        }
        $decommissionHazard = $this->hazardService->decommissionHazard($hazard, $request->decommission_reason ?? 0, $request->linked_project ?? 0);
        if (isset($decommissionHazard)) {
            if ($decommissionHazard['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionHazard['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionHazard['msg']);
            }
        }
    }

    public function confirmHazard($hazard_id){
        if(!$hazard = $this->hazardService->getHazardDetail($hazard_id)){
            abort(404);
        }
        $confirmHazard = $this->hazardService->confirmHazard($hazard);
        if (isset($confirmHazard)) {
            if ($confirmHazard['status_code'] == 200) {
                return redirect()->back()->with('msg', $confirmHazard['msg']);
            } else {
                return redirect()->back()->with('err', $confirmHazard['msg']);
            }
        }
    }

    public function recommissionHazard($hazard_id){
        if(!$hazard = $this->hazardService->getHazardDetail($hazard_id)){
            abort(404);
        }
        $recommissionHazard = $this->hazardService->recommissionHazard($hazard);
        if (isset($recommissionHazard)) {
            if ($recommissionHazard['status_code'] == 200) {
                return redirect()->back()->with('msg', $recommissionHazard['msg']);
            } else {
                return redirect()->back()->with('err', $recommissionHazard['msg']);
            }
        }
    }

    public function getSpecificDropdown(Request $request) {

        $parent_id = ($request->has('parent_id')) ? $request->parent_id : 0;

        $dropdowns = $this->hazardService->getHazardSpecificLocation(null, $parent_id);
        $have_child = false;
        foreach ($dropdowns as $dropdown) {
            if (count($dropdown->allChildrens)) {
                $have_child = true;
            }
        }

        $response = [
            'data' => $dropdowns,
            'have_child' => $have_child
        ];
        return response()->json($response);
    }
}
