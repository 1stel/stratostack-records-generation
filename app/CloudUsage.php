<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CloudUsage extends Model {

	//
	protected $connection = 'cloud_usage';
	protected $table = 'cloud_usage';

	public function scopeLike($query, $field, $value)
	{
        	return $query->where($field, 'LIKE', "%$value%");
	}

	public function scopeBillable($query) {

		// Setup billable records
                $billable = array(
                        2, // VM Allocated
                        4, // Network Sent
                        5, // Network Received
                        6, // Disk Utilization
                        9, // Snapshot Usage
                        11, // Load Balancer
                        12, // Port Forwarding
                        14  // VPN Usage
                );

		return $query->whereIn('usage_type', $billable);
	}
}
