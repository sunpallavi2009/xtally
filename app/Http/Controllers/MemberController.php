<?php

namespace App\Http\Controllers;

use App\Models\TallyCompany;
use App\Models\TallyLedger;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $societyGuid = $request->query('guid');
        //dd($societyGuid);
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->get();
        //dd($society);
        return view('members.index', compact('society','societyGuid'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $societyGuid = $request->query('guid');

            $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();

            if (!$society) {
                return response()->json(['message' => 'Society not found'], 404);
            }

            $members = TallyLedger::where('guid', 'like', $society->guid . '%')
                ->whereNotNull('alias1')
                ->where('alias1', '!=', '')
                ->withCount('vouchers')
                ->with('vouchers')
                ->latest()
                ->get()
                ->map(function($member) {
                    $member->first_voucher_date = $member->firstVoucherDate();
                    return $member;
                });

            return DataTables::of($members)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
