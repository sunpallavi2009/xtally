<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\TallyGroup;
use App\Models\TallyLedger;
use App\Models\TallyCompany;
use App\Models\VoucherEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LedgerController extends Controller
{
    public function index()
    {
        return view('ledger.index');
    }

    public function upload(Request $request)
    {
        // Ensure file is present and valid
        $request->validate([
            'uploadFile' => 'required|file',
        ]);

        // Process the uploaded file
        $file = $request->file('uploadFile');
        $success = 0;

        $fp = fopen($file->getPathname(), 'rb');
        while (($line = fgets($fp)) !== false) {
            try {
                $record_json = json_decode($line, true);
                // Insert into database based on record type
                if ($record_json["t"] == "company") {
                    TallyCompany::updateOrCreate(
                        ['guid' => $record_json["guid"]],
                        [
                            'name' => $record_json["name"],
                            'address1' => $record_json["address1"],
                            'address2' => $record_json["address2"],
                            'fax_number' => $record_json["fax_number"],
                            'email' => $record_json["email"],
                            'mobile_number' => $record_json["mobile_number"],
                            'phone_number' => $record_json["phone_number"],
                            'website' => $record_json["website"],
                            'company_number' => $record_json["company_number"],
                        ]
                    );
                } elseif ($record_json["t"] == "group") {
                    TallyGroup::updateOrCreate(
                        ['guid' => $record_json["guid"]],
                        [
                            'name' => $record_json["name"],
                            'parent' => $record_json["parent"],
                            'alter_id' => $record_json["alterid"],
                        ]
                    );
                } elseif ($record_json["t"] == "l") {
                    TallyLedger::updateOrCreate(
                        ['guid' => $record_json["g"]],
                        [
                            'name' => $record_json["n"],
                            'alias1' => $record_json["a1"],
                            'alias2' => $record_json["a2"],
                            'parent' => $record_json["p"],
                            'address' => $record_json["a"],
                            'alter_id' => $record_json["ai"],
                            'note' => $record_json["nt"],
                            'primary_group' => $record_json["pg"],
                            'previous_year_balance' => $record_json["pb"],
                            'this_year_balance' => $record_json["tb"],
                            'email' => $record_json["e"],
                            'mobile' => $record_json["m"],
                            'phone' => $record_json["c"],
                            'xml' => json_encode((object)$record_json["x"]),
                        ]
                    );

                    // $xmlData = json_decode($record_json["x"], true);
                    $xmlData = $record_json["x"]; 


                    foreach ($xmlData as $voucher) {
                        // Extract voucher details
                        $voucherData = [
                            'ledger_guid' => $record_json["g"],
                            'voucher_number' => $voucher["VNO"],
                            'voucher_date' => $voucher["DATE"],
                            // Add other voucher details as needed
                        ];
    
                        // Store the voucher data
                        $newVoucher = Voucher::create($voucherData);
    
                        // Loop through voucher entries
                        foreach ($voucher["entries"] as $entry) {
                            // Extract voucher entry details
                            $voucherEntryData = [
                                'voucher_id' => $newVoucher->id,
                                'account' => $entry["ACC"],
                                'amount' => $entry["AMT"],
                                // Add other voucher entry details as needed
                            ];
    
                            // Store the voucher entry data
                            VoucherEntry::create($voucherEntryData);
                        }
                    }
                }
                $success++;
            } catch (\Exception $e) {
                // Handle exceptions
            }
        }
        fclose($fp);

        return $success;
    }
    


    // public function upload(Request $request)
    // {
    //     // Ensure file is present and valid
    //     $request->validate([
    //         'uploadFile' => 'required|file',
    //     ]);
    
    //     // Process the uploaded file
    //     $file = $request->file('uploadFile');
    //     $success = 0;
    
    //     $fp = fopen($file->getPathname(), 'rb');
    //     while (($line = fgets($fp)) !== false) {
    //         try {
    //             $record_json = json_decode($line, true);
    //             // Insert into database based on record type
    //             if ($record_json["t"] == "company") {
    //                 TallyCompany::updateOrCreate(
    //                     ['guid' => $record_json["guid"]],
    //                     [
    //                         'name' => $record_json["name"],
    //                         'address1' => $record_json["address1"],
    //                         'address2' => $record_json["address2"],
    //                         'fax_number' => $record_json["fax_number"],
    //                         'email' => $record_json["email"],
    //                         'mobile_number' => $record_json["mobile_number"],
    //                         'phone_number' => $record_json["phone_number"],
    //                         'website' => $record_json["website"],
    //                         'company_number' => $record_json["company_number"],
    //                     ]
    //                 );
    //             } elseif ($record_json["t"] == "group") {
    //                 TallyGroup::updateOrCreate(
    //                     ['guid' => $record_json["guid"]],
    //                     [
    //                         'name' => $record_json["name"],
    //                         'parent' => $record_json["parent"],
    //                         'alter_id' => $record_json["alterid"],
    //                     ]
    //                 );
    //             } elseif ($record_json["t"] == "l") {
    //                 $ledger = TallyLedger::updateOrCreate(
    //                     ['guid' => $record_json["g"]],
    //                     [
    //                         'name' => $record_json["n"],
    //                         'alias1' => $record_json["a1"],
    //                         'alias2' => $record_json["a2"],
    //                         'parent' => $record_json["p"],
    //                         'address' => $record_json["a"],
    //                         'alter_id' => $record_json["ai"],
    //                         'note' => $record_json["nt"],
    //                         'primary_group' => $record_json["pg"],
    //                         'previous_year_balance' => $record_json["pb"],
    //                         'this_year_balance' => $record_json["tb"],
    //                         'email' => $record_json["e"],
    //                         'mobile' => $record_json["m"],
    //                         'phone' => $record_json["c"],
    //                         'xml' => json_encode((object)$record_json["x"]),
    //                     ]
    //                 );
    
    //                 // Check if the ledger has XML data
    //                 if (isset($record_json["x"])) {
    //                     // Store voucher and voucher entries
    //                     foreach ($record_json["x"] as $voucher_data) {
    //                         $voucher = Voucher::create([
    //                             'type' => $voucher_data["TYPE"],
    //                             'created_by' => $voucher_data["DATE"],
    //                             'narration' => $voucher_data["NAR"],
    //                         ]);
                
    //                         // Associate voucher with ledger
    //                         $ledger->vouchers()->save($voucher);
                
    //                         // Create voucher entries
    //                         foreach ($voucher_data["BD"] as $bill_detail) {
    //                             VoucherEntry::create([
    //                                 'voucher_id' => $voucher->id,
    //                                 'ledger' => $bill_detail["ACC"], // Assuming this is the ledger associated with the entry
    //                                 'amount' => $bill_detail["AMT"],
    //                                 'entry_type' => $voucher_data["ITYPE"],
    //                             ]);
    //                         }
    //                     }
    //                 }


    //             }
    //             $success++;
    //         } catch (\Exception $e) {
    //             // Handle exceptions
    //             // Log or handle the exception appropriately
    //         }
    //     }
    //     fclose($fp);
    
    //     return $success;
    // }

//     public function upload(Request $request)
// {
//     // Ensure file is present and valid
//     $request->validate([
//         'uploadFile' => 'required|file',
//     ]);

//     // Process the uploaded file
//     $file = $request->file('uploadFile');
//     $success = 0;

//     $fp = fopen($file->getPathname(), 'rb');
//     while (($line = fgets($fp)) !== false) {
//         try {
//             $record_json = json_decode($line, true);
//             // Insert into database based on record type
//             if ($record_json["t"] == "company") {
//                 TallyCompany::updateOrCreate(
//                     ['guid' => $record_json["guid"]],
//                     [
//                         'name' => $record_json["name"],
//                         'address1' => $record_json["address1"],
//                         'address2' => $record_json["address2"],
//                         'fax_number' => $record_json["fax_number"],
//                         'email' => $record_json["email"],
//                         'mobile_number' => $record_json["mobile_number"],
//                         'phone_number' => $record_json["phone_number"],
//                         'website' => $record_json["website"],
//                         'company_number' => $record_json["company_number"],
//                     ]
//                 );
//             } elseif ($record_json["t"] == "group") {
//                 TallyGroup::updateOrCreate(
//                     ['guid' => $record_json["guid"]],
//                     [
//                         'name' => $record_json["name"],
//                         'parent' => $record_json["parent"],
//                         'alter_id' => $record_json["alterid"],
//                     ]
//                 );
//             } elseif ($record_json["t"] == "l") {
//                 TallyLedger::updateOrCreate(
//                     ['guid' => $record_json["g"]],
//                     [
//                         'name' => $record_json["n"],
//                         'alias1' => $record_json["a1"],
//                         'alias2' => $record_json["a2"],
//                         'parent' => $record_json["p"],
//                         'address' => $record_json["a"],
//                         'alter_id' => $record_json["ai"],
//                         'note' => $record_json["nt"],
//                         'primary_group' => $record_json["pg"],
//                         'previous_year_balance' => $record_json["pb"],
//                         'this_year_balance' => $record_json["tb"],
//                         'email' => $record_json["e"],
//                         'mobile' => $record_json["m"],
//                         'phone' => $record_json["c"],
//                         'xml' => json_encode((object)$record_json["x"]),
//                     ]
//                 );

//                 // Check if the ledger has XML data
//                 if (isset($record_json["x"])) {
//                     // Store voucher and voucher entries
//                     foreach ($record_json["x"] as $voucher_data) {
//                         $voucher = Voucher::create([
//                             'narration' => json_encode((object)$record_json["x"]),
//                         ]);

//                         // Create voucher entries
//                         foreach ($voucher_data as $entry_key => $entry_value) {
//                             // Check if the entry is a billing details array
//                             if ($entry_key == "x") {
//                                 // Process billing details and create voucher entries
//                                 foreach ($entry_value as $bill_detail) {
//                                     foreach ($bill_detail as $bill_key => $bill_value) {
//                                         VoucherEntry::create([
//                                             'ledger' => json_encode((object)$record_json["x"]), // Assuming amount is present in billing details
//                                         ]);
//                                     }
//                                 }
//                             } else {
//                                 // Create voucher entry
//                                 VoucherEntry::create([
//                                     'ledger' => json_encode((object)$record_json["x"]), // Assuming amount is present in the entry
//                                 ]);
//                             }
//                         }
//                     }
//                 }
//             }
//             $success++;
//         } catch (\Exception $e) {
//             // Handle exceptions
//             // Log or handle the exception appropriately
//         }
//     }
//     fclose($fp);

//     return $success;
// }


    // public function upload(Request $request)
    // {
    //     // Ensure file is present and valid
    //     $request->validate([
    //         'uploadFile' => 'required|file',
    //     ]);

    //     // Process the uploaded file
    //     $file = $request->file('uploadFile');
    //     $success = 0;

    //     $fp = fopen($file->getPathname(), 'rb');
    //     while (($line = fgets($fp)) !== false) {
    //         try {
    //             $record_json = json_decode($line, true);
    //             // Insert into database based on record type
    //             if ($record_json["t"] == "company") {
    //                 TallyCompany::updateOrCreate(
    //                     ['guid' => $record_json["guid"]],
    //                     [
    //                         'name' => $record_json["name"],
    //                         'address1' => $record_json["address1"],
    //                         'address2' => $record_json["address2"],
    //                         'fax_number' => $record_json["fax_number"],
    //                         'email' => $record_json["email"],
    //                         'mobile_number' => $record_json["mobile_number"],
    //                         'phone_number' => $record_json["phone_number"],
    //                         'website' => $record_json["website"],
    //                         'company_number' => $record_json["company_number"],
    //                     ]
    //                 );
    //             } elseif ($record_json["t"] == "group") {
    //                 TallyGroup::updateOrCreate(
    //                     ['guid' => $record_json["guid"]],
    //                     [
    //                         'name' => $record_json["name"],
    //                         'parent' => $record_json["parent"],
    //                         'alter_id' => $record_json["alterid"],
    //                     ]
    //                 );
    //             } elseif ($record_json["t"] == "l") {
    //                 $ledgerData = [
    //                     'name' => $record_json["n"],
    //                     'alias1' => $record_json["a1"],
    //                     'alias2' => $record_json["a2"],
    //                     'parent' => $record_json["p"],
    //                     'address' => $record_json["a"],
    //                     'alter_id' => $record_json["ai"],
    //                     'note' => $record_json["nt"],
    //                     'primary_group' => $record_json["pg"],
    //                     'previous_year_balance' => $record_json["pb"],
    //                     'this_year_balance' => $record_json["tb"],
    //                     'email' => $record_json["e"],
    //                     'mobile' => $record_json["m"],
    //                     'phone' => $record_json["c"],
    //                     'xml' => isset($record_json["x"]) ? json_encode($record_json["x"]) : null,
    //                 ];
    //                 TallyLedger::updateOrCreate(
    //                     ['guid' => $record_json["g"]],
    //                     $ledgerData
    //                 );

    //                 // Check if the ledger has XML data
    //                 if (isset($record_json["x"])) {
    //                     // Store voucher and voucher entries
    //                     foreach ($record_json["x"] as $voucher_data) {
    //                         $voucher = Voucher::create([
    //                             'type' => $voucher_data["TYPE"],
    //                             'amount' => $voucher_data["AMT"],
    //                             'created_by' => $voucher_data["DATE"],
    //                             'narration' => $voucher_data["NAR"],
    //                         ]);

    //                         // Create voucher entries
    //                         foreach ($voucher_data as $entry_key => $entry_value) {
    //                             // Check if the entry is a billing details array
    //                             if ($entry_key == "BD") {
    //                                 // Process billing details and create voucher entries
    //                                 foreach ($entry_value as $bill_detail) {
    //                                     foreach ($bill_detail as $bill_key => $bill_value) {
    //                                         VoucherEntry::create([
    //                                             'voucher_id' => $voucher->id,
    //                                             'ledger' => $bill_key,
    //                                             'amount' => $bill_value, 
    //                                             'entry_type' => $voucher_data["ITYPE"],// Assuming amount is present in billing details
    //                                         ]);
    //                                     }
    //                                 }
    //                             } else {
    //                                 // Create voucher entry
    //                                 VoucherEntry::create([
    //                                     'voucher_id' => $voucher->id,
    //                                     'ledger' => $entry_key,
    //                                     'amount' => $entry_value, // Assuming amount is present in the entry
    //                                 ]);
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //             $success++;
    //         } catch (\Exception $e) {
    //             // Handle exceptions
    //             // Log or handle the exception appropriately
    //         }
    //     }
    //     fclose($fp);

    //     return $success;
    // }

//       public function upload(Request $request)
// {
//     // Ensure file is present and valid
//     $request->validate([
//         'uploadFile' => 'required|file',
//     ]);

//     // Process the uploaded file
//     $file = $request->file('uploadFile');
//     $success = 0;

//     $fp = fopen($file->getPathname(), 'rb');
//     while (($line = fgets($fp)) !== false) {
//         try {
//             $record_json = json_decode($line, true);
//             // Insert into database based on record type
//             if ($record_json["t"] == "company") {
//                 TallyCompany::updateOrCreate(
//                     ['guid' => $record_json["guid"]],
//                     [
//                         'name' => $record_json["name"],
//                         'address1' => $record_json["address1"],
//                         'address2' => $record_json["address2"],
//                         'fax_number' => $record_json["fax_number"],
//                         'email' => $record_json["email"],
//                         'mobile_number' => $record_json["mobile_number"],
//                         'phone_number' => $record_json["phone_number"],
//                         'website' => $record_json["website"],
//                         'company_number' => $record_json["company_number"],
//                     ]
//                 );
//             } elseif ($record_json["t"] == "group") {
//                 TallyGroup::updateOrCreate(
//                     ['guid' => $record_json["guid"]],
//                     [
//                         'name' => $record_json["name"],
//                         'parent' => $record_json["parent"],
//                         'alter_id' => $record_json["alterid"],
//                     ]
//                 );
//             } elseif ($record_json["t"] == "l") {
//                 $ledgerData = [
//                     'name' => $record_json["n"],
//                     'alias1' => $record_json["a1"],
//                     'alias2' => $record_json["a2"],
//                     'parent' => $record_json["p"],
//                     'address' => $record_json["a"],
//                     'alter_id' => $record_json["ai"],
//                     'note' => $record_json["nt"],
//                     'primary_group' => $record_json["pg"],
//                     'previous_year_balance' => $record_json["pb"],
//                     'this_year_balance' => $record_json["tb"],
//                     'email' => $record_json["e"],
//                     'mobile' => $record_json["m"],
//                     'phone' => $record_json["c"],
//                     'xml' => isset($record_json["x"]) ? json_encode($record_json["x"]) : null,
//                 ];
//                 TallyLedger::updateOrCreate(
//                     ['guid' => $record_json["g"]],
//                     $ledgerData
//                 );

//                 // Check if the ledger has XML data
//                 if (isset($record_json["x"])) {
//                     // Store voucher and voucher entries
//                     foreach ($record_json["x"] as $voucher_data) {
//                         $voucher = Voucher::create([
//                             // Set voucher fields accordingly
//                         ]);

//                         // Create voucher entries
//                         foreach ($voucher_data as $entry_key => $entry_value) {
//                             // Check if the entry is a billing details array
//                             if ($entry_key == "BD") {
//                                 // Process billing details and create voucher entries
//                                 foreach ($entry_value as $bill_detail) {
//                                     foreach ($bill_detail as $bill_key => $bill_value) {
//                                         VoucherEntry::create([
//                                             'voucher_id' => $voucher->id,
//                                             'ledger' => $bill_key,
//                                         ]);
//                                     }
//                                 }
//                             } else {
//                                 // Create voucher entry
//                                 VoucherEntry::create([
//                                     'voucher_id' => $voucher->id,
//                                     'ledger' => $entry_key,
//                                 ]);
//                             }
//                         }
//                     }
//                 }
//             }
//             $success++;
//         } catch (\Exception $e) {
//             // Handle exceptions
//         }
//     }
//     fclose($fp);

//     return $success;
// }  

//     public function upload(Request $request)
// {
//     // Ensure file is present and valid
//     $request->validate([
//         'uploadFile' => 'required|file',
//     ]);

//     // Process the uploaded file
//     $file = $request->file('uploadFile');
//     $success = 0;

//     $fp = fopen($file->getPathname(), 'rb');
//     while (($line = fgets($fp)) !== false) {
//         try {
//             $record_json = json_decode($line, true);
//             // Insert into database based on record type
//             if ($record_json["t"] == "company") {
//                 TallyCompany::updateOrCreate(
//                     ['guid' => $record_json["guid"]],
//                     [
//                         'name' => $record_json["name"],
//                         'address1' => $record_json["address1"],
//                         'address2' => $record_json["address2"],
//                         'fax_number' => $record_json["fax_number"],
//                         'email' => $record_json["email"],
//                         'mobile_number' => $record_json["mobile_number"],
//                         'phone_number' => $record_json["phone_number"],
//                         'website' => $record_json["website"],
//                         'company_number' => $record_json["company_number"],
//                     ]
//                 );
//             } elseif ($record_json["t"] == "group") {
//                 TallyGroup::updateOrCreate(
//                     ['guid' => $record_json["guid"]],
//                     [
//                         'name' => $record_json["name"],
//                         'parent' => $record_json["parent"],
//                         'alter_id' => $record_json["alterid"],
//                     ]
//                 );
//             } elseif ($record_json["t"] == "l") {
//                 $ledgerData = [
//                     'name' => $record_json["n"],
//                     'alias1' => $record_json["a1"],
//                     'alias2' => $record_json["a2"],
//                     'parent' => $record_json["p"],
//                     'address' => $record_json["a"],
//                     'alter_id' => $record_json["ai"],
//                     'note' => $record_json["nt"],
//                     'primary_group' => $record_json["pg"],
//                     'previous_year_balance' => $record_json["pb"],
//                     'this_year_balance' => $record_json["tb"],
//                     'email' => $record_json["e"],
//                     'mobile' => $record_json["m"],
//                     'phone' => $record_json["c"],
//                     'xml' => isset($record_json["x"]) ? json_encode($record_json["x"]) : null,
//                 ];
//                 TallyLedger::updateOrCreate(
//                     ['guid' => $record_json["g"]],
//                     $ledgerData
//                 );

//                 // Check if the ledger has XML data
//                 if (isset($record_json["x"])) {
//                     // Store voucher and voucher entries
//                     foreach ($record_json["x"] as $voucher_data) {
//                         $voucher = Voucher::create([
//                             // Set voucher fields accordingly
//                         ]);

//                         // Create voucher entries
//                         foreach ($voucher_data as $entry_key => $entry_value) {
//                             // Check if the entry is a billing details array
//                             if ($entry_key == "BD") {
//                                 // Process billing details and create voucher entries
//                                 foreach ($entry_value as $bill_detail) {
//                                     foreach ($bill_detail as $bill_key => $bill_value) {
//                                         VoucherEntry::create([
//                                             'voucher_id' => $voucher->id,
//                                             'ledger' => $bill_key,
//                                         ]);
//                                     }
//                                 }
//                             } else {
//                                 // Create voucher entry
//                                 VoucherEntry::create([
//                                     'voucher_id' => $voucher->id,
//                                     'ledger' => $entry_key,
//                                 ]);
//                             }
//                         }
//                     }
//                 }
//             }
//             $success++;
//         } catch (\Exception $e) {
//             // Handle exceptions
//         }
//     }
//     fclose($fp);

//     return $success;
// }


}
