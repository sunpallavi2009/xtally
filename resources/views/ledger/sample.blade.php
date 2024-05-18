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
                }
                $success++;
            } catch (\Exception $e) {
                // Handle exceptions
            }
        }
        fclose($fp);

        return $success;
    }