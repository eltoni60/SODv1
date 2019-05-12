

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
        tabDiv.setAttribute("id", (projectObj[j].page_name));
        var elements = projectObj[j].elements;//The elements array of a page

        for (var i = 0; i < elements.length; i++) { //loops through the elements to add them
            var btnDiv = document.createElement("div");
            if (itemLibraryData[elements[i]].item_id === 0) {
                tabDiv.appendChild(btnDiv);
                continue;
            }
            btnDiv.setAttribute("class", "orderButtons");
            var btn = document.createElement("button");
            btn.setAttribute("class", "btn btn-info btn-lg");
            btn.setAttribute("id", itemLibraryData[elements[i]].item_id);
            btn.setAttribute("name", itemLibraryData[elements[i]].item_name);
            btn.innerHTML = itemLibraryData[elements[i]].item_name;
            btn.setAttribute("value", itemLibraryData[elements[i]].item_price);
            btn.setAttribute("onclick", "addOrderedItem(this)");
            btnDiv.appendChild(btn);
            tabDiv.appendChild(btnDiv);
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












