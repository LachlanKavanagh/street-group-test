<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileUploadController extends Controller {
    public function getUploadForm(){
        return view('welcome');
    }

    public function parse_data(Request $request){
        $request->validate([
            'csv' => 'required|mimes:csv',
        ]);

        // loop through csv & create final formatted array
        $data = array();
        $fileContents = $request->csv->get();
        $fileContentsArray = explode(",",$fileContents);
        $header = null;

        $multiple_homeowner_strings = array('and', '&');

        foreach($fileContentsArray as $entry){
            if(!$header){
                $header = $entry;
                continue;
            }
            $output_entry = array();
            $output_entry2 = array();
            $output_entry['title'] = 'null';
            $output_entry['first_name'] = 'null';
            $output_entry['initial'] = 'null';
            $output_entry['last_name'] = 'null';
            $entry_exploded = explode(" ", $entry);
            // all entries that fall into here are entries containing a single homeowner
            if(sizeof($entry_exploded) == 3){
                $output_entry['title'] = $entry_exploded[0];
                if(strlen(str_replace('.', '', $entry_exploded[1])) == 1){
                    $output_entry['initial'] = $entry_exploded[1];
                }
                else{
                    $output_entry['first_name'] = $entry_exploded[1];
                }
                $output_entry['last_name'] = $entry_exploded[2];
            }
            // all entries that fall into here contain more than 1 homeowner
            else{                
                $output_entry2['title'] = 'null';
                $output_entry2['first_name'] = 'null';
                $output_entry2['initial'] = 'null';
                $output_entry2['last_name'] = 'null';
                // if 2nd word is &/and, assume two homeowners share everything apart from title
                // else, treat as 2 full entries in same row
                if(in_array($entry_exploded[1], $multiple_homeowner_strings)){
                    $output_entry['title'] = $entry_exploded[0];
                    $output_entry2['title'] = $entry_exploded[2];
                    // handles either just a last name being present, or fn/ln
                    if(4 == sizeof($entry_exploded)){
                        $output_entry['last_name'] = $entry_exploded[3];
                        $output_entry2['last_name'] = $entry_exploded[3];
                    }
                    else{
                        $output_entry['first_name'] = $entry_exploded[3];
                        $output_entry2['first_name'] = $entry_exploded[3];
                        $output_entry['last_name'] = $entry_exploded[4];
                        $output_entry2['last_name'] = $entry_exploded[4];
                    }
                }
                else{
                    $output_entry['title'] = $entry_exploded[0];
                    if(strlen(str_replace('.', '', $entry_exploded[1])) == 1){
                        $output_entry['initial'] = $entry_exploded[1];
                    }
                    else{
                        $output_entry['first_name'] = $entry_exploded[1];
                    }
                    $output_entry['last_name'] = $entry_exploded[2];

                    $output_entry2['title'] = $entry_exploded[4];
                    if(strlen(str_replace('.', '', $entry_exploded[5])) == 1){
                        $output_entry2['initial'] = $entry_exploded[5];
                    }
                    else{
                        $output_entry2['first_name'] = $entry_exploded[5];
                    }
                        $output_entry2['last_name'] = $entry_exploded[6];
                }
            }
            //$output_entry['title'] = $entry_exploded[0];
            $output_array[] = $output_entry;
            if(!empty($output_entry2)){
                $output_array[] = $output_entry2;
            }
        }
        
        return back()
            ->with('success',$output_array);
    }
}