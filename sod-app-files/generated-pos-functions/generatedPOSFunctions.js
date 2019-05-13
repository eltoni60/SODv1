

function addOrderedItem(item) {
    var orderName = item.name;
    var orderPrice = item.value;
    var table = document.getElementById("order");

    var tr = document.createElement("tr");
    tr.setAttribute("id", table.rows.length);

    var tdBtn = document.createElement("td");
    var btn = document.createElement("button");
    btn.setAttribute("class", "btn btn-info deleteBtn");
    btn.setAttribute("onclick", "deleteEntreeItem(this)");
    btn.innerHTML = "<i class='fa fa-trash'></i>";
    btn.setAttribute("id", "default");
    tdBtn.appendChild(btn);
    tr.appendChild(tdBtn);

    var tdName = document.createElement("td");
    tdName.setAttribute("class", "printEntree");
    tdName.innerHTML = orderName;
    tr.appendChild(tdName);

    var tdPrice = document.createElement("td");
    tdName.setAttribute("class", "printPrice");
    tdPrice.style = "text-align: right";
    tdPrice.innerHTML = orderPrice;
    tr.appendChild(tdPrice);

    var child = table.firstChild;
    child = child.nextSibling;
    child.appendChild(tr);

    calculateTotals(orderPrice);

}

function deleteEntreeItem(elm) {
    var elmToDelete = (elm.parentElement).parentElement;
    var price = elmToDelete.childNodes;
    if (price.length > 2)
        calculateTotals(price[2].outerText, true);
    elmToDelete.parentElement.removeChild(elmToDelete);

}

function deleteOrder() {
    var order = document.getElementById("order");
    order = order.firstChild;
    order = order.nextSibling;
    while (order.hasChildNodes()) {
        order.removeChild(order.firstChild);
    }
    document.getElementById('subTotal').firstChild.innerHTML = '0.00';
    document.getElementById('totalPrice').firstChild.innerHTML = '0.00';
}

function printOrder() {
    var order = document.getElementById("orderList");
    printJS({
        printable: 'orderList',
        type: 'html',
        ignoreElements: ['default'],
        targetStyle: [
            '#entree {text-allign: left; padding: 5px 50px;}',
            '#price {text-allign: right: padding: 5px 50px;}',
            '.printEntree { text-allign: left; padding: 5px 50px;}',
            '.printPrice {text-allign: right: padding: 5px 50px;}']
    });

    deleteOrder();
}

function calculateTotals(price, deleteEntree = false) {
    var parsedPrice = parseFloat(price);
    var subTotalField = parseFloat(document.getElementById('subTotal').firstChild.innerHTML);
    var totalField = parseFloat(document.getElementById('totalPrice').firstChild.innerHTML);

    if (!deleteEntree) {
        subTotalField += parsedPrice;
    } else {
        subTotalField -= parsedPrice;
    }
    totalField = subTotalField * 1.101;

    document.getElementById('subTotal').firstChild.innerHTML = subTotalField.toFixed(2);
    document.getElementById('totalPrice').firstChild.innerHTML = totalField.toFixed(2);
}

function mealMessage(elm) {
    var table = document.getElementById("order");
    var mealMessage = elm.previousElementSibling.childNodes[1].value;
    elm.previousElementSibling.childNodes[1].value = "";
    var child = table.firstChild;
    child = child.nextSibling;


    var tr = document.createElement("tr");
    tr.setAttribute("id", table.rows.length);

    var tdBtn = document.createElement("td");
    var btn = document.createElement("button");
    btn.setAttribute("class", "btn btn-info deleteBtn");
    btn.setAttribute("onclick", "deleteEntreeItem(this)");
    btn.innerHTML = "<i class='fa fa-trash'></i>";
    btn.setAttribute("id", "default");
    tdBtn.appendChild(btn);
    tr.appendChild(tdBtn);

    var tdName = document.createElement("td");
    tdName.innerHTML = mealMessage;
    tdName.setAttribute("colspan", "2");
    tr.appendChild(tdName);
    child.appendChild(tr);
    return false;
}

function loadGPOS() {
    var projData =  getProjectJSON();
    var libraryData = getItemLibraryJSON();
    createTabs(projData);
    tableContents(projData, libraryData);

    //var htmlDoc = document.getElementById("gpos");
    //var htmlStr = htmlDoc.outerHTML;

	return false;
}

function createTabs(projectObj) {
    var menuTabs = document.getElementById("menuButtons");
    menuTabs = menuTabs.childNodes[1];
    var listItem = document.createElement("li");
    listItem.setAttribute("class", "active");
    var aTag = document.createElement("a");
    aTag.setAttribute("data-toggle", "tab");
    aTag.setAttribute("href", "#" + (projectObj[0]).page_name);
    aTag.innerHTML = (projectObj[0]).page_name;
    listItem.innerHTML = aTag.outerHTML;
    menuTabs.appendChild(listItem);
    for (var i = 1; i < projectObj.length; i++) {
        listItem = document.createElement("li");
        aTag = document.createElement("a");
        aTag.setAttribute("data-toggle", "tab");
        aTag.setAttribute("href", "#" + (projectObj[i]).page_name);
        aTag.innerHTML = (projectObj[i]).page_name;
        listItem.innerHTML = aTag.outerHTML;
        menuTabs.appendChild(listItem);

    }
}

function tableContents(projectObj, itemLibraryData) {
    var content = document.getElementById("tabContent");

    for (var j = 0; j < projectObj.length; j++) { //loops through the pages
        var tabDiv = document.createElement("div");
        if(j === 0) {
            tabDiv.setAttribute("class", "tab-pane fade in active"); //Would loop but only one tab needs to be "active"
        }
        else {
            tabDiv.setAttribute("class", "tab-pane fade "); //Would loop but only one tab needs to be "active"

        }
        tabDiv.setAttribute("id", (projectObj[j].page_name)); // the div id is the name of the page
        var elements = projectObj[j].elements;//The elements array of a page
		// each element index corresponds to a table cell
		
		// this is where we will call a GET request at generatePageTable.php to 
		// get the page layout text. We will insert this table into 
		// the tabDiv right when we get it so that document.getElementId works
		
		var tableText = "";
		var http = new XMLHttpRequest();
		// added random parameter to end to try and get the browser to not cache this
        var url = "./generatePageTable.php" + "?pageName=" + projectObj[j].page_name + "&possd=" +
			sessionStorage.getItem("POSSD") + "&projectName=" + projectObj.project_name;
        http.open('GET', url, false); // not async, wait for a response!
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState === 4 && http.status === 200) {
                alert(http.responseText);
				// this is the response so we just set the variable
				tableText = http.responseText;
            }
        };
		http.send(); //send will wait until the response is recieved
		
		// tableText now has the HTML table that was requested.
		// create a DOM object out of it and then add it to this div
		var tableHTML = jQuery.parseHTML(tableText);
		// now add it 
		tabDiv.appendChild(tableHTML);
		
        var btn;

        for (var i = 0; i < elements.length; i++) { //loops through the elements to add them
			var cell = document.getElementById("" + projectObj[j].page_name + "-cell" + (i+1));
			
			/**
            var btnDiv = document.createElement("div");
            if (elements[i] === 0) {
                btnDiv.setAttribute("class", "orderButtons");
                btn = document.createElement("button");
                btn.style.visibility = "hidden";
                btnDiv.appendChild(btn);
                tabDiv.appendChild(btnDiv);
                continue;
            }
			**/
			if (elements[i] != 0) {
				//btnDiv.setAttribute("class", "orderButtons");
				btn = document.createElement("button");
				btn.setAttribute("class", "btn btn-info btn-lg");
				btn.setAttribute("id", itemLibraryData[elements[i]].item_id);
				btn.setAttribute("name", itemLibraryData[elements[i]].item_name);
				btn.innerHTML = itemLibraryData[elements[i]].item_name;
				btn.setAttribute("value", itemLibraryData[elements[i]].item_price);
				btn.setAttribute("onclick", "addOrderedItem(this)");
				
				//add this created button into the appropriate cell
				cell.appendChild(btn);
				
				//btnDiv.appendChild(btn);
				//tabDiv.appendChild(btnDiv);
			}
        }
        content.appendChild(tabDiv);
    }

}

function getProjectJSON() {
    var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("pName");
    //var possd = "Shoneys";
    //var pName = "test2";
    var projectObj = returnLoadedJSON("../POSSD-" + possd + "/project-" + pName + "/" + pName + "-project.json");
    projectObj = projectObj["pages"];
    return projectObj;
}

function getItemLibraryJSON() {
    var possd = sessionStorage.getItem("POSSD");
    var pName = sessionStorage.getItem("pName");
    //var possd = "Shoneys";
    //var pName = "test2";
    var libraryObj = returnLoadedJSON("../POSSD-" + possd + "/project-" + pName + "/" + pName + "-item-library.json");
    libraryObj = libraryObj["items"];
    return libraryObj;

}












