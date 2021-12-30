<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\AdminToolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolBoxController extends Controller
{
    private $adminToolService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AdminToolService $adminToolService
    )
    {
        $this->adminToolService = $adminToolService;

    }

    /**
     * Show my organisation by id.
     *
     */
    public function getUpload(Request $request)
    {
        return view('shineCompliance.resources.admin_tool_box.upload');
    }

    public function getConfigurations(Request $request)
    {
        return view('shineCompliance.resources.settings.configurations');
    }

    public function dropdownTypeConfiguration(Request $request) {
        $validator = \Validator::make($request->all(), [
//            'type_id' => 'required',
            'selected' => 'sometimes',
        ]);

        if ($request->has('selected') || $request->has('checked_contractors')) {
            $selected = $request->selected;
            $checked_contractors = $request->checked_contractors;

            if (!is_null($selected) || !is_null($checked_contractors)) {
                $selected = explode(",", $selected);
                $checked_contractors = explode(",", $checked_contractors);
            } else {
                $selected = [];
                $checked_contractors = [];
            }
        } else {
            $selected = [];
            $checked_contractors = [];
        }

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        $contractor_lists = [
            1 =>[
                'id' => 1,
                'name' => 'Dropdown Value #1',
            ] ,
            2 =>[
                'id' => 2,
                'name' => 'Dropdown Value #2',
            ] ,
            3 =>[
                'id' => 3,
                'name' => 'Dropdown Value #3',
            ]
        ];
        $html = view('shineCompliance.forms.form_multiple_dropdown_type', [ 'name'=>'contractors',
            'title' => 'Contractors',
            'dropdown_list'=> $contractor_lists,
            'id_select' => 'contractors',
            'name' => 'contractors',
            'value_get' => 'name',
            'selected'=> $selected,
            'checked_contractors'=> $checked_contractors,
            'max_option' => count($contractor_lists) ,
            'edit_project' => $request->has('edit_project') ? true : false
        ])->render();
        return response()->json(['status_code' =>200, 'data'=> $html, 'total' => count($contractor_lists)]);
    }


    public function downloadTemplate(Request $request) {
        if ($request->has('type')) {
            switch ($request->type) {
                case 'properties':
                    return \Storage::download('UploadPropertiesTemplate.csv');
                    break;
                case 'users':
                    return \Storage::download('UploadUsersTemplate.csv');
                    break;
                case 'programmes':
                    return \Storage::download('Upload Programmes.xlsx');
                    break;
                case 'systems':
                    return \Storage::download('Upload Systems and Programmes.xlsx');
                    break;

                default:
                    return redirect()->back()->with('err', 'No Template found');
                    break;
            }
        }
         return redirect()->back()->with('err', 'No Template found');
    }

    public function postUpload(Request $request) {
        if ($request->has('document')) {
            switch ($request->type) {
                case 'programmes':
                    $data = $this->adminToolService->uploadProgramme($request->document);
                    break;
                case 'systems':
                    $data = $this->adminToolService->uploadSystemAndProgramme($request->document);
                    break;

                default:
                    $data = null;
                    break;
            }
            if (is_null($data)) {
                # code...
            } else {
                if ($data['status_code'] == STATUS_OK) {
                    return redirect()->back()->with('msg', $data['msg']);
                } else {
                    return redirect()->back()->with('err', $data['msg']);
                }
            }
        }
        return redirect()->back()->with('err', 'Please upload a document');
    }
}
