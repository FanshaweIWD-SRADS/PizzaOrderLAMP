/*  Definition of CurrentOrder Object
    Purpose: To contain all data (in string or JSON format) about the current user and their order and be safely passed around in AJAX calls.
    Properties:current users ID, address as a JSON object, new address if entered, and the pizzas that they've prepared in this current order so far.
*/
var currentOrder = {
    //user ID - default value of -1, if this is still -1 at step 5, means that this is a new user, and will require an entry into the customer MySQL table
    "uID":-1,
    "email":" ",
    //This holds the status of their address. Will be "default", "new", or "old". Used later to decide if an entry needs to be made into address MySQL table
    "addressInfo":{status:"default"},
    //user address info - this actually HOLDS the address they are currently using
    "address":" ",
    //an array of Pizza objects
    "pizzas":[]
};

/*  Definition of Pizza Object
    Purpose: To encapsulate all the qualities of a pizza for both temporary storage and then to conveniently send data to MySQL server upon order completion.
    TODO: May need to change this to a class, so each new Pizza can be created via ' x = new Pizza();' syntax.
*/
function Pizza() {
    Pizza.size = "";
    Pizza.dough = "";
    Pizza.sauce = "";
    Pizza.cheese = "";
    Pizza.toppings = "";
}
// from https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript .
function validateEmail(email) {
    var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    return re.test(email);
}

function loadFirstPage(err){
    document.getElementById("outputDiv").innerHTML = `        <h3>Welcome!</h3>
    <p>Thanks for choosing our store! <br><br>Please enter your email address below to begin placing your order!</p>
    <FORM method="POST" id="introForm">
        <input id="emailInput" name="emailInput" type="text"/><br/><br/>
        <input id="subButton1" name="subButton1" type="submit"/>
    </FORM>`;
    if(err) { //if error doesn't exist it is undefined and should be seen by the if as 'no'. If it doesn't, call me...
        $("#outputDiv").append(`<h4 class="errortext">Please enter a valid email!</h4>`);
    }
    //When users coming back from steps 5 & 7, will have to check for and make changes to CurrentOrder there or here
    $("#introForm").submit(function(event){
        //add logic here to test for if user entered no email address or invalid, to reload page, else run below
        if(validateEmail($("#emailInput").val())) {
            currentOrder.email = $("#emailInput").val();
            $.post("php/intro.php", $(this).serialize(), onEntry);
            event.preventDefault();
        }
        else {
            //looks like they entered an invalid email address
            //NANIIIII!?!?!!
            console.log("Bad email...");
            var err = true;
            loadFirstPage(err);
        }
    });
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
    //$("#test").append(addressArray);
    displayAddressPage(addressArray);
 }
};

function displayAddressPage(addressArray) {
    //clear old page from output
    $("#outputDiv").html(" ");
    //show user previous used addresses associated with their email, as well as address entry form
    var adString = "";
    if(typeof(addressArray) !== 'undefined'){
        adString += `<h2>Looks like you're a repeat customer!</h2>
        </br><h4>Please select a previously used address from below, or enter a new one</h4>
        <FORM method="POST" id="addressForm">
            <table id="addTable" name="addTable">
                <tr><th>Selection</th><th>Former Addresses</th></tr>`;
        currentOrder.uID = parseInt(JSON.parse(addressArray[0]).cusID);
        for(var y in addressArray) {
            //set address Array to be JSON (was string before) for easier access of values
            addressArray[y] = JSON.parse(addressArray[y]);
            //add checkbutton to output
            adString += `<tr><td><input type="radio" id="address${y}" name="addressRad" value="${y}" /></td>
            `;
            //add data to output
            adString += `<td><p>Address ${parseInt(y) + 1} : ${addressArray[y].addr},
            ${addressArray[y].city}, ${addressArray[y].prov}</br> ${addressArray[y].post}`;
            if(addressArray[y].appt != null){
                adString += ` Appt: ${addressArray[y].appt}`;
            }
            adString += `</p></br></td></tr>
            `;
        }
            adString += `<tr>
                    <td colspan="2"><p>Want to enter a new address? Do so here!</p></td>
                </tr>
                <tr>
                    <td>Address: <input id="addInput" name="addInput" type="text"/><br/></td>
                    <td>City: <input id="cityInput" name="cityInput" type="text"/><br/></td>
                </tr>
                <tr>
                    <td>Prov: <input id="provInput" name="provInput" type="text"/><br/></td>
                    <td>Postal Code: <input id="postInput" name="postInput" type="text"/><br/></td>
                </tr>
                <tr>
                    <td>Appt#(optional): <input id="apptInput" name="apptInput" type="text"/><br/></td>
                    <td><input id="subButton2" name="subButton2" type="submit"/></td>
                </tr>
            </table>
        </FORM>`;
        $("#outputDiv").append(adString);
    } else { //else if no previous addresses set, show user just address entry form
        //output code
        adString += `<FORM method="POST" id="addressForm">
        <table id="addTable" name="addTable">
            <tr>
                <td colspan="2"><h4>Please enter a new address</h4></td>
            </tr>
            <tr>
                <td>Address: <input id="addInput" name="addInput" type="text"/><br/></td>
                <td>City: <input id="cityInput" name="cityInput" type="text"/><br/></td>
            </tr>
            <tr>
                <td>Prov: <input id="provInput" name="provInput" type="text"/><br/></td>
                <td>Postal Code: <input id="postInput" name="postInput" type="text"/><br/></td>
            </tr>
            <tr>
                <td>Appt#(optional): <input id="apptInput" name="apptInput" type="text"/><br/></td>
                <td><input id="subButton2" name="subButton2" type="submit"/></td>
            </tr>
        </table>
    </FORM>`;

        $("#outputDiv").append(adString);
    }
    //AJAX here to set up submit button to move forward ALSO to initialize global object
    $("#addressForm").submit(function(event){
        console.log("submitting address!");
        var selAddress = $("input[name='addressRad']:checked").val();
        if(selAddress !== undefined){
            currentOrder.address = addressArray[selAddress];
            console.log(addressArray[selAddress]);
            currentOrder.addressInfo = "old";
            beginOrder({status:"selectedAddress"});
        } else {
            //post php
            currentOrder.addressInfo = "new";
            console.log("going to address.php");
            $.post("php/address.php", $(this).serialize(), beginOrder);
            event.preventDefault();
        }
    });
    //AJAX here so radio buttons send selected address to global object
}
//Step 3
var beginOrder = function(res) {
    console.log("back from address.php or sel");
    if (typeof(res) !== undefined) {
        //User added new address, make global object change - address to response data
        console.log(res['status']);
        if(res.status == "Invalid") {
            $("#error").html(`<h4 class="errortext">Please enter a valid address!</h4>`);
            displayAddressPage(); //This sorta locks the user into entering new addresses if they attempt but fail
            //could optionally add in some more code to repopulate the address fields.
        } else {
            //In this case, they selected an old address
            if(currentOrder.addressInfo == "old"){
                //page change on outputDiv here
                console.log(currentOrder);
                $("#outputDiv").html("<h1>Riley's code goes here</h1>");
            } else { //In this case the entered a new address
                currentOrder.address = res;
                //page change on outputDiv here
                console.log(currentOrder);
                $("#outputDiv").html("<h1>Riley's code goes here</h1>");
            }
        }        
    } else {
        console.log("NANI! ... well, this is what I have");
        console.log(currentOrder);
    }    
}
