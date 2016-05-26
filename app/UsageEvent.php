<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UsageEvent extends Model {

	//
    protected $connection = 'cloud';
    protected $table = 'usage_event';

    public $timestamps = false;
}
