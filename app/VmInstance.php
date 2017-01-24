<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class VmInstance extends Model
{

    //
    protected $connection = 'cloud';
    protected $table = 'vm_instance';

    public function details()
    {
        return $this->hasMany('App\UserVmDetail', 'vm_id');
    }
}
