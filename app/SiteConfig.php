<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model {

	//
    protected $table = 'site_config';
    protected $fillable = ['parameter', 'data'];
}
