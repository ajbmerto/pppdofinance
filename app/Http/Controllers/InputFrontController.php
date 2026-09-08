<?php

namespace App\Http\Controllers;

Use App\Models\FundSource;
Use App\Models\Expenditure;
Use App\Models\TheDivision;
Use App\Models\divbelongto;

use Illuminate\Http\Request;
use Auth;

class InputFrontController extends Controller
{
    //
	function index() {
		$dbt 		= divbelongto::where("empid",Auth::id())->get(["divid"]);


		if (count($dbt) == 0 ) { die("no division found"); }

		$divs  		= TheDivision::where("divid",$dbt[0]->divid)->get("divisionname");

		$programs 	= FundSource::where("divisionid",$dbt[0]->divid)->get(["fundname","fsrcid","fundvalue"]);
		$total 		= $programs->sum('fundvalue');

		return view("front.input")->with(["programs" => $programs,
										  "divs" 	 => $divs,
										  "action" 	 => "enable",
										  "total"	 => $total]);
	}

	function view($div) {
		$programs 	= FundSource::where("divisionid",$div)->get(["fundname","fsrcid","fundvalue"]);
		$total 		= $programs->sum('fundvalue');
		$divs  		= TheDivision::where("divid",$div)->get("divisionname");

		return view("front.input")->with(["programs" => $programs,
										  "divs" 	 => $divs,
										  "action" 	 => "disable",
										  "total"	 => $total]);
	}

	function save(Request $req) {
		$action = $req->input("savebtn");

		if ($action == "save") {
			$ep 					= new Expenditure();
			$ep->fsrcidfk 			= $req->input("thebudgetline");
			$ep->name 				= $req->input("activityname");
			$ep->fundvalue 			= str_replace(",","",$req->input("input_value"));
			$ep->startdate  		= date("Y-m-d", strtotime($req->input("t_start")));
			$ep->enddate    		= date("Y-m-d", strtotime($req->input("t_end")));
			$ep->expendituretype    = explode("|", $req->input("status"))[0];
			$ep->expdeep 			= explode("|", $req->input("status"))[1];
			$ep->qtr 				= 1;
			$ep->save();
		} else if ($action == "update") {
			$ep = Expenditure::where("expid",$req->input("theidid"))
					->update([
					    'fsrcidfk'        => $req->input("thebudgetline"),
					    'name'            => $req->input("activityname"),
					    'fundvalue'       => str_replace(",", "", $req->input("input_value")),
					    'startdate'       => date("Y-m-d", strtotime($req->input("t_start"))),
					    'enddate'         => date("Y-m-d", strtotime($req->input("t_end"))),
					    'expendituretype' => explode("|", $req->input("status"))[0],
					    'expdeep'		  => explode("|", $req->input("status"))[1],
					    'qtr'             => 1,
					]);
		} else {
			die("can't proceed");
		}

		return redirect()->route("add");
	}

	function getdetails($theidid) {
		
		$dets 		= Expenditure::where("expid", $theidid)->get();

		return response()->json($dets);
		
		// document.getElementById("program-select").value             = 6;
		// document.getElementById("activity-name").value              = "hello world";
		// document.getElementById("timeline-start").value             = "1989-08-22";
		// document.getElementById("timeline-end").value               = "1925-12-08";
		// var input = document.getElementById("mask-amount").value    = "26236.00";
	}

	function removeitem($item) {
		$del 	= Expenditure::where(["expid"=>$item])->delete();

		if ($del) {
			return response()->json(1);
		}
		return response()->json(0);
	}

	function getActivityRecords($div) {
		$seldiv 				= $div;

		if (!is_numeric($div)) {
			$dbt 				= divbelongto::where("empid",Auth::id())->get(["divid"]);
			$seldiv 			= $dbt[0]->divid;
		}

		$activityrec 		= Expenditure::join("fund_sources", "expenditures.fsrcidfk","=","fund_sources.fsrcid")
											->select("expenditures.*","fund_sources.fundname")
											->where("fund_sources.divisionid",$seldiv)
											->get();

		$activityrecords 	= [];

		$status			 	= [
			"obligated"		 => "Obligated",
			"underproc" 	 => "Under Procurement",
			"Planning stage" => "Planning stage",
			"Pre-procurement" => "Pre-procurement",
			"undef"			 => "undefined"
		];

		$substat = [
			"Market Scoping" 		=> "Market Scoping",
			"Activity Design"		=> "Activity Design",
			"AWFP/APP/PPMP"			=> "AWFP/APP/PPMP",
			"abc"				    => "Approved Budget for the Contract",
			"pr"					=> "Purchase Request",
			"rfq"					=> "Request for Quotation",
			"posting"				=> "Posting",
			"bidding"				=> "Bidding",
			"bac reso"				=> "Bac Reso",
			"ntp/noa"				=> "Notice to Proceed/Award",
			"po"					=> "Purchase Request",
			"Activity done"			=> "Activity conducted",
			"liquidation done"		=> "Liquidation Completed"
		];

		foreach($activityrec as $ar) {
			$ar_var 		= [
				"id"		=> $ar->expid,
				"program"	=> $ar->fundname,
				"name"		=> $ar->name,
				"start"		=> date("Y-m-d",strtotime($ar->startdate)),
				"end"		=> date("Y-m-d",strtotime($ar->enddate)),
				"status"	=> $status[$ar->expendituretype],
				"substat"   => $substat[$ar->expdeep],
				"amount"	=> (int) $ar->fundvalue,
			];
			array_push($activityrecords,$ar_var);
		}

		return response()->json($activityrecords); 
	}

	function fundsource() {
		$divs = TheDivision::all();
		return view("dashboard.FundSource")->with(["division" => $divs]);
	}

	function savefund(Request $req) {

		$fs 			= new FundSource();
		$fs->divisionid = $req->input("divisionselect");
		$fs->fundname 	= $req->input("fundname");
		$fs->fundvalue  = $req->input("fundvalue");
		$fs->yearactive	= $req->input("yearactive");
		$fs->save();
		
		return redirect()->back();
	}
}
