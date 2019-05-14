//function to compare two string values,
function validatePassword (string1, string2, checkHash = false) {
    //The default value of check hash is to have this function pull double duty

    //If chechHash is false then we are just verifying that two strings are equal to each other
    if (!checkHash) {
        return (string1 === string2);
    }
    //Assumptions:
    //string1 is the unhashed function
    //string2 is the hashed password retrieve from users-config.json
    return (hashString(string1) === string2);
}

//rudemantry hash function, supply a string and it returns the string encrypted
function hashString(password) {
    var encryptedPassword = "",
        original =  "abcdefghijklmnopqrstuvwxyz0123456789",
        encrypted = "mhtfgkbpjwerqslniuoxzyvdca9517038624",
        i, character;

    for (i = 0; i < password.length; i++) {
        character = encrypted[original.indexOf(password[i].toLowerCase())] || password[i];
        if (password[i] === password[i].toUpperCase()) {
            character = character.toUpperCase();
        }
        encryptedPassword += character;
    }
    //console.log(encryptedPassword);
    return encryptedPassword;
}

//checks if the string is alpha numeric
function isAlphaNum(name) {
    var letterNumber = /^[0-9a-zA-Z]+$/;
    return !!name.match(letterNumber);
}

function checkUsername() {
    var m_username = document.forms["signUpInfo"][0];
	//added date to the end to try to prevent browser caching
    var usernames = returnLoadedJSON('../sod-app-files/users-config.json').loginCredentials;
    for (var i = 0; i < usernames.length; i++) {
        if (usernames[i].username === m_username.value) {
            m_username.style.backgroundColor = '#f77474';
            return false;
        }
    }
    m_username.style.backgroundColor = '#53f442';
    return true;
}

function checkPassword() {
    var pw1 = document.forms["signUpInfo"]['m_password'];
    var pw2 = document.forms["signUpInfo"]['m_password1'];
    if (validatePassword(pw1.value, pw2.value)) {
        pw1.style.backgroundColor = '#53f442';
        pw2.style.backgroundColor = '#53f442';
        return true;
    }
    else {
        pw2.style.backgroundColor = '#f77474';
        return false;
    }

}

function loadJSON(callback, pathName) {
    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType("application/json");
	//adding a dummy parameter to the resource for cache busting purposes
    xobj.open('GET', pathName + "?nocache=" + new Date().getTime(), false); // Replace 'my_data' with the path to your file
	xobj.onreadystatechange = function () {
        if (xobj.readyState == 4 && xobj.status == "200") {
            // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
            callback(xobj.responseText);
        }
    };
    xobj.send(null);
}

//Use this to load any JSON you need may need to the client
function returnLoadedJSON(pathName) {
    var file;
    loadJSON(function(response) {
        // Parse JSON string into object
       var actual_JSON = JSON.parse(response);
       file = actual_JSON;
    }, pathName);
    return file;
}

/** Converted this to an AJAX call in signup.html **/
/** This seemed to work better overall **/
/*
function signUp() {
    if (!(checkUsername() && checkPassword())) {
        //alert('Please fix the marked fields.');
        return false;
    }
	console.log("I am signing up");
	
    var enteredUsername = document.forms["signUpInfo"]["m_userName"].value;
    var enteredPassword = hashString(document.forms["signUpInfo"]["m_password"].value);
    var credential = {
        username: enteredUsername,
        password: enteredPassword
    };
    var logins = returnLoadedJSON('../sod-app-files/users-config.json');
    logins.loginCredentials.push(credential);
    var dataString = JSON.stringify(credential);
    var http = new XMLHttpRequest();
	
	// added cache busting to this to see if it helps
    
	var url = "./addUser.php" ;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState === 4 && http.status === 200) {
            alert(http.responseText);
        }
    };
    http.send(dataString);
	console.log("I am being sent: '" + dataString + "'");
    window.location.replace('./index.html');
    return false;
}
*/

//Used when for logging in.
function login() {
    var enteredUsername = document.forms["loginInfo"]["username"].value;
    var enteredPassword = document.forms["loginInfo"]["password"].value;
	// addded cache busting to see if it helps
    var credentials = returnLoadedJSON('../sod-app-files/users-config.json' + '?nocache=' + new Date().getTime()).loginCredentials;
    var index = -1;
    for (var i = 0; i < credentials.length; i++) {
        if (enteredUsername === credentials[i].username) {
            index = i;
        }
    }
     if (index < 0) {
         alert('Incorrect Username or Password');
         return false;
     }
     else if (validatePassword(enteredPassword, credentials[index].password, true)) {
         //Setting a session variable with the POSSD username, can be used in filepaths
         //Retrieve variable with sessionStorage.getItem("POSSD");
         sessionStorage.setItem("POSSD", enteredUsername);
         window.location.href = './project-selector.html';
         return false;
     }
     else {
         alert('Incorrect Username or Password');
         return false;
     }
}

function checkProjectName() {
    var name = document.getElementById('name');
    var btn = document.getElementById("nextBtn");
    if (isAlphaNum(name.value)) {
        name.style.backgroundColor = '#53f442';
        btn.disabled = false;
    }
    else {
        name.style.backgroundColor = '#f77474';
        btn.disabled = true;
    }
}

// same function as checkProjectName but under a different alias
function checkPageName() {
    var name = document.getElementById('name');
    if (isAlphaNum(name.value)) {
        name.style.backgroundColor = '#53f442';
        return false;
    }
    else {
        name.style.backgroundColor = '#f77474';
        return false;
    }
}

//Redirects the user to the
function redirectToItemLibrary(projectname, newProject = false) {
    if(newProject) {
        sessionStorage.setItem("PROJECT_NAME", projectname);
        var possd = sessionStorage.getItem("POSSD");
        var projectObj = {
            POSSD: possd,
            pName: projectname
        };
        var http = new XMLHttpRequest();
		// added cache busting technique to see if it would help
        var url = "./addProject.php"+ "?nocache=" + new Date().getTime();
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState === 4 && http.status === 200) {
                //alert(http.responseText);
            }
        };
        http.send(JSON.stringify(projectObj));
        window.location.href ="./item-library.html";
        return false;
    }
    sessionStorage.setItem("PROJECT_NAME", projectname);
    window.location.href ="./item-library.html";
    return false;
}

function redirectToGPOS(projectname) {
    var possd = sessionStorage.getItem("POSSD");
    sessionStorage.setItem("pName", projectname);
    window.location.href = "../generated-pos/" + possd + "-" + projectname
        + "-gpos.html";
    return false;
}

function createNewPage(newPageName) {
	var projectName = sessionStorage.getItem("PROJECT_NAME");
	var possd = sessionStorage.getItem("POSSD");
	
	// ensure we are not in remove mode
	exitPageRemoveMode('editPages');
	
	var projectObj = {
            POSSD: possd,
            projectName: projectName,
			pageName: newPageName
        };
		
	var http = new XMLHttpRequest();
	// added cache busting technique to see if it would help
	var url = "./addPage.php" + "?nocache=" + new Date().getTime();
	http.open('POST', url, true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState === 4 && http.status === 200) {
			//alert(http.responseText);
		}
	};
	var httpBody = JSON.stringify(projectObj);
	http.send(httpBody);
	// we want to manually create a new page button here
	createNewPageButton('editPages', newPageName);
	
	// hiding the modal that is still visible with jQuery here
	$('#newPageName').modal('hide');
	
	return false;
}

function removePage(pageNameToRemove, pageContainerId) {
	var projectName = sessionStorage.getItem("PROJECT_NAME");
	var possd = sessionStorage.getItem("POSSD");
	
	var projectObj = {
            POSSD: possd,
            projectName: projectName,
			pageName: pageNameToRemove
        };
		
	var http = new XMLHttpRequest();
	// added cache busting technique to see if it would help
	var url = "./removePage.php" + "?nocache=" + new Date().getTime();
	http.open('POST', url, true);
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState === 4 && http.status === 200) {
			//alert(http.responseText);
		}
	};
	var httpBody = JSON.stringify(projectObj);
	http.send(httpBody);
	
	// also detach the button element with this ID
	var btn = document.getElementById(pageNameToRemove);
	btn.parentNode.removeChild(btn);
	
	exitPageRemoveMode(pageContainerId);
	return false;
}

function createNewPageButton(pageContainerId, newPageName) {
	var btn = document.createElement("BUTTON");
	
	btn.id = newPageName;
	btn.value = newPageName;
	
	var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("PROJECT_NAME");
	btn.setAttribute("onclick", "window.location.replace('./designerMockUpv3.php?modifyingPage=" 
		+ newPageName + "&possd=" + possd + "&projectName=" + pName + "');");
	
	btn.className += 'btn btn-info btn-lg';
	btn.innerHTML = newPageName;
	btn.style.margin = "1em 2em 1em";
	btn.style.width = "50% ";
	document.getElementById(pageContainerId).appendChild(btn);
	//document.getElementById(pageContainerId).appendChild(document.createElement("BR"));
}

/** This puts the page into "remove move" **/
function enterPageRemoveMode(pageContainerId) {
	var pageButtonContainer = document.getElementById(pageContainerId);
	// for each child element,
	// if it is a button element,
	// then change the onclick attribute to the 
	// appropriate removePage() function call
	//  and add "Remove " to the front of its display text
	var pageChildren = pageButtonContainer.children;
		
	for (var i = 0; i < pageChildren.length; i++) {
		var child = pageChildren[i];
		if (child.tagName.toUpperCase() == "BUTTON") {
			//we can safely modify this now
			child.innerHTML = "Remove " + child.id;
			child.setAttribute("onclick", "return removePage('" + child.id + "', '" + pageContainerId + "')");
			child.setAttribute("class",'btn btn-danger btn-lg');
		}
	}
	
	// change the functionality of the remove page button
	var removeButton = document.getElementById("removePageButton");
	removeButton.innerHTML = "Cancel Remove";
	removeButton.setAttribute("onclick", "return exitPageRemoveMode('editPages');");
	
	return false;
}

/** 
	This puts the page back into "normal mode" by restoring
    button actions to what they originally were and changin 
    the button text to what they were before
**/
function exitPageRemoveMode(pageContainerId) {
	var pageButtonContainer = document.getElementById(pageContainerId);
	// for each child element,
	// if it is a button element,
	// then change the onclick attribute to  
	// what it is supposed to be and remove the
	// "Remove " from the front of its display text
	var pageChildren = pageButtonContainer.children;
	
	console.log("Entering remove page mode");
	
	for (var i = 0; i < pageChildren.length; i++) {
		var child = pageChildren[i];
		if (child.tagName.toUpperCase() == "BUTTON") {
			//we can safely modify this now
			child.innerHTML = child.id;
			
			var possd = sessionStorage.getItem("POSSD");
			var pName = sessionStorage.getItem("PROJECT_NAME");
			child.setAttribute("onclick", "window.location.replace('./designerMockUpv3.php?modifyingPage=" 
				+ child.id + "&possd=" + possd + "&projectName=" + pName + "');");
			child.setAttribute("class", 'btn btn-info btn-lg');
		}
	}
	
	// change the functionality of the remove page button
	var removeButton = document.getElementById("removePageButton");
	removeButton.innerHTML = "Remove Page";
	removeButton.setAttribute("onclick", "return enterPageRemoveMode('editPages');");
	
	return false;
}

function logOut() {
    sessionStorage.clear();
    window.location.href = "./index.html";
    return false;
}

var loadFile = function (event) {
    validateFile();
    var input = event.target.files;
    var reader = new FileReader();
    reader.onload = function () {
        var dataText = reader.result;
        testString = dataText;
        var http = new XMLHttpRequest();
		// added random parameter to end to try and get the browser to not cache this
        var url = "./processSODP.php";
        http.open('POST', url, false);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState === 4 && http.status === 200) {
                //alert(http.responseText);
            }
        };
		//we need to carry the POSSD to the processSODP.php
		//file, so we are going to create a wrapper json object
        var wrapper = '{';
		wrapper += "\"possd\":\"" + sessionStorage.getItem("POSSD") + "\",";
		wrapper += "\"sodp\":";
		wrapper += dataText;
		wrapper += "}";
		//console.log("I am sending " + wrapper );
		http.send(wrapper);
    };
    reader.readAsText(input.files[0]);

};

var validateFile = function () {
    var file = document.getElementById("myFile").files;
    var extension = file[0].name.split('.').pop();
    var bol = (extension !== 'sodp');
    if (file.length === 0 || bol) {
        alert("You must select a valid file with extension .sodp");
        return false;
    }
    return true;
};



//This just loads the existing library files when the page is loaded
function loadLibraryFields() {
    var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("PROJECT_NAME");
	// added random parameter to the end to prevent caching
    var itemLibrary = returnLoadedJSON("../POSSD-" + possd + "/project-" + pName + "/" + pName + "-item-library.json");
    if(itemLibrary.items.length == 0) {
        addMoreLibraryFields();
        return;
    }

    for(var i = 0 ; i < itemLibrary.items.length; i++) {
        var itemID = itemLibrary.items[i].item_id;
        var elmDiv = document.createElement("div");
        elmDiv.id = itemID;
        elmDiv.style.display = "inline-block";

        var elmItem = document.createElement("label");
        elmItem.appendChild(document.createTextNode("Item Name: "));
        elmDiv.appendChild(elmItem);

        var elmItmInput = document.createElement("input");
        elmItmInput.type = "text";
        elmItmInput.value = itemLibrary.items[i].item_name;
        elmDiv.appendChild(elmItmInput);

        var elmPrice = document.createElement("label");
        elmPrice.appendChild(document.createTextNode("Price: "));
        elmDiv.appendChild(elmPrice);

        var elmPriceInput = document.createElement("input");
        elmPriceInput.type = "text";
        elmPriceInput.value = (itemLibrary.items[i].item_price).toFixed(2);
        elmDiv.appendChild(elmPriceInput);

        var btn = document.createElement("button");
        btn.setAttribute("onClick",  "deleteLibraryFields(event," + itemID + ")");
        btn.setAttribute('class','btn btn-info deleteBtn')
        btn.innerHTML = "<i class='fa fa-trash'></i>";
        elmDiv.appendChild(btn);
        document.getElementById("itemFields").appendChild(elmDiv);
        document.getElementById("itemFields").appendChild(document.createElement("br"));
    }
}

class Item {
    constructor(id, name, price) {
        this.item_id = id;
        this.item_name = name;
        this.item_price = price;
        this.attribute_names = [];
        this.attribute_values = [];
    }
}

function saveLibraryFields(path) {
    var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("PROJECT_NAME");

    //Gets the div element with all of the fields
    var dataFields = document.getElementById("itemFields");
    //Gets the list of childs in the div element
    var childFields = dataFields.childNodes;
    //The itemLibrary object
    var jsonItemObj = {
        //sends the POSSD and the project name in the string
        POSSD: possd,
        projectName: pName,
        //This makes it so that we dont have to edit the string to conform to our library format
        library:
        {
            items: []
        }
    };

   for(var i = 0; i < childFields.length; i+=2) {
       var inputLabels = childFields[i].childNodes;

       var name = inputLabels[1].value;
       var price = inputLabels[3].value;
       if(name === "" || price === "") //Skips the
           continue;
       jsonItemObj.library.items.push(new Item(childFields[i].id, name, parseFloat(price)));
    }
    var stringy = JSON.stringify(jsonItemObj);
   /*console.log(jsonItemObj);
   console.log(stringy);*/

    var http = new XMLHttpRequest();
    var url = "./processItemLibrary.php" + "?nocache=" + new Date().getTime();
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState === 4 && http.status === 200) {
            //alert(http.responseText);
        }
    };
    http.send(stringy);
    if(childFields.length < 1)
        addMoreLibraryFields(null);

    window.location.href = path;
    return false;
}

function addMoreLibraryFields(event) {
    var numOfItems;
    if(!(event == null))
        event.preventDefault();
    var itemFields = document.getElementById("itemFields");
    // assuming this is checking if there are any children
	if(itemFields.lastChild == null)
        numOfItems = 1;
    else{
        var last = itemFields.lastChild;
        last = last.previousSibling;
        numOfItems = last.id;
        numOfItems++;
    }

    var elmDiv = document.createElement("div");
    elmDiv.id = numOfItems;
    elmDiv.style.display = "inline-block";

    var elmItem = document.createElement("label");
    elmItem.appendChild(document.createTextNode("Item Name: "));
    elmDiv.appendChild(elmItem);

    var elmItmInput = document.createElement("input");
    elmItmInput.type = "text";
    elmDiv.appendChild(elmItmInput);

    var elmPrice = document.createElement("label");
    elmPrice.appendChild(document.createTextNode("Price: "));
    elmDiv.appendChild(elmPrice);

    var elmPriceInput = document.createElement("input");
    elmPriceInput.type = "text";
    elmDiv.appendChild(elmPriceInput);

    var btn = document.createElement("button");
    btn.setAttribute("onClick",  "deleteLibraryFields(event," + numOfItems + ")");
    btn.innerHTML = "<i class='fa fa-trash'></i>";
    btn.setAttribute('class','btn btn-info deleteBtn')
    elmDiv.appendChild(btn);
    itemFields.appendChild(elmDiv);
    itemFields.appendChild(document.createElement("br"));


}

function deleteLibraryFields(evt,  id) {
    evt.preventDefault();
    var itemFields = document.getElementById(id);
    var brField = itemFields.nextElementSibling;
    itemFields.parentNode.removeChild(itemFields);
    brField.parentNode.removeChild(brField);


}

function toggleHide(e) {
    var element = e.currentTarget;
    element = element.parentElement;
    for (var i = 0; i < 3; i++) {
        element = element.nextElementSibling;
        if(element.style.visibility === "hidden")
            element.style.visibility = "visible";
        else
            element.style.visibility = "hidden";

    }
}

function loadDesigner() {
    loadElementToolBox();
    loadTab();


}

function loadTab() {
    var page = getUrlVars()["modifyingPage"];
    var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("PROJECT_NAME");
    // added random parameter to the end to prevent caching
    var projectData = returnLoadedJSON("../POSSD-" + possd + "/project-" + pName + "/" + pName + "-project.json")["pages"];
    var elementArray;
    var cellCount;
    for (var i = 0; i < projectData.length; i++) {
        if(projectData[i]["page_name"] === page) {
             elementArray = projectData[i]["elements"];
             cellCount = projectData[i]["layout"].cells.length;
             break;
        }
    }

    /*var stagingArea = document.getElementById("stagingArea");
    var divItem = stagingArea.firstElementChild;*/

    for(var j = 0; j < stagingArea.childElementCount; j++) {
        if(elementArray[j] === 0)
            continue;

        var element = document.getElementById(elementArray[j]);
        var clonedElement = element.cloneNode(true);
        clonedElement.setAttribute("ondragstart", "deleteDrag(event)");
        var cell = document.getElementById("cell" + (j+1));
        cell.appendChild(clonedElement);
    }

    return false;
}

function loadElementToolBox() {
    var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("PROJECT_NAME");
    // added random parameter to the end to prevent caching
    var itemLibrary = returnLoadedJSON("../POSSD-" + possd + "/project-" + pName + "/" + pName + "-item-library.json").items;
    var toolbox = document.getElementById("elementToolBox");

    for (var i = 0; i < itemLibrary.length; i++) {
        var divItem = document.createElement("div");
        divItem.setAttribute("class", "grid-item");
        var btnItem = document.createElement("button");
        btnItem.setAttribute("draggable", true);
        btnItem.setAttribute("class", "btn btn-info btn-md ");
        btnItem.setAttribute("ondragstart", "dragStart(event)");
        btnItem.setAttribute("id", itemLibrary[i].item_id);
        btnItem.innerHTML = itemLibrary[i].item_name;
        divItem.appendChild(btnItem);
        toolbox.appendChild(divItem);
    }

}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}


function saveDesignerLayout(path) {
    var parameters = getUrlVars();
	var jsonProjectFilePath = "../POSSD-" + parameters["possd"] + "/project-" + parameters["projectName"] + "/" + parameters["projectName"] + "-project.json";
	var pData = returnLoadedJSON(jsonProjectFilePath);
	var pagesData = pData["pages"];
    var cellCount;
    for (var i = 0; i < pagesData.length; i++) {
        if(pagesData[i]["page_name"] === parameters["modifyingPage"]) {
            cellCount = pagesData[i]["layout"]["cells"].length;
            break;
        }
    }
    var elementArray = [];

    for (var j = 1; j <= cellCount; j++) {
        var cell = document.getElementById("cell" + j);
        if(cell.childElementCount === 0) {
            elementArray.push(0);
        }
        else {
            var btnID = cell.firstElementChild.id;
            elementArray.push(parseInt(btnID));
        }
    }

    pData["pages"][i]["elements"] = elementArray;

    var http = new XMLHttpRequest();
    // added cache busting technique to see if it would help
    var url = "./saveProject.php";
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState === 4 && http.status === 200) {
            //alert(http.responseText);
        }
    };

    var projectObj = {
        POSSD: parameters["possd"],
        projectData: pData
    };
    http.send(JSON.stringify(projectObj));
    window.location.href = path;
    return false;
}


function changeLayoutForPage(changingLayoutPageName, changingLayoutLayoutName) {
	var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("PROJECT_NAME");
	
	var returnStatus = "";
	var http = new XMLHttpRequest();
    // added cache busting technique to see if it would help
    var url = "./changePageLayout.php";
    http.open('POST', url, false);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState === 4 && http.status === 200) {
			//alert(http.responseText);
        }
    };

    var projectObj = {
        POSSD: possd,
        projectName: pName,
		pageName: changingLayoutPageName,
		layoutName: changingLayoutLayoutName
    };
	
	http.send(JSON.stringify(projectObj));
	
	//check return status and reload the page
	//if it was successful, otherwise make
	//an alert for the return status
	
	if (String(http.responseText).trim() == "SUCCESS") {
		var path = "./designerMockUpv3.php?modifyingPage=" + changingLayoutPageName + 
			"&possd=" + possd + "&projectName=" + pName;
		saveDesignerLayout(path);
	}
	else {
		alert("Could not change layout. Too many elements to fit.");
	}
	
	return false;
}























