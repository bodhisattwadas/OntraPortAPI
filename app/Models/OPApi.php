<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OPApi extends Model
{
    use HasFactory;
    protected $fillable = ['job_id','client_f_name','client_l_name','suburb_state','suburb_lat','suburb_long','inspection_status',
            'access_details','access_person_type','access_person_f_name','access_person_l_name','job_status_id','job_status_name',
            'access_person_sms','access_person_email','ontraport_link','date_of_inspection','inspection_status_name','access_person_type_name',
            'address-1','address-2','postal-code','state','country','job_type_id','job_type_name','suburb-town'];
}
