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
    public function index(){
        $start = SettingsModel::where('id',1)->get()->first()->start;
        $range = SettingsModel::where('id',1)->get()->first()->range;
        $this->_getDetails('269',$start,$range);
        $this->_getDetails('270',$start,$range);
        $this->_getDetails('271',$start,$range);
        SettingsModel::where('id',1)->update(['start'=>$start+$range]);
    }
   
    private function _getDetails($inspectionStatus,$start,$range){
        $inspectionStatusArray = ['269'=>'Booked','270'=>'To Be Scheduled','271'=>'Access Details Required'];
        $accessPersonTypeArray = [ "0" =>"N/A",
                                    "386" => "Other",
                                    "387" => "Builder",
                                    "388" => "Developer",
                                    "389" => "Tenant",
                                    "390" => "Property Manager",
                                    "391" => "Client"];
        $response = Http::acceptJson()->get('https://api.ontraport.com/1/Jobs',[
            'Api-Appid' => '2_97024_DalLz1gO5',
            'Api-Key' => 'xbEGYGGIBkMDn5H',
            'start' => $start,
            'range' => $range,
            'condition' => 'f2009='.$inspectionStatus,
            'listFields' => 'id,f2064//firstname,f2064//lastname,f2064//state,f2009,f2010,f2105,f2106,f2011'
        ]);
        
        $data =  $response['data'];
        $parentArray = Array();
        foreach($data as $element){
            $childArray = Array();
            $childArray['job_id'] = $element['id'];

            
            $childArray['client_f_name'] = (array_key_exists('f2064//firstname',$element))?$element['f2064//firstname']:"";
            $childArray['client_l_name'] = (array_key_exists('f2064//lastname',$element))?$element['f2064//lastname']:"";

            $childArray['inspection_status'] = $element['f2009'];

            $childArray['access_details'] = $element['f2010'];
            $childArray['access_person_type'] = $element['f2105'];



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

            if(array_key_exists('f2050',$element) && $element['f2050'] != 0){
                $propertyDetails =  Http::acceptJson()->get('https://api.ontraport.com/1/Property',[
                    'Api-Appid' => '2_97024_DalLz1gO5',
                    'Api-Key' => 'xbEGYGGIBkMDn5H',
                    'id' => $element['f2050'],
                    'listFields' => 'f2687'
                ]);
                $childArray['suburb'] = $propertyDetails['data']['f2687'];
                
            }else{
                $childArray['suburb'] = '';
            }
            $childArray['ontraport_link'] = "https://app.ontraport.com/#!/o_jobs10006/edit&id=".$element['id'];
            $childArray['date_of_inspection'] = $element['f2011'];

            array_push($parentArray,$childArray);

            /**
             * Database insert
             */
            // $opapi = new OPApi([
            //     'job_id' => $childArray['job_id'],
            //     'client_f_name' => $childArray['client_f_name'],
            //     'client_l_name' => $childArray['client_l_name'],
            //     'client_state' => $childArray['client_state'],
            //     'inspection_status' => $childArray['inspection_status'],
            //     'inspection_status_name' => $inspectionStatusArray[$childArray['inspection_status']],
            //     'access_details' => $childArray['access_details'],
            //     'access_person_type' => $childArray['access_person_type'],
            //     'access_person_type_name' => $accessPersonTypeArray[$childArray['access_person_type']],
            //     'access_person_f_name' => $childArray['access_person_f_name'],
            //     'access_person_l_name' => $childArray['access_person_l_name'],
            //     'access_person_sms' => $childArray['access_person_sms'],
            //     'access_person_email' => $childArray['access_person_email'],
            //     'ontraport_link' => $childArray['ontraport_link'],
            //     'date_of_inspection' => $childArray['date_of_inspection']
            // ]);
            //if(OpApi::where('job_id',$childArray['job_id'])->get()->count() == 0) $opapi->save();
        }
        print_r($parentArray);
    }
   
}
