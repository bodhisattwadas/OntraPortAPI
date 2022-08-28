<?php

namespace App\Http\Controllers;

use App\Models\OPApi;
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
    
   
    public function index()
    {
        $response = Http::acceptJson()->get('https://api.ontraport.com/1/Jobs',[
            'Api-Appid' => '2_97024_DalLz1gO5',
            'Api-Key' => 'xbEGYGGIBkMDn5H',
            'start' => 1,
            'range' => 50,
            'condition' => 'f2009=269',
            'listFields' => 'id,f2064//firstname,f2064//lastname,f2009,f2010,f2105,f2106,f2011'
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
            $childArray['date_of_inspection'] = $element['f2011'];
            array_push($parentArray,$childArray);
        }
        return $parentArray;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOPApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOPApiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OPApi  $oPApi
     * @return \Illuminate\Http\Response
     */
    public function show(OPApi $oPApi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OPApi  $oPApi
     * @return \Illuminate\Http\Response
     */
    public function edit(OPApi $oPApi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOPApiRequest  $request
     * @param  \App\Models\OPApi  $oPApi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOPApiRequest $request, OPApi $oPApi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OPApi  $oPApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(OPApi $oPApi)
    {
        //
    }
}
