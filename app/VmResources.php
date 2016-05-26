<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class VmResources extends Model {

	//
    protected $fillable = ['vmInstanceId' , 'cpuNumber', 'cpuSpeed', 'memory'];
}
