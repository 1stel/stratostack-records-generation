<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UsageGeneral extends Model {

	//
	protected $table = 'usage_general';
	protected $fillable = ['zoneId', 'accountId', 'domainId', 'type', 'usage', 'vmInstanceId', 'templateId', 'startDate', 'endDate'];

	public $timestamps = false;
}
