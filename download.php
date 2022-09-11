<?php
echo '<pre>';
var_dump($_GET['link']);

if ($_GET['link']) {
    $file = ('./' . $_GET['link']);
    $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, 0, 'utf-8'));


    if(file_exists($file)) {
        
    
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileToDownloadEscaped));
        
        ob_clean();
        flush();
        readfile($fileToDownloadEscaped);
        exit;
    }   
}
