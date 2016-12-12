<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirewallRule extends Model
{

    use SoftDeletes;
    //
    protected $fillable = ['reseller_id', 'src', 'src_cidr', 'dst_port', 'protocol', 'active'];
    protected $dates = ['deleted_at'];

    public function reseller()
    {
        return $this->belongsTo('App\Reseller');
    }
}
