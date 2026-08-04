<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class divbelongto extends Model
{
    //

    protected $table        = "divbelongtos";
    protected $id           = "divbelongtoid";
    protected $fillable     = [
        "empid","divid","usertype","created_at","updated_at"
    ];
}
