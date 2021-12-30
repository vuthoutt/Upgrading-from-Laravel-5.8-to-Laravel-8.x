<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\LocationRepository;
use App\Repositories\AreaRepository;
use App\Repositories\SurveyRepository;
use App\Http\Request\Location\LocationCreateRequest;

class LocationController extends Controller
{

        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LocationRepository $locationRepository, AreaRepository $areaRepository, SurveyRepository $surveyRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->areaRepository = $areaRepository;
        $this->surveyRepository = $surveyRepository;
    }

    public function getAddLocation(Request $request) {
        $locationVoids = $this->locationRepository->getLocationDropdown(LOCATION_VOID_ID);
        $locationContructions = $this->locationRepository->getLocationDropdown(LOCATION_CONTRUCTION_DETAILS_ID);
        $reasons = $this->locationRepository->getDropdownById(LOCATION_REASONS);
        $area = $this->areaRepository->getArea($request->area_id);
        if (empty($area)) {
            abort(404);
        }
        $survey = $this->surveyRepository->getSurvey($area->survey_id);

        if (\CommonHelpers::isSystemClient()) {
            //check privilege
            // if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $area->property_id) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $area->property_id)){
            //     abort(404);
            // }
        } else {
            if ($area->survey_id > 0) {
                if(\Auth::user()->client_id == $area->survey->client_id || \Auth::user()->id == $area->survey->surveyor_id || \Auth::user()->id == $area->survey->consultant_id || \Auth::user()->id == $area->survey->created_by) {
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }

        $locationVoidsData = [];
        //push location void to array
        foreach ($locationVoids as $locationVoid ) {
            $dataVoid['id'] = $locationVoid->id;
            $dataVoid['description'] = $locationVoid->description;
            $dataVoid['name'] = str_replace(' ', '-', $locationVoid->description);
            $parents = $this->locationRepository->getDropdownById($locationVoid->id);

            foreach ($parents as $key => $parent) {
                $dataVoid['parents'][$key]['id'] = $parent->id;
                $dataVoid['parents'][$key]['description'] = $parent->description;
                $dataVoid['parents'][$key]['name'] = str_replace(' ', '-', $parent->description);
                if (!is_null($parent->allChildrenAccounts)) {
                    $dataVoid['parents'][$key]['childs'] = $parent->allChildrenAccounts;
                }
            }

            $locationVoidsData[] = $dataVoid;
        }

        $locationContructionsDatas = [];
        //push location contruction to array
        foreach ($locationContructions as $locationContruction ) {
            $dataContruction['id'] = $locationContruction->id;
            $dataContruction['description'] = $locationContruction->description;
            $dataContruction['name'] = str_replace(' ', '-', $locationContruction->description);
            $dataContruction['childs'] = $this->locationRepository->getDropdownById($locationContruction->id);
            $locationContructionsDatas[] = $dataContruction;
        }


        return view('location.add_location',[
            'locationVoidsData' => $locationVoidsData,
            'locationContructionsDatas' => $locationContructionsDatas,
             'reasons' => $reasons,
             'area' => $area,
             'survey' => $survey,
         ] );
    }

    public function postAddLocation(LocationCreateRequest $locationCreateRequest) {
        $validatedData = $locationCreateRequest->validated();

        $location = $this->locationRepository->createLocation($validatedData);

        if (isset($location) and !is_null($location)) {
            if ($location['status_code'] == 200) {
                //for register
                if ($location['data']->survey_id == 0) {
                    return redirect()->route('property_detail',['id' => $location['data']->property_id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $location['data']->id] )->with('msg', $location['msg']);
                //for survey
                } else {
                    return redirect()->route('property.surveys',['survey_id' => $location['data']->survey_id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $location['data']->id] )->with('msg', $location['msg']);
                }
            } else {
                return redirect()->back()->with('err', $location['msg']);
            }
        }
    }

    public function getEditLocation($id, Request $request) {
        $location = $this->locationRepository->getLocation($id);
        if (empty($location)) {
            abort(404);
        }

        if (\CommonHelpers::isSystemClient()) {
            //check privilege
            // if(!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $location->property_id) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $location->property_id)){
            //     abort(404);
            // }
        } else {
            if ($location->survey_id > 0) {
                if(\Auth::user()->client_id == $location->survey->client_id || \Auth::user()->id == $location->survey->surveyor_id || \Auth::user()->id == $location->survey->consultant_id || \Auth::user()->id == $location->survey->created_by) {
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }

        $locationVoids = $this->locationRepository->getLocationDropdown(LOCATION_VOID_ID);
        $locationContructions = $this->locationRepository->getLocationDropdown(LOCATION_CONTRUCTION_DETAILS_ID);
        $reasons = $this->locationRepository->getDropdownById(LOCATION_REASONS);

        $locationVoidsData = [];
        //push location void to array
        foreach ($locationVoids as $locationVoid ) {
            $dataVoid['id'] = $locationVoid->id;
            $dataVoid['description'] = $locationVoid->description;
            $dataVoid['name'] = str_replace(' ', '-', $locationVoid->description);
            $parents = $this->locationRepository->getDropdownById($locationVoid->id);

            // get selected void
            switch ($locationVoid->id) {
                case LOCATION_CEILING_VOID:
                    $dataVoid['selected_parent'] = $this->locationRepository->getLocationVoidId($location->locationVoid->ceiling,'parent');
                    $dataVoid['selected_child'] = $this->locationRepository->getLocationVoidId($location->locationVoid->ceiling);
                    $dataVoid['selected_other'] = $location->locationVoid->ceiling_other;
                    break;

                case LOCATION_CAVITIES:
                    $dataVoid['selected_parent'] = $this->locationRepository->getLocationVoidId($location->locationVoid->cavities,'parent');
                    $dataVoid['selected_child'] = $this->locationRepository->getLocationVoidId($location->locationVoid->cavities);
                    $dataVoid['selected_other'] = $location->locationVoid->cavities_other;
                    break;

                case LOCATION_RISERS:
                    $dataVoid['selected_parent'] = $this->locationRepository->getLocationVoidId($location->locationVoid->risers,'parent');
                    $dataVoid['selected_child'] = $this->locationRepository->getLocationVoidId($location->locationVoid->risers);
                    $dataVoid['selected_other'] = $location->locationVoid->risers_other;
                    break;

                case LOCATION_DUCTING:
                    $dataVoid['selected_parent'] = $this->locationRepository->getLocationVoidId($location->locationVoid->ducting,'parent');
                    $dataVoid['selected_child'] = $this->locationRepository->getLocationVoidId($location->locationVoid->ducting);
                    $dataVoid['selected_other'] = $location->locationVoid->ducting_other;
                    break;

                case LOCATION_FLOOR_VOID:
                    $dataVoid['selected_parent'] = $this->locationRepository->getLocationVoidId($location->locationVoid->floor,'parent');
                    $dataVoid['selected_child'] = $this->locationRepository->getLocationVoidId($location->locationVoid->floor);
                    $dataVoid['selected_other'] = $location->locationVoid->floor_other;
                    break;

                case LOCATION_BOXING:
                    $dataVoid['selected_parent'] = $this->locationRepository->getLocationVoidId($location->locationVoid->boxing,'parent');
                    $dataVoid['selected_child'] = $this->locationRepository->getLocationVoidId($location->locationVoid->boxing);
                    $dataVoid['selected_other'] = $location->locationVoid->boxing_other;
                    break;

                case LOCATION_PIPEWORK:
                    $dataVoid['selected_parent'] = $this->locationRepository->getLocationVoidId($location->locationVoid->pipework,'parent');
                    $dataVoid['selected_child'] = $this->locationRepository->getLocationVoidId($location->locationVoid->pipework);
                    $dataVoid['selected_other'] = $location->locationVoid->pipework_other;
                    break;

                default:
                    $dataVoid['selected_parent'] = [];
                    $dataVoid['selected_child'] = [];
                    $dataVoid['selected_other'] = [];
                    break;
            }

            foreach ($parents as $key => $parent) {
                $dataVoid['parents'][$key]['id'] = $parent->id;
                $dataVoid['parents'][$key]['description'] = $parent->description;
                $dataVoid['parents'][$key]['name'] = str_replace(' ', '-', $parent->description);
                if (!is_null($parent->allChildrenAccounts)) {
                    $dataVoid['parents'][$key]['childs'] = $parent->allChildrenAccounts;
                }
            }

            $locationVoidsData[] = $dataVoid;
        }

        $locationContructionsDatas = [];
        //push location contruction to array
        foreach ($locationContructions as $locationContruction ) {
            $dataContruction['id'] = $locationContruction->id;
            $dataContruction['description'] = $locationContruction->description;
            $dataContruction['name'] = str_replace(' ', '-', $locationContruction->description);
            $dataContruction['childs'] = $this->locationRepository->getDropdownById($locationContruction->id);

            // get selected contruction
            switch ($locationContruction->id) {
                case LOCATION_CEILING:
                    $dataContruction['selected'] = $this->locationRepository->getLocationContructionId($location->locationConstruction->ceiling);
                    $dataContruction['selected_other'] = $location->locationConstruction->ceiling_other;
                    break;

                case LOCATION_WALLS:
                    $dataContruction['selected'] = $this->locationRepository->getLocationContructionId($location->locationConstruction->walls);
                    $dataContruction['selected_other'] = $location->locationConstruction->walls_other;
                    break;

                case LOCATION_FLOOR:
                    $dataContruction['selected'] = $this->locationRepository->getLocationContructionId($location->locationConstruction->floor);
                    $dataContruction['selected_other'] = $location->locationConstruction->floor_other;
                    break;

                case LOCATION_DOORS:
                    $dataContruction['selected'] = $this->locationRepository->getLocationContructionId($location->locationConstruction->doors);
                    $dataContruction['selected_other'] = $location->locationConstruction->doors_other;
                    break;

                case LOCATION_WINDOWS:
                    $dataContruction['selected'] = $this->locationRepository->getLocationContructionId($location->locationConstruction->windows);
                    $dataContruction['selected_other'] = $location->locationConstruction->windows_other;
                    break;

                default:
                    $dataContruction['selected'] = [];
                    $dataContruction['selected_other'] = [];
                    break;
            }
            $locationContructionsDatas[] = $dataContruction;
        }

        $survey = $this->surveyRepository->getSurvey($location->survey_id);

        return view('location.edit_location',[
            'location' => $location,
            'locationVoidsData' => $locationVoidsData,
            'locationContructionsDatas' => $locationContructionsDatas,
            'reasons' => $reasons,
            'area_id' => $location->area_id,
            'survey_id' => $location->survey_id,
            'property_id' => $location->property_id,
            'survey' => $survey,
            'position' => $request->position ?? 0,
            'category' => $request->category ?? 0,
            'pagination_type' => $request->pagination_type ?? 0,
        ]);
    }

    public function postEditLocation($id, LocationCreateRequest $locationCreateRequest) {
        $validatedData = $locationCreateRequest->validated();
        $locationUpdate = $this->locationRepository->createLocation($validatedData, $id);
        $location = $this->locationRepository->getLocation($id);

        if (isset($locationUpdate) and !is_null($locationUpdate)) {
            if ($locationUpdate['status_code'] == 200) {
                //for register
                if ($location->survey_id == 0) {
                    return redirect()->route('property_detail',['id' => $location->property_id,
                        'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $id,
                        'position' => $locationCreateRequest->position,
                        'category' => $locationCreateRequest->category,
                        ] )->with('msg', $locationUpdate['msg']);
                //for survey
                } else {
                    return redirect()->route('property.surveys',['survey_id' => $location->survey_id,
                        'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $id,
                        'position' => $locationCreateRequest->position,
                        'category' => $locationCreateRequest->category,
                        'pagination_type' => $locationCreateRequest->pagination_type,
                    ] )->with('msg', $locationUpdate['msg']);
                }
            } else {
                return redirect()->back()->with('err', $locationUpdate['msg']);
            }
        }
    }

    public function decommissionLocation($location_id, Request $request) {
        $reason = $request->location_decommisson_reason_add;
        $decommissionLocation = $this->locationRepository->decommissionLocation($location_id, $reason );
        if (isset($decommissionLocation)) {
            if ($decommissionLocation['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionLocation['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionLocation['msg']);
            }
        }
    }

    public function decommissionLocationReason($location_id, Request $request) {
        $reason = $request->location_decommisson_reason;
        $decommissionLocationReason = $this->locationRepository->decommissionLocationReason($location_id, $reason);
        if (isset($decommissionLocationReason)) {
            if ($decommissionLocationReason['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionLocationReason['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionLocationReason['msg']);
            }
        }
    }

    public function locationReason(Request $request) {
        $reasons = $this->locationRepository->getDropdownById(LOCATION_REASONS, $request->parent_id ?? 0);
        return response()->json(['status_code' => 200, 'data' => $reasons]);
    }
}
