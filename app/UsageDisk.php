<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UsageDisk extends Model {

	//
	protected $table = 'usage_disk';
	protected $fillable = ['zoneId', 'accountId', 'domainId', 'volumeId', 'type', 'tags', 'usage', 'size', 'vmInstanceId', 'startDate', 'endDate'];

	public $timestamps = false;
}
