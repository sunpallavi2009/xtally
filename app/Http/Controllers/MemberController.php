<?php

namespace App\Http\Controllers;

use App\Models\TallyCompany;
use App\Models\TallyLedger;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        //dd($societyGuid);
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        return view('members.index', compact('society','societyGuid'));
    }

    
    
    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $societyGuid = $request->query('guid');
    //         // $partialGuid = explode('-', $societyGuid)[0]; // Extract the partial GUID
    
    //         // Debugging to check the value of $partialGuid
    //         dd($societyGuid);
    
    //         $members = TallyLedger::where('guid', 'like', "$societyGuid%") // Use the partial GUID in the query
    //             ->whereNotNull('alias1')
    //             ->where('alias1', '!=', '')
    //             ->latest()
    //             ->get();
    
    //         return DataTables::of($members)
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }
    

    
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');
            //dd($societyGuid);
            $members = TallyLedger::where('guid', 'like', "$societyGuid%")
                ->whereNotNull('alias1')
                ->where('alias1', '!=', '')
                ->latest()
                ->get();

            return DataTables::of($members)
                ->addIndexColumn()
                ->make(true);
        }
    }

    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $societyGuid = $request->query('guid');
    //         $members = TallyLedger::where('guid', $societyGuid)->whereNotNull('alias1')->where('alias1', '!=', '')->latest()->get();

    //         return DataTables::of($members)
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }
  
    // public function getData(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $members = TallyLedger::whereNotNull('alias1')
    //             ->where('alias1', '!=', '')
    //             ->withCount('vouchers') // Count the total vouchers
    //             ->latest()
    //             ->get();

    //         return DataTables::of($members)
    //             ->addColumn('total_vouchers', function ($member) {
    //                 return $member->vouchers_count;
    //             })
    //             ->addColumn('balance', function ($member) {
    //                 // Calculate the balance here based on your logic
    //                 // For example, if balance is a column in the TallyLedger table:
    //                 return $member->balance;
    //             })
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }
}
