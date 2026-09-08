<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\divbelongto;

class TheDivisionController extends Controller
{
    //

    function saveemp(Request $req) {
        $divb           = new divbelongto();
        $divb->empid    = $req->input("id");
        $divb->divid    = $req->input("divid");
        $divb->usertype = $req->input("rolesel");
        return response()->json($divb->save());
    }
}
