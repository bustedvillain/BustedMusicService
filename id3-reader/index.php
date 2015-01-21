<?php

/*
 * Creation Date: 22/01/2014
 * Autor: Jose Manuel Nieto Gomez
 * Objective: Read files in the music folder, in order to process the files into the system
 */
//Get ID3 Library
//echo '<html><head>';
//echo '<title>getID3() - /demo/demo.simple.php (sample script)</title>';
//echo '<style type="text/css">BODY,TD,TH { font-family: sans-serif; font-size: 9pt; }</style>';
//echo '</head><body>';

require_once('../sources/libs/getid3/getid3.php');

// Initialize getID3 engine
$getID3 = new getID3;


$path = realpath('music/');

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

//echo '<table border="1" cellspacing="0" cellpadding="3">';
//echo '<tr><th>Filename</th><th>Artist</th><th>Title</th><th>Bitrate</th><th>Playtime</th></tr>';

foreach ($objects as $name => $object) {
    $extensionesPermitidas = array("mp3", "wav", "ogg");
    if (in_array(end(explode(".", $name)), $extensionesPermitidas)) {
        //$extension = end(explode(".", strtolower($name)));
        $ThisFileInfo = $getID3->analyze($name);
        getid3_lib::CopyTagsToComments($ThisFileInfo);

//        var_dump($ThisFileInfo);
//        echo '<tr>';
//        echo '<td>' . htmlentities($ThisFileInfo['filenamepath']) . '</td>';
//        echo '<td>' . htmlentities(!empty($ThisFileInfo['comments_html']['artist']) ? implode('<br>', $ThisFileInfo['comments_html']['artist']) : chr(160)) . '</td>';
//        echo '<td>' . htmlentities(!empty($ThisFileInfo['comments_html']['title']) ? implode('<br>', $ThisFileInfo['comments_html']['title']) : chr(160)) . '</td>';
//        echo '<td align="right">' . htmlentities(!empty($ThisFileInfo['audio']['bitrate']) ? round($ThisFileInfo['audio']['bitrate'] / 1000) . ' kbps' : chr(160)) . '</td>';
//        echo '<td align="right">' . htmlentities(!empty($ThisFileInfo['playtime_string']) ? $ThisFileInfo['playtime_string'] : chr(160)) . '</td>';
//        echo '</tr>';


        $artista = $ThisFileInfo["id3v2"]['comments']['artist'][0];
        $album = $ThisFileInfo["id3v2"]['comments']['album'][0];
        $genero = $ThisFileInfo["id3v2"]['comments']['genre'][0];
        $cancion = $ThisFileInfo["id3v2"]['comments']['title'][0];
        $duracion = $ThisFileInfo['playtime_string'];
        $bitrate = round($ThisFileInfo['audio']['bitrate'] / 1000);
        $formato = $ThisFileInfo['fileformat'];
        $peso = round($ThisFileInfo['filesize'] / 1024 / 1024, 3);
        $noPista = $ThisFileInfo['track_number'][0];
        $disquera = $ThisFileInfo["id3v2"]["publisher"][0];
        $anio = $ThisFileInfo["id3v2"]["year"][0];
        
        $name_url = urlencode(htmlentities($name));
        
        echo <<<fileinfo
            <br>
            <br><img src='cover.php?file=$name_url' width='200'>
            <br>Artist: $artista
            <br>Album: $album
            <br>Genero: $genero
            <br>Cancion: $cancion
            <br>Duracion: $duracion
            <br>Bitrate: $bitrate kbps
            <br>Formato: $formato
            <br>Peso: $peso mb
            <br>Numero de Pista: $noPista
            <br>Sello discografico: $disquera
            <br>Año: $anio
            <br>
               
fileinfo;
    }
}



//echo '</table>';
//
//echo '</body></html>';