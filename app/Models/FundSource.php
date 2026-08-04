<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundSource extends Model
{
    //
	protected $table 	= "fund_sources";
	protected $id 		= "fsrcid";
	protected $fillable = [
		"fundname","fundvalue","yearactive","divisionid","created_at","updated_at"
	];
}
