<?php
    /*This code only saves the new user that was just signed up successfully*/
    $usersFile = fopen("users-config.json", 'r');
    $existingUsersStr = fread($usersFile, filesize("users-config.json"));
    $addedUserStr = file_get_contents("php://input");

	// the format I am getting for the body of the request is:
	// m_username=user&m_password=password&m_password1=password
	// is this the same thing you are getting?
	// UPDATE: the password is not the hashed version.
	//  	   This broke the addUser function
	//		   Need to figure out why the php://input is not working
	$jsonAddedUser = array(
		"username" => $_POST["username"],
		"password" => $_POST["password"]
	);
	
	var_dump($addedUserStr);
	$debugFile = fopen('debug.txt', 'w');
	fwrite($debugFile, $addedUserStr);
	fclose($debugFile);
	
	// commenting this out for now because I am getting a different format
    //$jsonAddedUser = json_decode($addedUserStr, true);
    
	/*print_r($jsonAddedUser);*/
    $jsonExistingUsers = json_decode($existingUsersStr, true);
    /*print_r($jsonExistingUsers);*/

    array_push($jsonExistingUsers["loginCredentials"], $jsonAddedUser);
	//write back the object
    $prettyString = json_encode($jsonExistingUsers, JSON_PRETTY_PRINT);
    $usersFile = fopen("users-config.json", "w");
    fwrite($usersFile, $prettyString);
	fclose($usersFile);
	
	$addingUserUsername = (string)$jsonAddedUser["username"];
	$addingUserPassword = (string)$jsonAddedUser["password"];

    /*This code creates the folder structure for the POSSD as specified in the RAD*/
    $sod = dirname(__FILE__, 2);
    mkdir($sod.'/POSSD-'.$addingUserUsername);
    $filePaths = fopen($sod.'/POSSD-'.$addingUserUsername.'/'.$addingUserUsername.'-POSSD-filepaths.json', "w");
    $startWrite = '{"projects": []}';
    fwrite($filePaths, $startWrite);
    fclose($filePaths);
    echo 'Sign Up Complete. Please login.';
?>
