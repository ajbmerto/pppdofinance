<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DivbelongtoController extends Controller
{
    //
    protected $table    = "divbelongtos";
    protected $id       = "divbelongtoid";
    protected $fillable = [
        "empid","divid","usertype","created_at","updated_at"
    ];
}
