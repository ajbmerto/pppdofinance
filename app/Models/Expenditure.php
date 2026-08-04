<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    //
	protected $table 	= "expenditures";
	protected $id 		= "expid";
	protected $fillable = [
		"fsrcidfk","name","fundvalue","startdate","enddate","expendituretype","qtr","created_at","updated_at"
	];
}
