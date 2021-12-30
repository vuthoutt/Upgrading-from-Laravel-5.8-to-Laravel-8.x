<?php

namespace App\Services\ShineCompliance;
use App\Models\ShineCompliance\AdminTool;
use App\Repositories\ShineCompliance\ComplianceSystemRepository;
use App\Repositories\ShineCompliance\ComplianceProgrammeRepository;
use Maatwebsite\Excel\Facades\Excel;

class AdminToolService
{
    private $programmeRepository;
    private $systemRepository;

    public function __construct(ComplianceProgrammeRepository $programmeRepository, ComplianceSystemRepository $systemRepository){
        $this->programmeRepository = $programmeRepository;
        $this->systemRepository = $systemRepository;
    }

    public function uploadProgramme($document) {

        $mime = $document->getClientMimeType();
        if ($mime == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $array = Excel::toArray([], $document);
            $array = $array[0] ?? [];
            // 0 => "Shine Reference"
            // 1 => "UPRN"
            // 2 => "Block Reference"
            // 3 => "Parent Reference"
            // 4 => "Property Name"
            // 5 => "System Reference"
            // 6 => "System Type"
            // 7 => "System Name"
            // 8 => "Programme Type"
            // 9 => "Programme Frequency"

            array_shift($array);
            if (count($array)) {
                try {
                    \DB::beginTransaction();
                    $data_create = [];
                    foreach ($array as $key => $value) {
                        $property_id = str_replace('PL', '', trim($value[0] ?? ''));
                        $system_id = str_replace('PS', '', trim($value[5] ?? ''));
                        $programme_name = trim($value[8] ?? '');
                        $inspection_period = trim($value[9] ?? 0);

                        $data_create[] = [
                            'property_id' => $property_id,
                            'system_id' => $system_id,
                            'name' => $programme_name,
                            'inspection_period' => $inspection_period,
                            'date_inspected' => time(),
                            'decommissioned' => 0,
                            'next_inspection' => time() + ($inspection_period * 86400),
                            'created_by' => \Auth::user()->id
                        ];
                        $new_programme = $this->programmeRepository->create($data_create);
                        $new_programme->reference = 'PT'. $new_programme->id;
                        $new_programme->save();

                        $comment = \Auth::user()->full_name . " added programme by toolbox " . $new_programme->name;
                        \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $new_programme->id, AUDIT_ACTION_ADD, $new_programme->reference, $new_programme->property_id, $comment);
                    }

                    \DB::commit();
                    return \ComplianceHelpers::successResponse('Uploaded Multiples Programmes Successfully!');
                } catch (\Exception $e) {
                    \DB::rollback();
                    return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
                }
            }
        }
         return \ComplianceHelpers::failResponse(STATUS_FAIL,'Incorrect Document Format. Please try again!');
    }

    public function uploadSystemAndProgramme($document) {
        $mime = $document->getClientMimeType();
        if ($mime == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $array = Excel::toArray([], $document);
            $array = $array[0] ?? [];

            // 0 => "Shine Reference"
            // 1 => "UPRN"
            // 2 => "Block Reference"
            // 3 => "Parent Reference"
            // 4 => "Property Name"
            // 5 => "System Type"
            // 6 => "System Name"
            // 7 => "Programme Type"
            // 8 => "Programme Frequency"

            array_shift($array);
            if (count($array)) {
                try {
                    \DB::beginTransaction();
                    $data_create = [];
                    $system_types = $this->systemRepository->getAllSystemType();
                    foreach ($array as $key => $value) {
                        $property_id = str_replace('PL', '', trim($value[0] ?? ''));
                        $system_type = trim($value[5] ?? '');
                        $system_name = trim($value[6] ?? '');
                        $programme_name = trim($value[7] ?? '');
                        $inspection_period = trim($value[8] ?? 0);

                        $system_type_id = $system_types->where('description', $system_type)->first()->id ?? null;
                        $data_create_system = [
                            'property_id' => $property_id,
                            'name' => $system_name,
                            'type' => $system_type_id,
                            'decommissioned' => 0,
                            'created_by' => \Auth::user()->id
                        ];
                        $system =  $this->systemRepository->create($data_create_system);

                        $system->reference = 'PS'. $system->id;
                        $system->save();

                        $comment = \Auth::user()->full_name . " added system by toolbox " . $system->name;
                        \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_ADD, $system->reference, $system->property_id, $comment);

                        $data_create = [
                            'property_id' => $property_id,
                            'system_id' => $system->id,
                            'name' => $programme_name,
                            'inspection_period' => $inspection_period,
                            'date_inspected' => time(),
                            'decommissioned' => 0,
                            'next_inspection' => time() + (intval($inspection_period) * 86400),
                            'created_by' => \Auth::user()->id
                        ];

                        $new_programme = $this->programmeRepository->create($data_create);
                        $new_programme->reference = 'PT'. $new_programme->id;
                        $new_programme->save();

                        $comment_pro = \Auth::user()->full_name . " added programme by toolbox " . $new_programme->name;
                        \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $new_programme->id, AUDIT_ACTION_ADD, $new_programme->reference, $new_programme->property_id, $comment_pro);
                    }
                    $this->programmeRepository->insert($data_create);

                    \DB::commit();
                    return \ComplianceHelpers::successResponse('Uploaded Multiples System and Programmes Successfully!');
                } catch (\Exception $e) {
                    \DB::rollback();
                    return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
                }
            }
        }
         return \ComplianceHelpers::failResponse(STATUS_FAIL,'Incorrect Document Format. Please try again!');
    }
}
