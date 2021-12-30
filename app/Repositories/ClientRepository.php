<?php
namespace App\Repositories;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Client;
use App\Models\ClientInfo;
use App\Models\ClientAddress;
use App\User;
use App\Models\Department;
use App\Helpers\CommonHelpers;

class ClientRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Client::class;
    }

    /**
     * get Organizauin id
    * @return
     */
    public function myOrganisationId() {
        return $this->model->where('client_type',Client::$USER_ADMIN)->first('id');
    }
    /**
     * get property client list
     * @return
     */
    public function getPropertyClient() {
        return $this->model->select('id', 'name', 'reference')->whereIn('id', [Client::$USER_ADMIN, Client::$USER_OTHER])->get();
    }

    // get client by id with all relation
    public function getClient($client_id) {
        $client = Client::with('departments','clientInfo','clientAddress','mainUser','policy','traningRecord','users')->where('id', $client_id)->first();
        return is_null($client) ? [] : $client;
    }

    public function getAllClients() {
        $client = Client::all();
        return is_null($client) ? [] : $client;
    }
    /**
     * update organisation from request
     * @return
     */
    public function updateOrCreateContractor($data, $client_id = null, $client_type = 1) {

        $dataClient = [
            'name' => isset($data['name']) ? $data['name'] : '',
            'email' => isset($data['email']) ? $data['email'] : '',
            'email_notification' => isset($data['email_notification']) ? $data['email_notification'] : '',
            'key_contact' => isset($data['key_contact']) ? $data['key_contact'] : 0,
        ];

        $dataClientInfo = [
            'ukas_reference' => isset($data['ukas_reference']) ? $data['ukas_reference'] : '',
            'account_management_email' => isset($data['account_management_email']) ? $data['account_management_email'] : '',
            'type_surveying' => (isset($data['type_surveying'])) ? 1 : 0,
            'type_removal' => (isset($data['type_removal'])) ? 1 : 0,
            'type_demolition' => (isset($data['type_demolition'])) ? 1 : 0,
            'type_analytical' => (isset($data['type_analytical'])) ? 1 : 0,
            'type_fire_risk' => (isset($data['type_fire_risk'])) ? 1 : 0,
            'type_fire_equipment' => (isset($data['type_fire_equipment'])) ? 1 : 0,
            'type_fire_remedial' => (isset($data['type_fire_remedial'])) ? 1 : 0,
            'type_independent_survey' => (isset($data['type_independent_survey'])) ? 1 : 0,
            'type_legionella_risk' => (isset($data['type_legionella_risk'])) ? 1 : 0,
            'type_water_testing' => (isset($data['type_water_testing'])) ? 1 : 0,
            'type_water_remedial' => (isset($data['type_water_remedial'])) ? 1 : 0,
            'type_temperature' => (isset($data['type_temperature'])) ? 1 : 0,
            'type_hs_assessment' => (isset($data['type_hs_assessment'])) ? 1 : 0,
        ];

        $dataClientAddress = [
            'address1' => isset($data['address1']) ? $data['address1'] : '',
            'address2' => isset($data['address2']) ? $data['address2'] : '',
            'address3' => isset($data['address3']) ? $data['address3'] : '',
            'address4' => isset($data['address4']) ? $data['address4'] : '',
            'address5' => isset($data['address5']) ? $data['address5'] : '',
            'country' => isset($data['country']) ? $data['country'] : '',
            'postcode' => isset($data['postcode']) ? $data['postcode'] : '',
            'fax' => isset($data['fax']) ? $data['fax'] : '',
            'telephone' => isset($data['telephone']) ? $data['telephone'] : '',
            'mobile' => isset($data['mobile']) ? $data['mobile'] : '',
        ];

        try {
            if (is_null($client_id)) {
                $client = Client::create($dataClient);
                if($client) {
                    $client_id = $client->id;
                    $refClient = "CO" . $client_id;
                    $client_type = $client_type ?? 1;

                    Client::where('id', $client_id)->update(['reference' => $refClient, 'client_type' => $client_type]);
                }
                //update EMP
                // if (\CompliancePrivilege::checkPermission(ORGANISATION_EMP_VIEW_PRIV)) {
                    \CompliancePrivilege::setViewEMP(JR_ORGANISATION_EMP, $client_id, 'client');
                // }
                //update EMP
                // if (\CompliancePrivilege::checkUpdatePermission(ORGANISATION_EMP_UPDATE_PRIV)) {
                    \CompliancePrivilege::setUpdateEMP(JR_ORGANISATION_EMP, $client_id, 'client');
                // }
                $flag = 'create';

            } else {
                $client = Client::where('id',$client_id)->update($dataClient);
                $client = Client::find($client_id);
                $flag = 'update';
            }

            $clientInfoUpdate = ClientInfo::updateOrCreate(['client_id' => $client_id],$dataClientInfo);
            $clientAddressUpdate = ClientAddress::updateOrCreate(['client_id' => $client_id],$dataClientAddress);

            //save file
            if (isset($data['logo'])) {
                $saveLogo = \CommonHelpers::saveFileShineDocumentStorage($data['logo'], $client_id, CLIENT_LOGO);
            }
            if (isset($data['ukas'])) {
                $saveUkas = \CommonHelpers::saveFileShineDocumentStorage($data['ukas'], $client_id, UKAS_IMAGE);
                if ($saveUkas['status_code'] == 200) {
                    ClientInfo::where('client_id', $client_id)->update(['ukas' => $saveUkas['data']]);
                }
            }

            if ($flag == 'create') {
                if ($client_type) {
                    $msg = 'Created Client successfully !';
                     // log audit
                    \CommonHelpers::logAudit(CLIENT_TYPE, $client->id, AUDIT_ACTION_ADD, $refClient);
                } else {
                    $msg = 'Created contractor successfully !';
                     // log audit
                    \CommonHelpers::logAudit(CONTRACTOR_AUDIT_TYPE, $client->id, AUDIT_ACTION_ADD, $refClient);
                }
            } else {
                if (\CommonHelpers::checkArrayKey($data, 'type') == CONTRACTOR_TYPE) {
                    $msg = 'Updated contractor successfully !';
                    // log audit
                    \CommonHelpers::logAudit(CONTRACTOR_AUDIT_TYPE, $client->id, AUDIT_ACTION_EDIT, $client->reference);
                } elseif (\CommonHelpers::checkArrayKey($data, 'type') == CLIENT_TYPE) {
                    $msg = 'Updated client successfully !';
                    // log audit
                    \CommonHelpers::logAudit(CLIENT_TYPE, $client->id, AUDIT_ACTION_EDIT, $client->reference);
                } else {
                    $msg = 'Updated organisation successfully !';
                    // log audit
                    \CommonHelpers::logAudit(ORGANISATION_AUDIT_TYPE, $client->id, AUDIT_ACTION_EDIT, $client->reference);
                }
            }
            return CommonHelpers::successResponse($msg, $client_id);
        } catch (\Exception $e) {
            return CommonHelpers::failResponse(STATUS_FAIL,'Failed to update contractor. Please try again !');
        }
    }

    public function getDepartmentUsers($client_id, $department_id) {
        $user = User::with('userRole')->where('client_id', $client_id)->where('department_id', $department_id)->get();
        return is_null($user) ? [] : $user;
    }

    public static function getClientUsers($client_id){
        $user = User::where(['client_id'=> $client_id, 'is_locked' => Client::$USER_UNLOCKED])->orderBy('first_name','ASC')->get();
        return is_null($user) ? [] : $user;
    }

    public function searchClient($q){
        return $this->model->where('name','like',"%$q%")->orWhere('reference','like',"%$q%")->limit(LIMIT_SEARCH)->get();
    }

    public function getContractors(){
        return Client::where('client_type', 1)->get();
    }
}
