<? php


    $file_name = "./tempSODP.sodp";
    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename=' . $file_name);
    header("Content-Length: " . filesize($file_name));

    $fp = fopen($file_name, "r");
    fpassthru($fp);
    fclose($fp);







?>
