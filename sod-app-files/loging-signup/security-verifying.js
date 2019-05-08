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
    var m_username = document.forms["signUpInfo"]['m_userName'];
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
    xobj.open('GET', pathName, false); // Replace 'my_data' with the path to your file
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

function signUp() {
    if (!(checkUsername() && checkPassword())) {
        //alert('Please fix the marked fields.');
        return false;
    }
    var enteredUsername = document.forms["signUpInfo"]["m_userName"].value;
    var enteredPassword = hashString(document.forms["signUpInfo"]["m_password"].value);
    var credential = {
        username: enteredUsername,
        password: enteredPassword
    };
    /*var logins = returnLoadedJSON('../sod-app-files/users-config.json');
    logins.loginCredentials.push(credential);*/
    var dataString = JSON.stringify(credential);
    var http = new XMLHttpRequest();
    var url = "./addUser.php";
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState === 4 && http.status === 200) {
            alert(http.responseText);
        }
    };
    http.send(dataString);
    window.location.replace('./index.html');
    return false;
}

//Used when for logging in.
function login() {
    var enteredUsername = document.forms["loginInfo"]["username"].value;
    var enteredPassword = document.forms["loginInfo"]["password"].value;
    var credentials = returnLoadedJSON('../sod-app-files/users-config.json').loginCredentials;
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
    if (isAlphaNum(name.value)) {
        name.style.backgroundColor = '#53f442';
        name.disabled = false;
    }
    else {
        name.style.backgroundColor = '#f77474';
        name.disabled = true;
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
        var url = "./addProject.php";
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState === 4 && http.status === 200) {
                alert(http.responseText);
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
    var tempPOSSD = "EXAMPLE";
    window.location.href = "../generated-pos/" + possd + "-" + projectname
        + "-gpos.html";
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
        var http = new XMLHttpRequest();
        var url = "./processSODP.php";
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState === 4 && http.status === 200) {
                alert(http.responseText);
            }
        };
		//we need to carry the POSSD to the processSODP.php
		//file, so we are going to create a wrapper json object
        var wrapper = "{";
		wrapper += "\"possd\":\"" + sessionStorage.getItem("POSSD") + "\",";
		wrapper += "\"sodp\":";
		wrapper += dataText;
		wrapper += "}";
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


var numOfItems;
//This just loads the existing library files when the page is loaded
function loadLibraryFields() {
    var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("PROJECT_NAME");
    var itemLibrary = returnLoadedJSON("../POSSD-" + possd + "/project-" + pName + "/" + pName + "-item-library.json");

    for(var i = 0; i < itemLibrary.items.length; i++) {
        var elmDiv = document.createElement("div");
        elmDiv.id = i;
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
        elmPriceInput.value = itemLibrary.items[i].item_price;
        elmDiv.appendChild(elmPriceInput);

        var btn = document.createElement("button");
        btn.setAttribute("onClick",  "deleteLibraryFields(event," + i + ")");
        btn.innerHTML = "Delete";
        elmDiv.appendChild(btn);
        document.getElementById("itemFields").appendChild(elmDiv);
        document.getElementById("itemFields").appendChild(document.createElement("br"));
    }
    numOfItems = i;
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

function saveLibraryFields() {
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
       jsonItemObj.library.items.push(new Item(i/2, name, parseFloat(price)));
    }
    var stringy = JSON.stringify(jsonItemObj);
   /*console.log(jsonItemObj);
   console.log(stringy);*/

    var http = new XMLHttpRequest();
    var url = "./processItemLibrary.php";
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState === 4 && http.status === 200) {
            alert(http.responseText);
        }
    };
    http.send(stringy);

    window.location.href = "./staging-area-selector.html";
    return false;
}

function addMoreLibraryFields(event) {
    event.preventDefault();
    var itemFields = document.getElementById("itemFields");
    if(numOfItems == null)
        numOfItems = 0;

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
    btn.innerHTML = "Delete";
    elmDiv.appendChild(btn);
    itemFields.appendChild(elmDiv);
    itemFields.appendChild(document.createElement("br"));
    numOfItems++;

}

function deleteLibraryFields(evt,  id) {
    evt.preventDefault();
    var itemFields = document.getElementById(id);
    var brField = itemFields.nextElementSibling;
    itemFields.parentNode.removeChild(itemFields);
    brField.parentNode.removeChild(brField);


}


































