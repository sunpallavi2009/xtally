<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $ledgerGuid = $request->query('ledger_guid');
        $members = TallyLedger::where('guid', $ledgerGuid)->get();
        $societyGuid = explode('-', $ledgerGuid)[0]; 
        $society = TallyCompany::where('guid', 'like', "$societyGuid%")->first(); 
        return view('vouchers.index', compact('ledgerGuid','society','members'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $ledgerGuid = $request->query('ledger_guid');
            $vouchers = Voucher::where('ledger_guid', $ledgerGuid)->latest()->get();

            return DataTables::of($vouchers)
                ->addColumn('debit', function ($voucher) {
                    return $voucher->amount < 0 ? abs($voucher->amount) : '';
                })
                ->addColumn('credit', function ($voucher) {
                    return $voucher->amount >= 0 ? $voucher->amount : '';
                })
                ->addColumn('account_link', function ($voucher) {
                    return '<a href="' . route('voucherEntry.index', ['voucher_id' => $voucher->id]) . '">' . $voucher->credit_ledger . '</a>';
                })
                ->rawColumns(['account_link'])
                ->addIndexColumn()
                ->make(true);
        }
    }
}
