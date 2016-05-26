<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceOffering extends Model {

	//
    protected $connection = 'cloud';
    protected $table = 'service_offering_view';

    public $timestamps = false;
}
