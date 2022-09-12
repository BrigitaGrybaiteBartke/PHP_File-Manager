<?php

if(isset($_REQUEST['del'])) {
    $file = $_REQUEST['del'];
    unlink($file);
    header('Location: .');
}

?>