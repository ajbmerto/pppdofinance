<?php

namespace App\Http\Controllers;

Use App\Models\FundSource;
Use App\Models\Expenditure;
Use App\Models\TheDivision;

use Illuminate\Http\Request;

use DB;
class TheDashboardController extends Controller
{
    //
	function index() {
		return view("dashboard.dashboard");
	}
	
	function getfunds() {
		$divisions 	= TheDivision::all();

		$totalmoney = FundSource::join('the_divisions', 'fund_sources.divisionid', '=', 'the_divisions.divid')
								->select(
								        'fund_sources.divisionid',
								        'the_divisions.divisionname',
								        DB::raw('SUM(fund_sources.fundvalue) as total')
								    )
							    ->groupBy('fund_sources.divisionid', 'the_divisions.divisionname')
							    ->get();

		$obligated  = Expenditure::join("fund_sources","expenditures.fsrcidfk","=","fund_sources.fsrcid")
								->join("the_divisions","fund_sources.divisionid","=","the_divisions.divid")
								->select(
									"the_divisions.divisionname",
									"the_divisions.divid",
									"fund_sources.fsrcid",
									DB::raw("SUM(expenditures.fundvalue) as obligated")
								)
								->groupBy("fund_sources.fsrcid","the_divisions.divisionname","the_divisions.divid")
								->where("expenditures.expendituretype","obligated")
								->get();

		$underproc  = Expenditure::join("fund_sources","expenditures.fsrcidfk","=","fund_sources.fsrcid")
								->join("the_divisions","fund_sources.divisionid","=","the_divisions.divid")
								->select(
									"the_divisions.divisionname",
									"the_divisions.divid",
									"fund_sources.fsrcid",
									DB::raw("SUM(expenditures.fundvalue) as underproc")
								)
								->groupBy("fund_sources.fsrcid","the_divisions.divisionname","the_divisions.divid")
								->where("expenditures.expendituretype","underproc")
								->get();

		// $div = "PRD";
		// return array_map(function($tm) use ($div) {
		// 	// return ($tm['divisionname'])?;
		// 	if ($tm['divisionname'] == "PRD") {
		// 		return "hello";
		// 	}
		// }, $totalmoney->toArray());

		// return response()->json($obligated);
		// return response()->json($this->returnthis($obligated,"divisionname","PFD"));

		// return response()->json($totalmoney);

		$budgetdata = [];
		foreach($divisions as $divs) {
			$div 				= $divs->divisionname;
			$budget = [
				"id"			=> $divs->divid,
				"name"  		=> $div,
				"begBudget"		=> $this->returnthis($totalmoney,"divisionname",$div)['total'] ?? 0,
				"obligations"	=> $this->returnthis($obligated,"divisionname",$div)['obligated'] ?? 0,
				"procurement"	=> $this->returnthis($underproc,"divisionname",$div)['underproc'] ?? 0
			];
			array_push($budgetdata, $budget);
		}

		return response()->json($budgetdata);

		/*
			let budgetData = [
				{ id: 1, name: "Infrastructure Modernization", begBudget: 1500000, obligations: 650000, procurement: 350000, remainingActivities: 400000 },
				{ id: 2, name: "Enterprise Software Licenses", begBudget: 800000, obligations: 500000, procurement: 150000, remainingActivities: 100000 },
				{ id: 3, name: "Research & Development Lab Tech", begBudget: 1200000, obligations: 400000, procurement: 500000, remainingActivities: 200000 },
				{ id: 4, name: "Information Security & Compliance", begBudget: 950000, obligations: 700000, procurement: 100000, remainingActivities: 120000 },
				{ id: 5, name: "Outreach & Marketing Campaign", begBudget: 600000, obligations: 200000, procurement: 500000, remainingActivities: 300000 }
			];
		*/
		
	}

	function returnthis($array,$from, $retwhat) {
		$array = $array->toArray();

		foreach($array as $ar) {
			if ($ar[$from] == $retwhat) {
				return $ar ?? 0;
			}
		}

		return 0;
	}

	function employeemgt() {
		return view("dashboard.employeemgt");
	}
}
