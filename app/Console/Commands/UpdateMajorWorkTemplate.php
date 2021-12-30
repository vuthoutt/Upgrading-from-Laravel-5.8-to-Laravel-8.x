<?php

namespace App\Console\Commands;
use App\Models\Property;
use Illuminate\Console\Command;
use App\Export\CollectionExport;

class UpdateMajorWorkTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_major_template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $title = [
            'PL Reference','Property  UPRN', 'Block Code','Parent Reference','Service Area', 'Property Name',
            'Flat Number','Building Name', 'Street Number','Street Name','Estate Code', 'Town',
            'County','Postcode', 'Status','Asset Type','Tenure Type', 'Communal Area',
            'Responsibility','Property Group','Construction Age','Property Access Type'

            ];
            $properties =  Property::with('propertyInfo','propertySurvey','zone','communalArea','responsibility',
                                        'propertyType','serviceArea','assetType','tenureType','propertySurvey.propertyProgrammeType','parents'
                                    )->get();
            $data = [];
            foreach ($properties as $property) {
                $temp = [];
                if(isset($property->propertyType) && !$property->propertyType->isEmpty()){
                    foreach ($property->propertyType as $p_risk_type){
                        $temp[] = $p_risk_type->description;
                    }
                }
                if ($property->decommissioned == 0) {
                    $status = "Live";
                } else {
                    $status =  $property->decommissionedReason->description ?? "Property No Longer under Management";
                }
                $tmp = [
                    'reference' => $property->reference,
                    'uprn' => $property->property_reference,
                    'block_code' => $property->pblock,
                    'parent_ref' => $property->parents->name ?? '',
                    'serviceArea' => $property->serviceArea->description ?? '',
                    'name' => $property->name ?? '',
                    'flat_number' => $property->propertyInfo->flat_number ?? '',
                    'building_name' => $property->propertyInfo->building_name ?? '',
                    'street_number' =>  $property->propertyInfo->street_number ?? '',
                    'street_name' => $property->propertyInfo->street_name ?? '' ,
                    'estate_code' => $property->estate_code ?? '',
                    'town' => $property->propertyInfo->town ?? '',
                    'county' => $property->propertyInfo->address5 ?? '',
                    'postcode' => $property->propertyInfo->postcode ?? '',
                    'status' => $status,
                    'assetType' =>  $property->assetType->description ?? '',
                    'tenureType' => $property->tenureType->description ?? '',
                    'communalArea' => $property->communalArea->description ?? '',
                    'responsibility' => $property->responsibility->description ?? '',
                    'zone' =>  $property->zone->zone_name ?? '',
                    'construction_age' => $property->propertySurvey->construction_age ?? '',
                    'access_type' => $property->propertySurvey->propertyProgrammeType->description ?? '',
                ];

                $data[] = $tmp;
            }
            $fileName = 'Property_Information_Template.csv';
            if (\Storage::exists($fileName)) {
                \Storage::deleteDirectory($fileName);
            }
            return \Excel::store(new CollectionExport($data, $title), $fileName);
            // return \Excel::download(new CollectionExport($data, $title), $fileName);
        } catch (\Exception $e) {
            dd($e);
        }

    }
}
