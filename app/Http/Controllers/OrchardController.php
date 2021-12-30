<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelpers;
use App\Models\Contact;
use App\Models\LogOrchardJob;
use App\Models\OrchardJob;
use App\Models\WorkRequest;
use App\Repositories\OrchardJobRepository;
use App\Repositories\ProjectRepository;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Illuminate\Http\Request;
//use GuzzleHttp\Psr7\Request;


class OrchardController extends Controller
{
    /**
     * request option of GuzzleHttp
     */
    private $option;

    public function __construct(OrchardJobRepository $orchardJobRepository, ProjectRepository $projectRepository){
        $this->orchardJobRepository = $orchardJobRepository;
        $this->projectRepository = $projectRepository;
        $this->option = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'proxy' => [
                'http'  => env('HTTP_PROXY'),
                'https' => env('HTTP_PROXY'),
            ],
            'exceptions' => false
        ];
    }

    public function api_no1(Request $request)
    {
        // TODO: api no 1
        $work_id = $request->work_id;
        $work_request = WorkRequest::find($work_id);
        if(!$work_request->project_id){
            //create project without due date
            $new_project_id = $this->projectRepository->createProjectWorkRequest($work_request, time(), $work_request->property, true);
            if($new_project_id){
                $work_request->update(['project_id'=>$new_project_id]);
            }
        }
        $job_id = $work_request->orchardJob->id ?? NULL;
        $user_code = $work_request->sorLogic->user_code ?? 'MANUAL' ;
        if($work_request){
            $client = new Client([
                'verify' => false
            ]);
            $orchard_url = env('API_ORCHARD_URL', 'https://orchard-shine-test.westminster.gov.uk');
            $url = $orchard_url ."/orchardWS/services/EDRPlusSOR_ITEMService";
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ser="http://www.orchard-systems.co.uk/services" xmlns:typ="http://www.orchard-systems.co.uk/types">
                   <soapenv:Header>
                      <oas:Security>
                         <!--Optional:-->
                         <oas:UsernameToken>
                            <!--Optional:-->
                            <oas:Username>SHINE_API</oas:Username>
                            <!--Optional:-->
                            <oas:Password>U2gxbjMxNjEy</oas:Password>
                         </oas:UsernameToken>
                      </oas:Security>
                   </soapenv:Header>
                   <soapenv:Body>
                      <ser:findPlusLOOKUP>
                         <ser:criteria>
                            <!--Optional:-->
                            <typ:ACTIVE>
                               <typ:operator>=</typ:operator>
                               <typ:value>true</typ:value>
                            </typ:ACTIVE>
                            <!--Optional:-->
                            <typ:USERCODE>
                               <typ:operator>=</typ:operator>
                               <typ:value>'.htmlspecialchars($user_code, ENT_XML1).'</typ:value>
                            </typ:USERCODE>
                            <!--Optional:-->
                            <typ:VOLUMECDE>
                               <typ:operator>=</typ:operator>
                               <typ:value>1</typ:value>
                            </typ:VOLUMECDE>
                         </ser:criteria>
                      </ser:findPlusLOOKUP>
                   </soapenv:Body>
                </soapenv:Envelope>';

            // configure options
            $options = $this->option;
            $options['body'] = $xml;

            try {

                $response = $client->request('POST', $url, $options);
                $contents = (string) $response->getBody();
                $status = $response->getStatusCode();

                $list = ['DESCRIPTION1', 'EXPENSETYPECODE', 'TRADECODE', 'PRIORITYCODE', 'SORTYPECODE', 'SORTYPECODE', 'SORNUM'];
                $check = CommonHelpers::checkXMLData($list, $contents);
                $description1 = CommonHelpers::regexXML('DESCRIPTION1', $contents);
                $expenseCode = CommonHelpers::regexXML('EXPENSETYPECODE', $contents);
                $tradeCode = CommonHelpers::regexXML('TRADECODE', $contents);
                $priorityCode = CommonHelpers::regexXML('PRIORITYCODE', $contents);
                $sorTypeCode = CommonHelpers::regexXML('SORTYPECODE', $contents);
                $volumeCDE = CommonHelpers::regexXML('VOLUMECDE', $contents);
                $sorNum = CommonHelpers::regexXML('SORNUM', $contents);
                $orchard_err_message = CommonHelpers::regexAttrXML('faultstring', $contents, $status);
                if($check == ""){
                    $error = FALSE;
                    $message_warning = "Success";
                }else{
                    $error = TRUE;
                    $message_warning = "SOR Not available, please contact asbestos team";
                }
                // TODO: log send and response request
                $data['work_id'] = $work_request->id ?? 0;
                $data['step'] = 1;
                $data['job_number'] = 0;
                $data['description1'] = $description1;
                $data['expense_code'] = $expenseCode;
                $data['trade_code'] = $tradeCode;
                $data['priority_code'] = $priorityCode;
                $data['sor_type_code'] = $sorTypeCode;
                $data['volume_cde'] = $volumeCDE;
                $data['sor_num'] = $sorNum;
                $data['department_code'] = '';
                $data['contract_number'] = '';
                $data['status_code'] = $status;
                $data['sent_request'] = $xml;
                $data['response_request'] = $contents;
                $job_return = $this->orchardJobRepository->updateOrCreateOrchardJob($data, $job_id);
                return  [
                    "error" => $error,
                    "message" => $message_warning,
                    "response" => $contents,
                    "description1" => $description1,
                    "expenseCode" => $expenseCode,
                    "tradeCode" => $tradeCode,
                    "priorityCode" => $priorityCode,
                    "sorTypeCode" => $sorTypeCode,
                    "volumeCDE" => $volumeCDE,
                    "sorNum" => $sorNum,
                    "orchard_err_message" => $orchard_err_message
                ];

//                dd($status, $response);
            } catch (\Exception $e){
                dd($e);
//                $err_message = $e->getMessage();
//                $status = 500;
                return  [
                    "error" => TRUE,
                    "message" => "SOR Not available, please contact asbestos team"
                ];
                // TODO: log error message
            }
        } else {
            // Todo return with message work request not found
            return  [
                "error" => TRUE,
                "message" => "SOR Not available, please contact asbestos team"
            ];
        }
    }

    public function api_no2(Request $request)
    {
        // TODO: api no 1
        //todo Contact Tel No needs to be truncated to 40
        $work_id = $request->work_id ?? 0;
        $work_request = WorkRequest::find($work_id);
        $job_id = $work_request->orchardJob->id ?? NULL;
        // dd($work_id, $work_request->id);
        $quantity = $work_request->sorLogic->price ?? ' ' ;
        $accessNote = $work_request->workScope->access_note ?? ' ' ;
        $workLocation = $work_request->workScope->location_note ?? ' ' ;
        $reportedBy = $work_request->workScope->reported_by ?? ' ' ;
        $external_job_no = $work_request->project->reference ?? ' ' ;
        // TODO GET CONTACT TEL NO FROM non user first, the first mobile in contact after
        $contactTelNo =  '';
        $contacts = $work_request->workContact;

        if(isset($work_request->non_user) && $work_request->non_user == 1 && ($contacts->mobile || $contacts->telephone)){
            $contactTelNo = $contacts->mobile ? $contacts->mobile : $contacts->telephone;
        }
        if(!$contactTelNo){
            for($i = 1; $i <= 10; $i++) {
                if (!is_null($contacts->{'team'.$i}) && $contacts->{'team'.$i} > 0) {
                    if (is_numeric($contacts->{'team'.$i})) {
                        $userID = $contacts->{'team'.$i};
                        $user_contact = Contact::where('user_id',$userID)->whereRaw(" (mobile != '' OR telephone != '') ")->first();
                        if($user_contact){
                            $contactTelNo = $user_contact->mobile ? $user_contact->mobile : $user_contact->telephone;
                            break;
                        }
                    }
                }
            }
        }
        if(!$contactTelNo){
            $contactTelNo = ' ';
        } else {
            $contactTelNo = substr($contactTelNo, 0, 40);
        }

        $property = $work_request->property;
        $property_ref = $property->property_reference;
        $description1 = $request->description1 ?? ' ';
        $expenseCode = $request->expenseCode ?? ' ';
        $tradeCode = $request->tradeCode ?? ' ';
        $jobPriorityCode = $request->priorityCode ?? ' ';
        $sorTypeCode = $request->sorTypeCode ?? ' ';
        $volumeCDE = $request->volumeCDE ?? ' ';
        $sorNum = $request->sorNum ?? ' ';
        $date = date("c");

        // HANDLE PRIORITY CODE
        $priorityCode = ($work_request->priority) ? $work_request->priority : $jobPriorityCode;
        if ($priorityCode == 25) {
            $priorityCode = 5;
        }elseif ($priorityCode >= 22){
            $priorityCode = $priorityCode - 22;
        }

        if($work_request){
            $client = new Client([
                'verify' => false
            ]);
            $orchard_url = env('API_ORCHARD_URL', 'https://orchard-shine-test.westminster.gov.uk');
            $url = $orchard_url ."/orchardWS/services/EDRPlusJOBService";
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ser="http://www.orchard-systems.co.uk/services" xmlns:typ="http://www.orchard-systems.co.uk/types">
                       <soapenv:Header>
                          <oas:Security>
                             <!--Optional:-->
                             <oas:UsernameToken>
                                <!--Optional:-->
                                <oas:Username>SHINE_API</oas:Username>
                                <!--Optional:-->
                                <oas:Password>U2gxbjMxNjEy</oas:Password>
                             </oas:UsernameToken>
                          </oas:Security>
                       </soapenv:Header>
                       <soapenv:Body>
                          <ser:add>
                             <ser:addAttributes>
                                <!--Optional:-->
                                <typ:ACCESSNOTE>' . htmlspecialchars($accessNote, ENT_XML1) . '</typ:ACCESSNOTE>
                                <!--Optional:-->
                                <typ:AUTO_SELECT_CONTRACT>true</typ:AUTO_SELECT_CONTRACT>
                                <!--Optional:-->
                                <typ:CONTACTTELNO>' . htmlspecialchars($contactTelNo, ENT_XML1) . '</typ:CONTACTTELNO>
                                <!--Optional:-->
                                <typ:DESCRIPTION1>' . $description1 . '</typ:DESCRIPTION1>
                                <!--Optional:-->
                                <typ:EXPENSECODE>' . htmlspecialchars($expenseCode, ENT_XML1) . '</typ:EXPENSECODE>
                                <!--Optional:-->
                                <typ:EXTERNALJOBNO>' . htmlspecialchars('SHINE-'.$external_job_no, ENT_XML1) . '</typ:EXTERNALJOBNO>
                                <!--Optional:-->
                                <typ:GAS_SERVICE_CONTINUE_ADD>true</typ:GAS_SERVICE_CONTINUE_ADD>
                                <!--Optional:-->
                                <typ:HOLD>true</typ:HOLD>
                                <!--Optional:-->
                                <typ:LOCATION>' . htmlspecialchars($workLocation, ENT_XML1) . '</typ:LOCATION>
                                <!--Optional:-->
                                <typ:PRINCIPALTRADE>' . htmlspecialchars($tradeCode, ENT_XML1) . '</typ:PRINCIPALTRADE>
                                <!--Optional:-->
                                <typ:PRIORITYCODE>' . htmlspecialchars($priorityCode, ENT_XML1) . '</typ:PRIORITYCODE>
                                <!--Optional:-->
                                <typ:PROPERTYSEQNO>'.htmlspecialchars($property_ref, ENT_XML1).'</typ:PROPERTYSEQNO>
                                <!--Optional:-->
                                <typ:REPORTEDBY>'.htmlspecialchars($reportedBy, ENT_XML1).'</typ:REPORTEDBY>
                                <!--Optional:-->
                                <typ:REPORTEDDATETIME>'.htmlspecialchars($date, ENT_XML1).'</typ:REPORTEDDATETIME>
                                <!--Optional:-->
                                <typ:SORTYPCDE>' . htmlspecialchars($sorTypeCode, ENT_XML1) . '</typ:SORTYPCDE>
                                <!--Optional:-->
                                <typ:SORVOLUMECODE>' . htmlspecialchars($volumeCDE, ENT_XML1) . '</typ:SORVOLUMECODE>
                                <!--Optional:-->
                                <typ:SOR_DUPLICATE_REASON>5</typ:SOR_DUPLICATE_REASON>
                                <!--Optional:-->
                                <typ:SOR_GUARANTEE_CONFIRM>true</typ:SOR_GUARANTEE_CONFIRM>
                                <!--Optional:-->
                                <typ:SOR_NUMBER>' . htmlspecialchars($sorNum, ENT_XML1) . '</typ:SOR_NUMBER>
                                <!--Optional:-->
                                <typ:SOR_ORDER_QTY>'.htmlspecialchars($quantity, ENT_XML1).'</typ:SOR_ORDER_QTY>
                             </ser:addAttributes>
                          </ser:add>
                       </soapenv:Body>
                    </soapenv:Envelope>';

            // configure options
            $options = $this->option;
            $options['body'] = $xml;

            try {

                $response = $client->request('POST', $url, $options);
                $contents = (string) $response->getBody();
                $status = $response->getStatusCode();

//                preg_match_all('/<JOBNUMBER(.*)">(.*)<\/JOBNUMBER>/', $contents, $matches);
                $job_number = CommonHelpers::regexXML('JOBNUMBER', $contents);

                $list = ['JOBNUMBER', 'DEPARTMENTCODE'];
                // dd($xml, $contents);
                $check = CommonHelpers::checkXMLData($list, $contents);
                $departmentCode = CommonHelpers::regexXML('DEPARTMENTCODE', $contents);
                $orchard_err_message = CommonHelpers::regexAttrXML('faultstring', $contents, $status);
                if($check == ""){
                    $error = FALSE;
                    $message_warning = "Success";
                }else{
                    $error = TRUE;
                    $message_warning = "Cannot create job record, please contact the Asbestos Team";
                }
                // TODO: log send and response request
                $data['step'] = 2;
                $data['job_number'] = $job_number;
                $data['department_code'] = $departmentCode;
                $data['status_code'] = $status;
                $data['sent_request'] = $xml;
                $data['response_request'] = $contents;
                $job_return = $this->orchardJobRepository->updateOrCreateOrchardJob($data, $job_id);

                return  [
                    "error" => $error,
                    "message" => $message_warning,
                    "job_number" => $job_number,
                    "response" => $contents,
                    "departmentCode" => $departmentCode,
                    'orchard_err_message' => $orchard_err_message
                ];

//                dd($status, $response);
            } catch (\Exception $e){
//                dd($e);
//                $err_message = $e->getMessage();
//                $status = 500;
                return  [
                    "error" => TRUE,
                    "message" => "Cannot create job record, please contact the Asbestos Team"
                ];
                // TODO: log error message
            }
        } else {
            // Todo return with message work request not found
            return  [
                "error" => TRUE,
                "message" => "Cannot create job record, please contact the Asbestos Team"
            ];
        }
    }

    public function api_no3(Request $request)
    {
        // TODO: api no 1
        $work_id = $request->work_id ?? 0;
        $work_request = WorkRequest::find($work_id);
        $job_id = $work_request->orchardJob->id ?? NULL;
        $job_number = $request->job_number ?? ' ';
        $departmentCode = $request->departmentCode ?? ' ';
        $extendedText = $work_request->workScope->scope_of_work ?? ' ';


        if($work_request){
            $client = new Client([
                'verify' => false
            ]);
            $orchard_url = env('API_ORCHARD_URL', 'https://orchard-shine-test.westminster.gov.uk');
            $url = $orchard_url ."/orchardWS/services/EDRPlusJOBTEXTService";
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ser="http://www.orchard-systems.co.uk/services" xmlns:typ="http://www.orchard-systems.co.uk/types">
                       <soapenv:Header>
                          <oas:Security>
                             <!--Optional:-->
                             <oas:UsernameToken>
                                <!--Optional:-->
                                <oas:Username>SHINE_API</oas:Username>
                                <!--Optional:-->
                                <oas:Password>U2gxbjMxNjEy</oas:Password>
                             </oas:UsernameToken>
                          </oas:Security>
                       </soapenv:Header>
                       <soapenv:Body>
                          <ser:add>
                             <ser:addAttributes>
                                <!--Optional:-->
                                <typ:DEPARTMENTCODE>' . htmlspecialchars($departmentCode, ENT_XML1) . '</typ:DEPARTMENTCODE>
                                <typ:JOBNUMBER>' . htmlspecialchars($job_number, ENT_XML1) . '</typ:JOBNUMBER>
                                <!--Optional:-->
                                <typ:TEXT>' . htmlspecialchars($extendedText, ENT_XML1) . '</typ:TEXT>
                             </ser:addAttributes>
                          </ser:add>
                       </soapenv:Body>
                    </soapenv:Envelope>';

            // configure options
            $options = $this->option;
            $options['body'] = $xml;

            try {

                $response = $client->request('POST', $url, $options);
                $contents = (string) $response->getBody();
                $status = $response->getStatusCode();

                $list = ['JOBNUMBER', 'DEPARTMENTCODE'];
                $check = CommonHelpers::checkXMLData($list, $contents);
                $departmentCode = CommonHelpers::regexXML('DEPARTMENTCODE', $contents);
                $orchard_err_message = CommonHelpers::regexAttrXML('faultstring', $contents, $status);
                if($check == ""){
                    $error = FALSE;
                    $message_warning = "Success";
                }else{
                    $error = TRUE;
                    $message_warning = "Cannot add extended text, please contact the Asbestos Team";
                }
                // TODO: log send and response request
                $data['step'] = 3;
                $data['job_number'] = $job_number;
                $data['department_code'] = $departmentCode;
                $data['status_code'] = $status;
                $data['sent_request'] = $xml;
                $data['response_request'] = $contents;
                $job_return = $this->orchardJobRepository->updateOrCreateOrchardJob($data, $job_id);

                return [
                    "error" => $error,
                    "message" => $message_warning,
                    "job_number" => $job_number,
                    "response" => $contents,
                    "departmentCode" => $departmentCode,
                    'orchard_err_message' => $orchard_err_message
                ];

//                dd($status, $response);
            } catch (\Exception $e){
//                dd($e);
                return  [
                    "error" => TRUE,
                    "message" => "Cannot add extended text, please contact the Asbestos Team"
                ];
            }
        } else {
            return  [
                "error" => TRUE,
                "message" => "Cannot add extended text, please contact the Asbestos Team"
            ];
        }
    }

    public function api_no4(Request $request)
    {
        // TODO: api no 1
        $work_id = $request->work_id ?? 0;
        $work_request = WorkRequest::find($work_id);
        $job_id = $work_request->orchardJob->id ?? NULL;
        $job_number = $request->job_number ?? ' ';
        $departmentCode = $request->departmentCode ?? ' ';


        if($work_request){
            $client = new Client([
                'verify' => false
            ]);
            $orchard_url = env('API_ORCHARD_URL', 'https://orchard-shine-test.westminster.gov.uk');
            $url = $orchard_url ."/orchardWS/services/EDRPlusJOB_CONTRACTService";
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ser="http://www.orchard-systems.co.uk/services" xmlns:typ="http://www.orchard-systems.co.uk/types">
                       <soapenv:Header>
                          <oas:Security>
                             <!--Optional:-->
                             <oas:UsernameToken>
                                <!--Optional:-->
                                 <oas:Username>SHINE_API</oas:Username>
                                <!--Optional:-->
                                <oas:Password>U2gxbjMxNjEy</oas:Password>
                             </oas:UsernameToken>
                          </oas:Security>
                       </soapenv:Header>
                       <soapenv:Body>
                          <ser:findPlusLOOKUP>
                             <ser:criteria>
                                <!--Optional:-->
                                <typ:AUTO_SELECT_CONTRACT>
                                   <typ:operator>=</typ:operator>
                                   <typ:value>true</typ:value>
                                </typ:AUTO_SELECT_CONTRACT>
                                <!--Optional:-->
                                <typ:DEPARTMENT_CODE>
                                   <typ:operator>=</typ:operator>
                                   <typ:value>' . htmlspecialchars($departmentCode, ENT_XML1) . '</typ:value>
                                </typ:DEPARTMENT_CODE>
                                <!--Optional:-->
                                <typ:JOB_NUMBER>
                                   <typ:operator>=</typ:operator>
                                   <typ:value>'.htmlspecialchars($job_number, ENT_XML1).'</typ:value>
                                </typ:JOB_NUMBER>
                                <!--Optional:-->
                                <typ:SHOW_VALID_CONTRACTS_FOR_JOB>
                                   <typ:operator>=</typ:operator>
                                   <typ:value>true</typ:value>
                                </typ:SHOW_VALID_CONTRACTS_FOR_JOB>
                             </ser:criteria>
                          </ser:findPlusLOOKUP>
                       </soapenv:Body>
                    </soapenv:Envelope>';

            // configure options
            $options = $this->option;
            $options['body'] = $xml;

            try {

                $response = $client->request('POST', $url, $options);
                $contents = (string) $response->getBody();
                $status = $response->getStatusCode();

                $list = ['CONTRACT_NUMBER'];
                $check = CommonHelpers::checkXMLData($list, $contents);
                $departmentCode = CommonHelpers::regexXML('DEPARTMENT_CODE', $contents);
                $contractNumber = CommonHelpers::regexXML('CONTRACT_NUMBER', $contents);
                $orchard_err_message = CommonHelpers::regexAttrXML('faultstring', $contents, $status);
                if($check == ""){
                    $error = FALSE;
                    $message_warning = "Success";
                }else{
                    $error = TRUE;
                    $message_warning = "Cannot determine contact required, please contact the Asbestos Team";
                }
                // TODO: log send and response request
                $data['step'] = 4;
                $data['department_code'] = $departmentCode;
                $data['contract_number'] = $contractNumber;
                $data['status_code'] = $status;
                $data['sent_request'] = $xml;
                $data['response_request'] = $contents;
                $job_return = $this->orchardJobRepository->updateOrCreateOrchardJob($data, $job_id);

                return [
                    "error" => $error,
                    "message" => $message_warning,
                    "job_number" => $job_number,
                    "response" => $contents,
                    "departmentCode" => $departmentCode,
                    "contractNumber" => $contractNumber,
                    'orchard_err_message' => $orchard_err_message
                ];

//                dd($status, $response);
            } catch (\Exception $e){
//                dd($e);
                return  [
                    "error" => TRUE,
                    "message" => "Cannot determine contact required, please contact the Asbestos Team"
                ];
            }
        } else {
            return  [
                "error" => TRUE,
                "message" => "Cannot determine contact required, please contact the Asbestos Team"
            ];
        }
    }

    public function api_no5(Request $request)
    {
        // TODO: api no 1
        $work_id = $request->work_id ?? 0;
        $work_request = WorkRequest::find($work_id);
        $job_id = $work_request->orchardJob->id ?? NULL;
        $job_number = $request->job_number ?? ' ';
        $departmentCode = $request->departmentCode ?? ' ';
        $contractNumber = $request->contractNumber ?? ' ';


        if($work_request){
            $client = new Client([
                'verify' => false
            ]);
            $orchard_url = env('API_ORCHARD_URL', 'https://orchard-shine-test.westminster.gov.uk');
            $url = $orchard_url ."/orchardWS/services/EDRPlusJOBService";
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ser="http://www.orchard-systems.co.uk/services" xmlns:typ="http://www.orchard-systems.co.uk/types">
                       <soapenv:Header>
                          <oas:Security>
                             <!--Optional:-->
                             <oas:UsernameToken>
                                <!--Optional:-->
                                <oas:Username>SHINE_API</oas:Username>
                                <!--Optional:-->
                                <oas:Password>U2gxbjMxNjEy</oas:Password>
                             </oas:UsernameToken>
                          </oas:Security>
                       </soapenv:Header>
                       <soapenv:Body>
                          <ser:findPlusCONTRACT>
                             <ser:criteria>
                               <!--Optional:-->
                                <typ:DEPARTMENTCODE>
                                   <typ:operator>=</typ:operator>
                                   <typ:value>' . htmlspecialchars($departmentCode, ENT_XML1) . '</typ:value>
                                   </typ:DEPARTMENTCODE>
                                <typ:JOBNUMBER>
                                   <typ:operator>=</typ:operator>
                                   <typ:value>'.htmlspecialchars($job_number, ENT_XML1).'</typ:value>
                                </typ:JOBNUMBER>
                             </ser:criteria>
                          </ser:findPlusCONTRACT>
                       </soapenv:Body>
                    </soapenv:Envelope>';

            // configure options
            $options = $this->option;
            $options['body'] = $xml;

            try {

                $response = $client->request('POST', $url, $options);
                $contents = (string) $response->getBody();
                $status = $response->getStatusCode();

                $matches = array();
//                preg_match_all('/<TIMESTAMP(.*)">(.*)<\/TIMESTAMP>/', $contents, $matches);

                $timestamp = CommonHelpers::regexXML('TIMESTAMP', $contents);
                $list = ['TIMESTAMP'];
                $check = CommonHelpers::checkXMLData($list, $contents);
                $orchard_err_message = CommonHelpers::regexAttrXML('faultstring', $contents, $status);
                if($check == ""){
                    $error = FALSE;
                    $message_warning = "Success";
                }else{
                    $error = TRUE;
                    $message_warning = "Cannot retrieve current timestamp, please contact the Asbestos Team";
                }
                // TODO: log send and response request
                $data['step'] = 5;
                $data['department_code'] = $departmentCode;
                $data['timestamp'] = $timestamp;
                $data['status_code'] = $status;
                $data['sent_request'] = $xml;
                $data['response_request'] = $contents;
                $job_return = $this->orchardJobRepository->updateOrCreateOrchardJob($data, $job_id);

                return [
                    "error" => $error,
                    "message" => $message_warning,
                    "job_number" => $job_number,
                    "timestamp" => $timestamp,
                    "response" => $contents,
                    "departmentCode" => $departmentCode,
                    'orchard_err_message' => $orchard_err_message
                ];

//                dd($status, $response);
            } catch (\Exception $e){
//                dd($e);
                return  [
                    "error" => TRUE,
                    "message" => "Cannot retrieve current timestamp, please contact the Asbestos Team"
                ];
            }
        } else {
            return  [
                "error" => TRUE,
                "message" => "Cannot retrieve current timestamp, please contact the Asbestos Team"
            ];
        }
    }

    public function api_no6(Request $request)
    {
        // TODO: api no 1
        $work_id = $request->work_id ?? 0;
        $work_request = WorkRequest::find($work_id);
        $job_id = $work_request->orchardJob->id ?? NULL;
        $job_number = $request->job_number ?? ' ';
        $timestamp = $request->timestamp ?? ' ';
        $departmentCode = $request->departmentCode ?? ' ';
        $contractNumber = $request->contractNumber ?? ' ';


        if($work_request){
            $client = new Client([
                'verify' => false
            ]);
            $orchard_url = env('API_ORCHARD_URL', 'https://orchard-shine-test.westminster.gov.uk');
            $url = $orchard_url ."/orchardWS/services/EDRPlusJOBService";
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ser="http://www.orchard-systems.co.uk/services" xmlns:typ="http://www.orchard-systems.co.uk/types">
                       <soapenv:Header>
                          <oas:Security>
                             <!--Optional:-->
                             <oas:UsernameToken>
                                <!--Optional:-->
                                <oas:Username>SHINE_API</oas:Username>
                                <!--Optional:-->
                                <oas:Password>U2gxbjMxNjEy</oas:Password>
                             </oas:UsernameToken>
                          </oas:Security>
                       </soapenv:Header>
                       <soapenv:Body>
                          <ser:modify>
                             <ser:primaryKey>
                                <typ:JOBNUMBER>'.htmlspecialchars($job_number, ENT_XML1).'</typ:JOBNUMBER>
                                <typ:DEPARTMENTCODE>' . htmlspecialchars($departmentCode, ENT_XML1) . '</typ:DEPARTMENTCODE>
                             </ser:primaryKey>
                             <ser:modifyAttributes>
                                <!--Optional:-->
                                <typ:CONTRACTNUMBER>' . htmlspecialchars($contractNumber, ENT_XML1) . '</typ:CONTRACTNUMBER>
                                <!--Optional:-->
                                <typ:CONF_SEL_CONT_VAL_JOB_EXCEEDED>true</typ:CONF_SEL_CONT_VAL_JOB_EXCEEDED>
                                <!--Optional:-->
                                <typ:HOLD>false</typ:HOLD>
                                <!--Optional:-->
                                <typ:ISSUE_WITHOUT_AN_APPOINT_DISP2>true</typ:ISSUE_WITHOUT_AN_APPOINT_DISP2>
                                <!--Optional:-->
                                <typ:TIMESTAMP>' . htmlspecialchars($timestamp, ENT_XML1) . '</typ:TIMESTAMP>
                             </ser:modifyAttributes>
                          </ser:modify>
                       </soapenv:Body>
                    </soapenv:Envelope>';

            // configure options
            $options = $this->option;
            $options['body'] = $xml;

            try {

                $response = $client->request('POST', $url, $options);
                $contents = (string) $response->getBody();
                $status = $response->getStatusCode();

//                $matches = array();
//                preg_match_all('/<JOBNUMBER(.*)">(.*)<\/JOBNUMBER>/', $contents, $matches);
                $job_number = CommonHelpers::regexXML('JOBNUMBER', $contents);
                $orchard_err_message = CommonHelpers::regexAttrXML('faultstring', $contents, $status);
                if($job_number){
                    $error = FALSE;
                    $message_warning = "Success";
                }else{
                    $error = TRUE;
                    $message_warning = "Unable to create job, please contact the Asbestos Team";
                }
                // TODO: log send and response request
                $data['step'] = 6;
                $data['status_code'] = $status;
                $data['sent_request'] = $xml;
                $data['response_request'] = $contents;
                $is_sucess = $error ? 0 : 1;
                $job_return = $this->orchardJobRepository->updateOrCreateOrchardJob($data, $job_id, $is_sucess);

                return [
                    "error" => $error,
                    "message" => $message_warning,
                    "response" => $contents,
                    "orchard_err_message" => $orchard_err_message
                ];

//                dd($status, $response);
            } catch (\Exception $e){
//                dd($e);
                return  [
                    "error" => TRUE,
                    "message" => "Unable to create job, please contact the Asbestos Team"
                ];
            }
        } else {
            return  [
                "error" => TRUE,
                "message" => "Unable to create job, please contact the Asbestos Team"
            ];
        }
    }
}
