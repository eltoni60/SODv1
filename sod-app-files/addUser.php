<?php
    /*This code only saves the new user that was just signed up successfully*/
    $usersFile = fopen("users-config.json", 'r');
    $addedUserStr = file_get_contents("php://input");
    $existingUsersStr = fread($usersFile, filesize("users-config.json"));

    $jsonAddedUser = json_decode($addedUserStr, true);
    /*print_r($jsonAddedUser);*/
    $jsonExistingUsers = json_decode($existingUsersStr, true);
    /*print_r($jsonExistingUsers);*/

    array_push($jsonExistingUsers["loginCredentials"], $jsonAddedUser);

    $prettyString = json_encode($jsonExistingUsers, JSON_PRETTY_PRINT);
    $usersFile = fopen("users-config.json", "w");
    fwrite($usersFile, $prettyString);
	fclose($usersFile);

    /*This code creates the folder structure for the POSSD as specified in the RAD*/
    $sod = dirname(__FILE__, 2);
    mkdir($sod.'/POSSD-'.$jsonAddedUser["username"]);
    $filePaths = fopen($sod.'/POSSD-'.$jsonAddedUser["username"].'/'.$jsonAddedUser["username"].'-POSSD-filepaths.json', "w");
    $startWrite = '{"projects": []}';
    fwrite($filePaths, $startWrite);
    fclose($filePaths);
    echo 'Sign Up Complete. Please login.';
?>
