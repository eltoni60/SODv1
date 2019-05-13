<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Designer v3.0</title>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="../sod-app-files/generated-pos-functions/bootstrap-3.4.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../sod-app-files/generated-pos-functions/jquery-3.4.1.min.js"></script>
    <script src="../sod-app-files/generated-pos-functions/bootstrap-3.4.1-dist/js/bootstrap.min.js"></script>
    <script src="../sod-app-files/loging-signup/security-verifying.js"></script>
    <script src="../sod-app-files/generated-pos-functions/generatedPOSFunctions.js"></script>
    <link href="../sod-app-files/generated-pos-functions/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="../sod-app-files/generated-pos-functions/print.min.js"></script>
    <link href="designerv3.css" rel="stylesheet">
</head>
<body onload="loadDesigner()">

<?php

	require('sod-functions.php');

?>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            </button>
            <a class="navbar-brand" href="#"><script> document.write('Sod - ' + sessionStorage.getItem("POSSD") + ' - ' + sessionStorage.getItem("PROJECT_NAME"));</script></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="../sod-app-files/project-selector.html">Project Selector</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="../sod-app-files/project-selector.html">Item Library</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="">Staging Area Selector</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <button class="btn btn-warning btn-lg" onclick="return logOut()"><span class="glyphicon glyphicon-log-in"></span> Log Out</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <h3 style="text-align: center">Staging Area Designer</h3>
    <div class="row">
        <form class="deployPageForm" method="post" action="" onsubmit="deployPOSSystem.php">
            <input class="btn btn-success btn-lg" value="Deploy" type="submit" id="Deploy" onclick="submitForm('deploySystem.php')" />
        </form>
        <div class="btn btn-danger btn-lg trash" id="trash" ondragover="dragOver(event)" ondrop="deleteDrop(event)"><i class='fa fa-trash'></i></div>
    </div>
</div>


<div class="grid-container whole">
<div id="stagingArea" class="staginArea">
	<!--
    <div id="TL" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
    <div id="TC" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
    <div id="TR" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
    <div id="CL" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
    <div id="CC" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
    <div id="CR" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
    <div id="BL" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
    <div id="BC" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
    <div id="BR" class="grid-item" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
	-->
	<?php
		// had to pass all this info as a parameter to the HTTP header
		$page_name = $_GET["modifyingPage"];
		$possd = $_GET["possd"];
		$projectName = $_GET["projectName"];
		
		$sod = dirname(__DIR__);
		$projDir = $sod."/possd-".$possd."/project-".$projectName."/";
		$projectDataFilePath = $projDir.$projectName."-project.json";
		$loadedProject = deserialize_project_data($projectDataFilePath);
		$itemLibraryFilePath = $projDir.$projectName."-item-library.json";
		$loadedItemLibrary = deserialize_item_library($itemLibraryFilePath);
		$pageObject = $loadedProject->get_page($page_name);
		
		// generate the table from the page
		// from the predefined function
		generate_page($pageObject, $loadedItemLibrary, true);
		// percentage table
	?>
</div>

<div id="elementToolBox" class="elementtoolbox grid-container">
</div>
<div class="properties grid-container">
    <h3 style="text-align: right; grid-column: 1 / span 3">Properties <button onclick="toggleHide(event)" class="btn btn-info btn-sm"><i class="fa fa-bars" aria-hidden="true"></i></button></h3>
    <label>Item Name:
        <input type="text" class="grid-item">
    </label>
    <label> Item Price
        <input type="text" class="grid-item">
    </label>
</div>
</div>
</body>
<script>

    function dragStart(e) {
        e.dataTransfer.setData("text", e.target.id);
    }

    function dragOver(e) {
        e.preventDefault();
    }

    function dragDrop(e) {
        e.preventDefault();
        var data = e.dataTransfer.getData("text");
        var element = document.getElementById(data);
        var clonedElement = element.cloneNode(true);
        clonedElement.setAttribute("ondragstart", "deleteDrag(event)")
        if(e.currentTarget.childNodes.length === 0)
            e.target.appendChild(clonedElement);
    }

    function deleteDrag(e) {
        var ele = e.currentTarget;
        ele = ele.parentElement;
        e.dataTransfer.setData("text", ele.id);
    }

    function deleteDrop(e) {
        e.preventDefault();
        var data = e.dataTransfer.getData("text");
        var deleteElement = document.getElementById(data);
        deleteElement.removeChild(deleteElement.firstChild);
    }

	// using your function
	loadTab();
</script>
</html>
