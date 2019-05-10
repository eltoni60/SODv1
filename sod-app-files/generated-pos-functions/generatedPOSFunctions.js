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

function deleteEntreeItem(elm){
    var elmToDelete = (elm.parentElement).parentElement;
    var price =  elmToDelete.childNodes;
    calculateTotals(price[2].outerText, true);
    elmToDelete.parentElement.removeChild(elmToDelete);

}

function deleteOrder() {
    var order = document.getElementById("order");
    order = order.firstChild;
    order = order.nextSibling;
    while(order.hasChildNodes()) {
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

    if(!deleteEntree) {
        subTotalField += parsedPrice;
    }
    else {
        subTotalField -= parsedPrice;
    }
    totalField = subTotalField * 1.101;

    document.getElementById('subTotal').firstChild.innerHTML = subTotalField.toFixed(2);
    document.getElementById('totalPrice').firstChild.innerHTML = totalField.toFixed(2);
}
