<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UsageVm extends Model
{

    //
    protected $fillable = ['zoneId', 'accountId', 'domainId', 'vm_name', 'usage', 'vmInstanceId', 'serviceOfferingId', 'templateId', 'cpuNumber', 'cpuSpeed', 'memory', 'startDate', 'endDate'];

    public $timestamps = false;
}
