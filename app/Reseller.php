<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model {

    //
    protected $fillable = ['name', 'address', 'address2', 'city', 'state', 'zip', 'phone', 'email', 'domainid', 'apikey', 'portal_url'];

    public function firewallrules() {
        return $this->hasMany('App\FirewallRules');
    }
}
