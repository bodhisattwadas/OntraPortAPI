<?php

namespace App\Http\Controllers;

use App\Models\OPApi;
use App\Models\SettingsModel;
use App\Http\Requests\StoreOPApiRequest;
use App\Http\Requests\UpdateOPApiRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OPApiController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function _fetchDetails(){
        $start = SettingsModel::where('id',1)->get()->first()->start;
        $range = SettingsModel::where('id',1)->get()->first()->range;
        $upperLimit = SettingsModel::where('id',1)->get()->first()->upperLimit;
       
        $this->_getDetails('269',$start,$range);
        $this->_getDetails('270',$start,$range);
        $this->_getDetails('271',$start,$range);
        $this->_getDetails('471',$start,$range);

        if($start<$upperLimit){
            SettingsModel::where('id',1)->update(['start'=>$start+$range]);
        }else{
            SettingsModel::where('id',1)->update(['start'=>1]);
        }
        $this->_updateInspectionStatus();
        
    }
    public function _updateInspectionStatus(){
        $inspectionStatusArray = [
            "0" => "Not available data",
            "266"=> "Canceled",
            "267"=> "Reschedule",
            "268"=> "Inspection Completed",
            "269"=> "Booked",
            "270"=> "To Be Scheduled",
            "271"=> "Access Details Required",
            "471"=> "Scheduled in Calendar",
            "473"=> "Not required",
            "553"=> "Removed from GCal",
            "563"=> "New Booking",
            "1313"=> "To Be Rescheduled"
        ];
        $start = SettingsModel::where('id',1)->get()->first()->startCheck;
        $range = SettingsModel::where('id',1)->get()->first()->rangeCheck;
        $end = OPApi::count();
        $listOfJobs = OPApi::where('id','>=',$start)
                    ->where('id','<=',$end)
                    ->limit($range)
                    ->pluck('job_id')->toArray();
       // Log::debug(implode(",",$listOfJobs));
        $response = Http::acceptJson()->get('https://api.ontraport.com/1/Jobs',[
            'Api-Appid' => '2_97024_DalLz1gO5',
            'Api-Key' => 'xbEGYGGIBkMDn5H',
            'ids' => implode(",",$listOfJobs),
            'listFields' => 'id,f2009'
        ]);
        
        $data =  $response['data'];
       // Log::debug($data);
        foreach($data as $element){
            if(in_array($element['id'],$listOfJobs)){
                Log::debug("Not deleted job : ".$element['id']);
                OPApi::where('job_id',$element['id'])
                    ->update(['inspection_status'=>$element['f2009'],'inspection_status_name' => $inspectionStatusArray[$element['f2009']]]);
                    // ->update([['inspection_status'=>$element['f2009']],['inspection_status_name' => $inspectionStatusArray[$element['f2009']]]]);
            }
            else{
                Log::debug("Deleted job : ".$element['id']);

                OPApi::where('job_id',$element['id'])
                    ->update(['inspection_status'=>'deleted']);
            };
        }
        if($start<$end){
            SettingsModel::where('id',1)->update(['startCheck'=>$start+$range]);
        }else{
            SettingsModel::where('id',1)->update(['startCheck'=>1]);
        }
    }
   
    private function _getDetails($inspectionStatus,$start,$range){
        // $inspectionStatusArray = [
        //                             '269'=>'Booked',
        //                             '270'=>'To Be Scheduled',
        //                             '271'=>'Access Details Required'
        //                         ]; 
        $inspectionStatusArray = [
                                    "0" => "Not available data",
                                    "266"=> "Canceled",
                                    "267"=> "Reschedule",
                                    "268"=> "Inspection Completed",
                                    "269"=> "Booked",
                                    "270"=> "To Be Scheduled",
                                    "271"=> "Access Details Required",
                                    "471"=> "Scheduled in Calendar",
                                    "473"=> "Not required",
                                    "553"=> "Removed from GCal",
                                    "563"=> "New Booking",
                                    "1313"=> "To Be Rescheduled"
                                ];
        $accessPersonTypeArray = [ 
                                    "0" =>"N/A",
                                    "386" => "Other",
                                    "387" => "Builder",
                                    "388" => "Developer",
                                    "389" => "Tenant",
                                    "390" => "Property Manager",
                                    "391" => "Client"
                                ];
        $stateCodeArray = [
                                    'na' => "",
                                    "706" => "NT",
                                    "707" =>  "TAS",
                                    "708" =>  "SA",
                                    "709" =>  "ACT",
                                    "710" =>  "QLD",
                                    "711" =>  "WA",
                                    "712" =>  "VIC",
                                    "713" =>  "NSW"
                                ];

        $jobStatusArray = [
            "336"=> "Report Sent",
            "337"=>"Waiting on Payment",
            "338"=>"In Progress",
            "339"=>"Pending Inspection",
            "340"=>"Waiting On Info",
            "341"=>"Pending Admin Review",
            "342"=>"On Hold",
            "477"=>"Send Report",
            "498"=>"Report Sent - Awaiting Payment",
            "517"=>"Info Submitted",
            "548"=>"New Booking",
            "586"=>"Cancelled"
        ] ;                     
        $jobTypeArray = [
            "254"=> "Tax Depreciation Schedule",
            "255"=> "Insurance Replacement Cost Estimate",
            "256"=> "Preliminary Cost Plan",
            "257"=> "Sinking Fund Forecast/Capital Works Fund",
            "258"=> "Construction Cost Estimating Services",
            "259"=> "Financier Cost Plan",
            "260"=> "Progress Claim",
            "261"=> "Council DA Report",
            "262"=> "Detailed Cost Plan",
            "263"=> "Expert Witness Report",
            "264"=> "Asset Register",
            "265"=> "Phase A Depreciation Estimate",
            "716"=> "Insurance Replacement Cost Plan",
            "1175"=> "Capital Gains Tax Estimate",
            "1201"=> "Development Application Cost Plan"
        ];
        $response = Http::acceptJson()->get('https://api.ontraport.com/1/Jobs',[
            'Api-Appid' => '2_97024_DalLz1gO5',
            'Api-Key' => 'xbEGYGGIBkMDn5H',
            'start' => $start,
            'range' => $range,
            'condition' => 'f2009='.$inspectionStatus,
            'listFields' => 'id,unique_id,f2064//firstname,f2064//lastname,f2064//state,f2009,f2010,f2105,f2106,f2011,f2050,f2006,f2021'
        ]);
        
        $data =  $response['data'];
        $parentArray = Array();
        foreach($data as $element){
            $childArray = Array();
            $childArray['job_id'] = $element['id'];
            $childArray['unique_id'] = (array_key_exists('unique_id',$element))?$element['unique_id']:"";

            
            $childArray['client_f_name'] = (array_key_exists('f2064//firstname',$element))?$element['f2064//firstname']:"";
            $childArray['client_l_name'] = (array_key_exists('f2064//lastname',$element))?$element['f2064//lastname']:"";

            $childArray['inspection_status'] = $element['f2009'];

            $childArray['access_details'] = $element['f2010'];
            $childArray['access_person_type'] = $element['f2105'];

            $childArray['job_type_id'] = $element['f2006'];
           // $childArray['job_type_name'] = $jobTypeArray[$element['f2006']];
            $childArray['job_type_name'] = (array_key_exists($element['f2006'],$jobTypeArray))?$jobTypeArray[$element['f2006']]:"Undefined job type ID";

            $childArray['job_status_id'] = $element['f2021'];
            //$childArray['job_status_name'] = $jobStatusArray[$element['f2021']];
            $childArray['job_status_name'] = (array_key_exists($element['f2021'],$jobStatusArray))?$jobStatusArray[$element['f2021']]:"Undefined job status ID";


            $childArray['access_person'] = $element['f2106'];
            if(array_key_exists('f2106',$element) && $element['f2106'] != 0){
                $personDetails =  Http::acceptJson()->get('https://api.ontraport.com/1/Contact',[
                    'Api-Appid' => '2_97024_DalLz1gO5',
                    'Api-Key' => 'xbEGYGGIBkMDn5H',
                    'id' => $element['f2106'],
                    'listFields' => 'firstname,lastname,sms_number,email'
                ]);
                
                $childArray['access_person_f_name'] = $personDetails['data']['firstname'];
                $childArray['access_person_l_name'] = $personDetails['data']['lastname'];
                $childArray['access_person_sms'] = $personDetails['data']['sms_number'];
                $childArray['access_person_email'] = $personDetails['data']['email'];
            }else{
                $childArray['access_person_f_name'] = "";
                $childArray['access_person_l_name'] = "";
                $childArray['access_person_sms'] = "";
                $childArray['access_person_email'] = "";
            }
           // Log::debug("PropertyDetails : ".$element['f2050']);

            
            /**
             * f2687 : suburb
             * f2044 : address-1
             * f2045 : address-2
             * f2139 : postal-code
             * f2051 : state
             * f2048 : country
             */
            if(array_key_exists('f2050',$element) && $element['f2050'] != 0){
                $propertyDetails =  Http::acceptJson()->get('https://api.ontraport.com/1/Property',[
                    'Api-Appid' => '2_97024_DalLz1gO5',
                    'Api-Key' => 'xbEGYGGIBkMDn5H',
                    'id' => $element['f2050'],
                    'listFields' => 'f2687,f2044,f2045,,f2046,f2139,f2051,f2048'
                ]);
                // Log::debug("SuburbsDetails : ".$propertyDetails['data']['f2687']);
                //  Log::debug("Suburb/Town : ".$propertyDetails['data']['f2046']);
                // Log::debug("address-1 : ".$propertyDetails['data']['f2044']);
                // Log::debug("address-2 : ".$propertyDetails['data']['f2045']);
                // Log::debug("postal-code : ".$propertyDetails['data']['f2139']);
                // Log::debug("state : ".$propertyDetails['data']['f2051']);
                // Log::debug("country : ".$propertyDetails['data']['f2048']);

                $childArray['suburb-town'] = $propertyDetails['data']['f2046'];
                $childArray['address-1'] = $propertyDetails['data']['f2044'];
                $childArray['address-2'] = $propertyDetails['data']['f2045'];
                $childArray['postal-code'] = $propertyDetails['data']['f2139'];
                $childArray['state'] = $propertyDetails['data']['f2051'];
                $childArray['country'] = $propertyDetails['data']['f2048'];


                if(array_key_exists('f2687',$propertyDetails['data']) && $propertyDetails['data']['f2687'] != 0){
                    $suburbDetails = Http::acceptJson()->get('https://api.ontraport.com/1/Suburb',[
                        'Api-Appid' => '2_97024_DalLz1gO5',
                        'Api-Key' => 'xbEGYGGIBkMDn5H',
                        'id' => $propertyDetails['data']['f2687'],
                        'listFields' => 'f2686,f3447,f3448,'
                    ]);
                    
                    
                    $childArray['suburb_lat'] = $suburbDetails['data']['f3447'];
                    $childArray['suburb_long'] = $suburbDetails['data']['f3448'];
                    $childArray['suburb_state'] = $suburbDetails['data']['f2686'];
                }else{
                    $childArray['suburb_lat'] = '';
                    $childArray['suburb_long'] = '';
                    $childArray['suburb_state'] = 'na';
                }
            }else{
                $childArray['suburb_lat'] = '';
                $childArray['suburb_long'] = '';
                $childArray['suburb_state'] = 'na';
            }
            $childArray['ontraport_link'] = "https://app.ontraport.com/#!/o_jobs10006/edit&id=".$element['id'];
            $childArray['date_of_inspection'] = $element['f2011'];
           // Log::debug($childArray);

            /**
             * Database insert
             */
            $opapi = OPApi::updateOrCreate(
                [
                    'job_id' => $childArray['job_id'],
                ],
                [
                'unique_id' => $childArray['unique_id'],
                'client_f_name' => $childArray['client_f_name'],
                'client_l_name' => $childArray['client_l_name'],

                'suburb_state' => (array_key_exists($childArray['suburb_state'],$stateCodeArray))?$stateCodeArray[$childArray['suburb_state']]:'',
                'suburb_lat' => $childArray['suburb_lat'],
                'suburb_long' => $childArray['suburb_long'],

                'inspection_status' => $childArray['inspection_status'],
                'inspection_status_name' => $inspectionStatusArray[$childArray['inspection_status']],

                'access_details' => $childArray['access_details'],
                'access_person_type' => $childArray['access_person_type'],
                'access_person_type_name' => $accessPersonTypeArray[$childArray['access_person_type']],
                'access_person_f_name' => $childArray['access_person_f_name'],
                'access_person_l_name' => $childArray['access_person_l_name'],
                'access_person_sms' => $childArray['access_person_sms'],
                'access_person_email' => $childArray['access_person_email'],
                'ontraport_link' => $childArray['ontraport_link'],
                'date_of_inspection' => $childArray['date_of_inspection'],

                'suburb-town'=> array_key_exists('suburb-town',$childArray)? $childArray['suburb-town']:'',
                'address-1'=> array_key_exists('address-1',$childArray)? $childArray['address-1']:'',
                'address-2'=> array_key_exists('address-2',$childArray)? $childArray['address-2']:'',
                'postal-code'=> array_key_exists('postal-code',$childArray)? $childArray['postal-code']:'',
                'state'=> array_key_exists('state',$childArray)? $childArray['state']:'',
                'country'=> array_key_exists('country',$childArray)? $childArray['country']:'',

                'job_type_id'=> array_key_exists('job_type_id',$childArray)? $childArray['job_type_id']:'',
                'job_type_name'=> array_key_exists('job_type_id',$childArray)? $childArray['job_type_name']:'',
                
                'job_status_id'=> array_key_exists('job_status_id',$childArray)? $childArray['job_status_id']:'',
                'job_status_name'=> array_key_exists('job_status_name',$childArray)? $childArray['job_status_name']:'',
                
                
            ]);
            //if(OpApi::where('job_id',$childArray['job_id'])->get()->count() == 0) $opapi->save();
        }
        //print_r($parentArray);
    }
   
}
