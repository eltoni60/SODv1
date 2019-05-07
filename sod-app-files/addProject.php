<?php
/*If this is being called then we are assuming that there isn't a project
    of the name already*/
$addedProj = json_decode(file_get_contents("php://input"), true);
$POSSD = $addedProj["POSSD"];
$pName = $addedProj["pName"];

$sod = dirname(__DIR__);
$projDir = $sod."/possd-".$POSSD."/project-".$pName."/";
$dirSuccess = mkdir($projDir);

$filesToCreate = array($pName."-element-page-tracker.txt",
    $pName."-item-library.json", $pName."-project.json");

fclose(fopen($projDir.$filesToCreate[0], "w"));
fclose(fopen($projDir.$filesToCreate[1], "w"));
fclose(fopen($projDir.$filesToCreate[2], "w"));

$filePathP = $sod."/possd-".$POSSD."/".$POSSD."-POSSD-filepaths.json";+
$filePaths = fopen($filePathP, "r");
$pathsRead = fread($filePaths, filesize($filePathP));
fclose($filePaths);

$jsonPaths = json_decode($pathsRead, true);

array_push($jsonPaths["projects"], $pName);

$filePaths = fopen($filePathP, "w");
fwrite($filePaths, json_encode($jsonPaths, JSON_PRETTY_PRINT));
fclose($filePaths);

?>
