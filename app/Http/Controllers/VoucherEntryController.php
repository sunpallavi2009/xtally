<?php

namespace App\Http\Controllers;

use App\Models\TallyLedger;
use App\Models\TallyCompany;
use App\Models\Voucher;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VoucherEntryController extends Controller
{
    // public function index(Request $request)
    // {
    //     $voucherId = $request->query('voucher_id');
    //     //$ledgerGuid = $request->query('ledger_guid');

    //     // $memberGuid = explode('-', $ledgerGuid)[0]; 
    //     // $members = TallyLedger::where('guid', 'like', "$ledgerGuid%")->first(); 
    //     //dd($members);

    //     $societyGuid = explode('-', $voucherId)[0]; 
    //     $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    //    // $society = TallyCompany::where('guid', 'like', "$voucherId%")->get();
    //     //dd($society);
    //     $vouchers = Voucher::where('id', $voucherId)->get();
    //     //dd($vouchers);

    //     return view('voucherentries.index', compact('voucherId', 'society','vouchers'));
    // }

    public function index(Request $request)
    {
        $voucherId = $request->query('voucher_id');
        $ledgerGuid = $request->query('ledger_guid');

        // Fetch the voucher entry based on the voucher_id
        $voucher = Voucher::findOrFail($voucherId);

        // Retrieve the ledger GUID associated with the voucher entry
        $ledgerGuid = $voucher->ledger_guid;

        // Use the ledger GUID to find the society associated with it
        $societyGuid = explode('-', $ledgerGuid)[0]; 
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();

        $members = TallyLedger::where('guid', 'like', "$ledgerGuid%")->first(); 
        $vouchers = Voucher::where('id', $voucherId)->get();

        // Pass the society name to the view
        return view('voucherentries.index', compact('voucherId','society','members','vouchers'));
    }



    // public function index(Request $request)
    // {
    //     $voucherId = $request->query('voucher_id');
    //     $ledgerGuid = $request->query('ledger_guid');
    //     //dd($ledgerGuid);

    //     $members = TallyLedger::where('guid', $ledgerGuid)->get();
    //     if ($members->isEmpty()) {
    //         logger()->warning('No members found for GUID', ['ledger_guid' => $ledgerGuid]);
    //     }

    //     $societyGuid = explode('-', $ledgerGuid)[0]; 
    //     $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();

    //     return view('voucherentries.index', compact('voucherId','society','members'));
    // }

    
    // public function index(Request $request)
    // {
    //     $voucherId = $request->query('voucher_id');
    //     $ledgerGuid = $request->query('ledger_guid');
    //     $members = TallyLedger::where('guid', $ledgerGuid)->get();
    //     $societyGuid = explode('-', $ledgerGuid)[0]; 
    //     $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    //     return view('voucherentries.index', compact('voucherId','society','members'));
    // }



    // public function index(Request $request)
    // {
    //     $voucherId = $request->query('voucher_id');
    //     // $ledgerGuid = TallyLedger::where('guid', $voucherId)->get();
       
    //     $ledgerGuid = $request->query('ledger_guid');
    //     $members = TallyLedger::where('guid', $ledgerGuid)->get();
    //     //dd($members);
    //     $societyGuid = explode('-', $ledgerGuid)[0]; 
    //     $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first(); 
    //     //dd($society);
    //     return view('voucherentries.index', compact('voucherId', 'society', 'members'));
    // }

    // public function index(Request $request)
    // {
    //     $voucherId = $request->query('voucher_id');
    //     $members = TallyLedger::where('guid', $voucherId)->get();
    //     $societyGuid = explode('-', $voucherId)[0]; 
    //     $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first();
    //     dd($society);
    //     return view('voucherentries.index', compact('voucherId', 'society', 'members'));
    // }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $voucherId = $request->query('voucher_id');
            $voucherEntries = VoucherEntry::where('voucher_id', $voucherId)->latest()->get();

            return DataTables::of($voucherEntries)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
