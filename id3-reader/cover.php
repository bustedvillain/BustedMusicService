<?php

/*
 * Creation Date: 22 de Enero de 2015
 * Autor: José Manuel Nieto Gómez
 * Objective: Read and print an image cover of a music File
 */

if ($_GET) {
    //Import lib
    require_once('../sources/libs/getid3/getid3.php');
    
    //Get the file
    $file = html_entity_decode(urldecode($_GET["file"]));
    
    //Initialize the lib
    $getID3 = new getID3;
    
#$getID3->option_tag_id3v2 = true; # Don't know what this does yet 
    $getID3->analyze($file);
    
    if (isset($getID3->info['id3v2']['APIC'][0]['data'])) {
        $cover = $getID3->info['id3v2']['APIC'][0]['data'];
    } elseif (isset($getID3->info['id3v2']['PIC'][0]['data'])) {
        $cover = $getID3->info['id3v2']['PIC'][0]['data'];
    } else {
        $cover = null;
    }
    if (isset($getID3->info['id3v2']['APIC'][0]['image_mime'])) {
        $mimetype = $getID3->info['id3v2']['APIC'][0]['image_mime'];
    } else {
        $mimetype = 'image/jpeg'; // or null; depends on your needs 
    }

    if (!is_null($cover)) {
// Send file 
        header("Content-Type: " . $mimetype);

        if (isset($getID3->info['id3v2']['APIC'][0]['image_bytes'])) {
            header("Content-Length: " . $getID3->info['id3v2']['APIC'][0]['image_bytes']);
        }

        echo($cover);
    }
} else {
    echo "No filepath received";
}

