<?php


    $stringy = file_get_contents("php://input");
    $stringyObj = json_decode($stringy, true);

    $possd = $stringyObj["POSSD"];
    $pName = $stringyObj["projectName"];
    $itemLibraryObject = $stringyObj["library"];

    $pathToItemLibraryJson = dirname(__DIR__)."/POSSD-".$possd."/project-".$pName."/".$pName."-item-library.json";

    $jsonFile = fopen($pathToItemLibraryJson, "w");
    fwrite($jsonFile, json_encode($itemLibraryObject, JSON_PRETTY_PRINT));
    fclose($jsonFile);


?>
