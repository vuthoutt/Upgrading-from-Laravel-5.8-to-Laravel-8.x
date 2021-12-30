<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\ClientRepository;
use App\Repositories\ShineCompliance\ClientInfoRepository;
use App\Repositories\ShineCompliance\ClientAddressRepository;
use App\Repositories\ShineCompliance\TrainingRecordRepository;
use App\Repositories\ShineCompliance\UserRepository;

class ClientService{

    private $clientRepository;

    public function __construct(
        ClientRepository $clientRepository,
        ClientInfoRepository $clientInfoRepository,
        ClientAddressRepository $clientAddressRepository,
        UserRepository $userRepository,
        TrainingRecordRepository $trainingRecordRepository
    ){
        $this->clientRepository = $clientRepository;
        $this->clientInfoRepository = $clientInfoRepository;
        $this->clientAddressRepository = $clientAddressRepository;
        $this->userRepository = $userRepository;
        $this->trainingRecordRepository = $trainingRecordRepository;
    }

    public function getFindClient($client_id){
        return $this->clientRepository->getFindClient($client_id);
    }

    public function getClient($client_id){
        $relation = ['departments','clientInfo','clientAddress','mainUser','policy','traningRecord','users'];
        return $this->clientRepository->getClient($client_id,$relation);
    }

    public function updateOrCreateContractor($data, $client_id = null, $client_type = 1) {

        $dataClient = [
            'name' => isset($data['name']) ? $data['name'] : '',
            'email' => isset($data['email']) ? $data['email'] : '',
            'email_notification' => isset($data['email_notification']) ? $data['email_notification'] : '',
            'key_contact' => isset($data['key_contact']) ? $data['key_contact'] : 0,
        ];

        $dataClientInfo = [
            'contractor_setup_id' => isset($data['contractor_setup_id']) ? $data['contractor_setup_id'] : 0,
            'ukas_reference' => isset($data['ukas_reference']) ? $data['ukas_reference'] : '',
            'removal_licence_reference' => isset($data['removal_licence_reference']) ? $data['removal_licence_reference'] : '',
            'account_management_email' => isset($data['account_management_email']) ? $data['account_management_email'] : '',
            'type_surveying' => (isset($data['type_surveying'])) ? 1 : 0,
            'type_removal' => (isset($data['type_removal'])) ? 1 : 0,
            'type_demolition' => (isset($data['type_demolition'])) ? 1 : 0,
            'type_analytical' => (isset($data['type_analytical'])) ? 1 : 0,
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
            \DB::beginTransaction();
            if (is_null($client_id)) {
                $client =  $this->clientRepository->createClient($dataClient);
                if($client) {
                    $client_id = $client->id;
                    $refClient = "CO" . $client_id;
                    $client_type = $client_type ?? 1;
                    $data_update = ['reference' => $refClient, 'client_type' => $client_type];
                    $this->clientRepository->updateClient($client_id,$data_update);
                }
                //update EMP
                // if (\CompliancePrivilege::checkPermission(ORGANISATION_EMP_VIEW_PRIV)) {
                \CompliancePrivilege::setViewEMP(ORGANISATION_EMP_VIEW_PRIV, $client_id);
                // }
                //update EMP
                // if (\CompliancePrivilege::checkUpdatePermission(ORGANISATION_EMP_UPDATE_PRIV)) {
                \CompliancePrivilege::setUpdateEMP(ORGANISATION_EMP_UPDATE_PRIV, $client_id);
                // }
                $flag = 'create';

            } else {
                $this->clientRepository->updateClient($client_id,$dataClient);
                $client = $this->clientRepository->getFindClient($client_id);
                $flag = 'update';
            }


            $clientInfoUpdate = $this->clientInfoRepository->updateOrCreateClientInfo($client_id,$dataClientInfo);
            $clientAddressUpdate = $this->clientAddressRepository->updateOrCreateClientAddress($client_id,$dataClientAddress);

            //save file
            if (isset($data['logo'])) {
                $saveLogo = \CommonHelpers::saveFileShineDocumentStorage($data['logo'], $client_id, CLIENT_LOGO);
            }
            if (isset($data['ukas'])) {
                $saveUkas = \CommonHelpers::saveFileShineDocumentStorage($data['ukas'], $client_id, UKAS_IMAGE);
                if ($saveUkas['status_code'] == 200) {
                    $data =['ukas' => $saveUkas['data']];
                    $this->clientInfoRepository->updateClientInfo($client_id,$data);
                }
            }

            if ($flag == 'create') {
                if ($client_type) {
                    $msg = 'Created Client successfully !';
                    // log audit
                    \CommonHelpers::logAudit(CLIENT_TYPE, $client->id, AUDIT_ACTION_ADD, $refClient);
                } else {
                    $msg = 'Contractor Created Successfully!';
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
            \DB::commit();
            return \CommonHelpers::successResponse($msg, $client_id);
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to update contractor. Please try again !');
        }
    }

    public function getDepartmentUsers($client_id, $department_id) {
        $relation = ['userRole'];
        $user = $this->userRepository->getUserDepartment($relation,$client_id,$department_id);
        return is_null($user) ? [] : $user;
    }

    public function getClientUsers($client_id){
        return $this->userRepository->getClientUsers($client_id);
    }

    public function getClientUsersAssessment($client_id){
        return $this->userRepository->getClientUsersAssessment($client_id);
    }

    public function getClientAssessment($client_id){
        return $this->userRepository->getClientAssessment($client_id);
    }

    public function getClientLeadsAssessment($client_id){
        return $this->userRepository->getClientLeadsAssessment($client_id);
    }

    public function getClientPrivilege(){
        return $this->clientRepository->getClientPrivilege();
    }

    public function getTraningRecord($view_client_list){
        return $this->trainingRecordRepository->getClientPrivilege($view_client_list);
    }

    public function getAllClients(){
        $clients =  $this->clientRepository->getAllClients();
        return is_null($clients) ? [] : $clients;
    }

    public function getClientUsersCO1($client_id){
        $id_CO1 = $this->clientRepository->getClientCO1();
        $id_CO1 = Client::where('reference' , 'CO1')->first()->id;
        if($client_id == $id_CO1){
            $users = $this->clientRepository->getClientUsers($client_id);
        } else{
            $users = $this->clientRepository->getClientUsers($client_id);
        }
    }

    public function getAllClientByListId($client_ids, $limit, $query) {
        return $this->clientRepository->getAllClientByListId($client_ids, $limit, $query);
    }

    public function getAllClientsWithRelations($relations = []){

        $clients_list = $this->clientRepository->getAllClientsWithRelations($relations);
        $clients = $clients_list->where('id',1);//co1
        $clients_contractor = $clients_list->where('client_type','=',1);//contractor
        $clients_other = $clients_list->where('client_type','=',2);//other_client
        $client_properties = $clients_list->whereIn('client_type',[0,2]);//client with properties
        return [
            'clients_list' => $clients_list,
            'clients' => $clients,
            'clients_contractor' => $clients_contractor,
            'clients_other' => $clients_other,
            'client_properties' => $client_properties
        ];
    }

}
