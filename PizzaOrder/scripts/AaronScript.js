/*Definition of CurrentOrder Object 
    Purpose: To contain all data (in string or JSON format) about the current user and their order and be safely passed around in AJAX calls.
    Properties:
*/
var currentOrder = {
    //user ID
    uID = -1,
    //user address info
    address = {status:"default"},
    newAddress = " ",
    //an array of pizzas
    pizzas = []
};
function Pizza() {
    Pizza.size = "";
    Pizza.dough = "";
    Pizza.sauce = "";
    Pizza.cheese = "";
    Pizza.toppings = "";
}
function loadFirstPage(){
    document.getElementById("outputDiv").innerHTML = `        <h3>Welcome!</h3>
    <p>Thanks for choosing our store! <br><br>Please enter your email address below to begin placing your order!</p>
    <FORM method="POST" id="introForm">
        <input id="emailInput" name="emailInput" type="text"/><br/>
        <input id="subButton1" name="subButton1" type="submit"/>
    </FORM>`;
    //When users coming back from steps 5 & 7, will have to check for and make changes to CurrentOrder there or here
    $("#introForm").submit(function(event){
        //add logic here to test for if user entered no email address or invalid, to reload page, else run below
        $.post("php/intro.php", $(this).serialize(), onEntry);
        event.preventDefault();
    })
}
var onEntry = function(response){
 if(response.status == "None"){
    displayAddressPage();
 } else {
    
    var addressArray = [];
    //$("#outputDiv").append(response.status);
    //$("#test").append(response.addresses[0].addr);
    for (var x in response.addresses) {
        //$("#error").append(JSON.stringify(response.addresses[x]));
        addressArray.push(JSON.stringify(response.addresses[x]));
    }
    $("#test").append(addressArray);
    displayAddressPage(addressArray);
 }
};

function displayAddressPage(addressArray) {
    //if no previous addresses set, show user just address entry form
    if(!isset(addressArray)){
        displayAddForm();
    } else { //else show user previous used addresses associated with their email, as well as address entry form
        //output code
        displayAddForm();
    }
    //AJAX here to set up submit button to move forward ALSO to 
}