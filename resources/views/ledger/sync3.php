<?php
    error_reporting(0);
    // ini_set('display_errors', 1);
    include "connect.php";

    try {
        $log = Logger::getLogger(basename(__FILE__));// $log->info("Sync Called");
        // $log->info("File Name: " . $_FILES['uploadFile']['name']);
        $log->info("Sync Called <br >File Size: " . $_FILES['uploadFile']['size'] . " (" . formatSizeUnits($_FILES['uploadFile']['size']) . ")");
        if ($_FILES['uploadFile']['error'] != "0") {
            $log->info("File Error Status: " . $_FILES['uploadFile']['error'] . " (0 Expected)");
        }/*$phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );*/
        $success = 0;
        $fp = fopen($_FILES['uploadFile']['tmp_name'], 'rb');
        while (($line = fgets($fp)) !== false) {
            try {
                $record_json = json_decode($line, true);
                if ($record_json["t"] == "company") {
                    $sql = "INSERT INTO tally_company(`guid`, `name`,`address1`,`address2`,`fax_number`,`email`,`mobile_number`,`phone_number`,`website`,`company_number`) 
                        VALUES ('" . mysqli_real_escape_string($con, $record_json["guid"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["name"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["address1"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["address2"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["fax_number"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["email"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["mobile_number"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["phone_number"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["website"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["company_number"]) . "')
                         ON DUPLICATE KEY UPDATE
                        `updated_on` = now(),
                        `name` = '" . mysqli_real_escape_string($con, $record_json["name"]) . "',
                        `address1` = '" . mysqli_real_escape_string($con, $record_json["address1"]) . "',
                        `address2` = '" . mysqli_real_escape_string($con, $record_json["address2"]) . "',
                        `fax_number` = '" . mysqli_real_escape_string($con, $record_json["fax_number"]) . "',
                        `email` = '" . mysqli_real_escape_string($con, $record_json["email"]) . "',
                        `mobile_number` = '" . mysqli_real_escape_string($con, $record_json["mobile_number"]) . "',
                        `phone_number` = '" . mysqli_real_escape_string($con, $record_json["phone_number"]) . "',
                        `website` = '" . mysqli_real_escape_string($con, $record_json["website"]) . "',
                        `company_number` = '" . mysqli_real_escape_string($con, $record_json["company_number"]) . "'";
                    $log->info("Society: " . $record_json["name"]);
                }
                if ($record_json["t"] == "group") {
                    $sql = "INSERT INTO tally_groups(`guid`, `name`, `parent`,`alter_id`) 
                        VALUES ('" . mysqli_real_escape_string($con, $record_json["guid"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["name"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["parent"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["alterid"]) . "')
                         ON DUPLICATE KEY UPDATE
                        `updated_on` = now(),
                        `name` = '" . mysqli_real_escape_string($con, $record_json["name"]) . "',
                        `parent` = '" . mysqli_real_escape_string($con, $record_json["parent"]) . "',
                        `alter_id` = '" . mysqli_real_escape_string($con, $record_json["alterid"]) . "'";
                    // echo $sql;
                }
                if ($record_json["t"] == "l") {
                    $record_json["t"] = "ledger";
                    $sql = "INSERT INTO tally_ledgers(`guid`, `name`, `alias1`, `alias2`, `parent`,`address`,`alter_id`,`note`,`primary_group`,`previous_year_balance`,`this_year_balance`,`email`,`mobile`,`phone`,`xml`) 
                        VALUES ('" . mysqli_real_escape_string($con, $record_json["g"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["n"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["a1"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["a2"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["p"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["a"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["ai"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["nt"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["pg"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["pb"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["tb"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["e"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["m"]) . "',
                        '" . mysqli_real_escape_string($con, $record_json["c"]) . "',
                        '" . addslashes(json_encode((object)$record_json["x"])) . "')
                        ON DUPLICATE KEY UPDATE
                        `updated_on` = now(),
                        `name` = '" . mysqli_real_escape_string($con, $record_json["n"]) . "',
                        `alias1` = '" . mysqli_real_escape_string($con, $record_json["a1"]) . "',
                        `alias2` = '" . mysqli_real_escape_string($con, $record_json["a2"]) . "',
                        `parent` = '" . mysqli_real_escape_string($con, $record_json["p"]) . "',
                        `address` = '" . mysqli_real_escape_string($con, $record_json["a"]) . "',
                        `alter_id` = '" . mysqli_real_escape_string($con, $record_json["ai"]) . "',
                        `note` = '" . mysqli_real_escape_string($con, $record_json["nt"]) . "',
                        `primary_group` = '" . mysqli_real_escape_string($con, $record_json["pg"]) . "',
                        `previous_year_balance` = '" . mysqli_real_escape_string($con, $record_json["pb"]) . "',
                        `this_year_balance` = '" . mysqli_real_escape_string($con, $record_json["tb"]) . "',
                        `email` = '" . mysqli_real_escape_string($con, $record_json["e"]) . "',
                        `mobile` = '" . mysqli_real_escape_string($con, $record_json["m"]) . "',
                        `phone` = '" . mysqli_real_escape_string($con, $record_json["c"]) . "',
                        `xml` = '" . addslashes(json_encode((object)$record_json["x"])) . "'";
                    // echo $sql;
                }
                if (mysqli_multi_query($con, $sql)) {
                    //            echo "Success\n";
                    $success++;
                } else {
                    $log->info($sql . " " . mysqli_error($con));
                }
            } catch (Exception $e) {
                $log->info("Error Message: " . $e->getMessage());
            }
        }
        echo $success;
        $log->info("Total Records: " . $success);
        mysqli_close($con);
    } catch (Exception $e) {
        $log->info("Error Message: " . $e->getMessage());
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }