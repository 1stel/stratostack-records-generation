<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffActivity extends Model {

    //
    protected $table = 'staff_activity';
    protected $fillable = ['staff_id', 'ip_address', 'action'];

    public function staff()
    {
        $this->hasOne('App\User', 'id', 'staff_id');
    }
}
