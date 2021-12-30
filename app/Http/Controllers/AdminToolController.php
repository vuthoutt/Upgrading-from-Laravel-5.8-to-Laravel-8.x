<?php

namespace App\Http\Controllers;

use App\Models\AssetClass;
use App\Models\CommunalArea;
use App\Models\Property;
use App\Models\PropertyInfo;
use App\Models\PropertyProgrammeType;
use App\Models\PropertySurvey;
use App\Models\Responsibility;
use App\Models\ServiceArea;
use App\Models\TenureType;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\CustomClass\SimpleXLSX;
use Illuminate\Support\Facades\DB;

class AdminToolController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $route;

    public function __construct()
    {
    }

    /**
     * Show update page based on Excel file.
     *
     */
    public function updateProperty()
    {
        return view('admin_tool.update_property');
    }

    /**
     * Show my organisation by id.
     *
     */
    public function postUpdateProperty(Request $request)
    {
//        dd($request);
        if($request->hasFile('upload_document')){

            $path = $request->file('upload_document')->getRealPath();
//            dd($path);
            $xlsx = new SimpleXLSX();
            $xlsx->debug = true; // display errors
            $xlsx->skipEmptyRows = true; // skip empty rows
            if ($xlsx->parseFile($path)) {
//                0 => ""
//                1 => "PL Reference"
//                2 => "Property  UPRN"
//                3 => "Block Code"
//                4 => "Parent Reference"
//                5 => "Service Area"
//                6 => "Property Name"
//                7 => "Flat Number"
//                8 => "Building Name"
//                9 => "Street Number"
//                10 => "Street Name"
//                11 => "Estate"
//                12 => "Town"
//                13 => "County"
//                14 => "Postcode"
//                15 => "Status"
//                16 => "Asset Class"
//                17 => "Asset Type"
//                18 => "Tenure Type"
//                19 => "Communal Area"
//                20 => "Responsibility"
//                21 => "Property Group"
//                22 => "Construction Age"
//                23 => "Number of Floors"
//                24 => "Property Access Type"
                $rows = $xlsx->rows();
//                    dd($rows);
                $is_begin = false;
                $data_property = [];
                $data_property_info = [];
                $data_property_survey = [];
                $service_area_dropdown =  ServiceArea::all();
                $all_zones = Zone::all();
                $asset_class_dropdown = AssetClass::where('is_deleted', '!=', 1)->get();
                $tenure_type_dropdown = TenureType::all();
                $communal_area_dropdown = CommunalArea::where('is_deleted', '!=', 1)->get();
                $responsibility_dropdown = Responsibility::all();
                $property_access_type_dropdown = PropertyProgrammeType::where('is_deleted', '!=', 1)->get();
                $count = 0;

                DB::beginTransaction();
                try{
                    foreach ($rows as $k => $row){
                        $count++;
                        if($is_begin){
                            $id = trim($row[1]) ? substr(trim($row[1]), 2) : 0;
                            $uprn = !empty($row[2]) ? trim($row[2]) : NULL;
                            $uprn = $uprn ? str_pad($uprn, 6, '0', STR_PAD_LEFT) : '';
                            $block_code = !empty($row[3]) ? trim($row[3]) : NULL;
                            $matches = [];
                            //^PL\d*$
                            $parent_id = NULL;
                            preg_match_all('/^PL\d*$/', trim($row[4]), $matches);
                            if(isset($matches[0][0])){
                                $parent_id = substr(trim($row[4]), 2);
                            } else {
                                $parent_id = trim($row[4]);
                                $parent = Property::where('name', trim($row[4]))->first();
                                if($parent){
                                    $parent_id = $parent->id;
                                }
                            }
//                            $parent_id = !empty($row[4]) ? substr(trim($row[4]), 2) : NULL;
                            $service_area = trim($row[5]); // be parent group also
                            $service_area_id = $service_area_dropdown->where('description', $service_area)->first()->id ?? NULL;
                            $parent_zone_id = $all_zones->where('zone_name' , $service_area)->where('parent_id' , 0)->first()->id ?? NULL;
                            $property_name = !empty($row[6]) ? trim($row[6]) : NULL;
                            //property info
                            $flat_number = !empty($row[7]) ? trim($row[7]) : NULL;
                            $building_name = !empty($row[8]) ? trim($row[8]) : NULL;
                            $street_number = !empty($row[9]) ? trim($row[9]) : NULL;
                            $street_name = !empty($row[10]) ? trim($row[10]) : NULL;
                            $estate = !empty($row[11]) ? trim($row[11]) : NULL;
                            $town = !empty($row[12]) ? trim($row[12]) : NULL;
                            $county = !empty($row[13]) ? trim($row[13]) : NULL;
                            $postcode = !empty($row[14]) ? trim($row[14]) : NULL;
                            $status = trim($row[15]) == 'Live' ? 0 : 1; // Live
                            $asset_class = trim($row[16]);// asset class will be parent of asset type
                            $asset_class_id = $asset_class_dropdown->where('description' , $asset_class)->where('parent_id', 0)->first()->id ?? NULL;
                            $asset_type = trim($row[17]);
                            $asset_type_id = $asset_class_dropdown->where('description' , $asset_type)->where('parent_id', $asset_class_id)->first()->id ?? NULL;
                            if(!$asset_type_id){
                                $asset_type_id = $asset_class_dropdown->where('description' , $asset_type)->where('parent_id', '!=', 0)->first()->id ?? NULL;
                                if(!$asset_class_id){
                                    $asset_class_id = $asset_class_dropdown->where('description' , $asset_type)->where('parent_id', '!=', 0)->first()->parent_id ?? NULL;
                                }
                            }
                            $tenure_type = trim($row[18]);
                            $tenure_type_id = $tenure_type_dropdown->where('description' , $tenure_type)->first()->id ?? NULL;
                            $communal_area = trim($row[19]);
                            $communal_area_id = $communal_area_dropdown->where('description' , $communal_area)->first()->id ?? NULL;
                            $responsibility = trim($row[20]);
                            $responsibility_id = $responsibility_dropdown->where('description' , $responsibility)->first()->id ?? NULL;
                            $zone = trim($row[21]);
                            $zone_id = $all_zones->where('zone_name' , $zone)->where('parent_id' , $parent_zone_id)->first()->id ?? NULL;
                            $construction_age = !empty($row[22]) ? trim($row[22]) : NULL;
                            $number_floors = !empty($row[23]) ? trim($row[23]) : NULL;
                            $property_access_type = trim($row[24]);
                            $property_access_type_id = $property_access_type_dropdown->where('description' , $property_access_type)->first()->id ?? NULL;
                            $property_access_type_other = $property_access_type && !$property_access_type_id ? $property_access_type : NULL ;
                            $property_access_type_id = $property_access_type_other ? 25 : $property_access_type_id;
                            $data_property[] = [
                                'id' => $id,
                                'property_reference' => $uprn,
                                'pblock' => $block_code,
                                'parent_id' => $parent_id,
                                'service_area_id' => $service_area_id,
                                'zone_id' => $zone_id,
                                'name' => $property_name,
                                'estate_code' => $estate,
                                'decommissioned' => $status,
                                'asset_class_id' => $asset_class_id,
                                'asset_type_id' => $asset_type_id,
                                'tenure_type_id' => $tenure_type_id,
                                'communal_area_id' => $communal_area_id,
                                'responsibility_id' => $responsibility_id
                            ];

                            $data_property_info[] = [
                                'property_id' => $id,
                                'flat_number' => $flat_number,
                                'building_name' => $building_name,
                                'street_number' => $street_number,
                                'street_name' => $street_name,
                                'town' => $town,
                                'address5' => $county,//county
                                'postcode' => $postcode
                            ];

                            $data_property_survey[] = [
                                'property_id' => $id,
                                'construction_age' => $construction_age,
                                'size_floors' => $number_floors,
                                'programme_type' => $property_access_type_id,
                                'programme_type_other' => $property_access_type_other,
                            ];
                        }

//                        if($count == 30){
//                            dd($data_property, $data_property_info, $data_property_survey);
//                        }

                        if($row[1] == "PL Reference"){ //exclude header
                            $is_begin = true;
                        }
                    }

                    if(count($data_property) > 0){
                        foreach ($data_property as $prop){
                            Property::where('id', $prop['id'])->update($prop);
                        }

                        foreach ($data_property_info as $pi){
                            PropertyInfo::where('property_id', $pi['property_id'])->update($pi);
                        }

                        foreach ($data_property_survey as $ps){
                            PropertySurvey::where('property_id', $ps['property_id'])->update($ps);
                        }
                        DB::commit();
                    }
                } catch (\Exception $ex){
                    DB::rollback();
                    dd($ex->getMessage());
                }
            } else {
                dd($xlsx->error());
            }
            dd('done');
        }
    }



    public function postUpdatePropertyTemplate(Request $request) {
        return \Storage::download('COW_Property Update (1).xlsx');
    }
}
