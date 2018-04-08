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

/*  Definition of Pizza Class
    Purpose: To encapsulate all the qualities of a pizza for both temporary storage and then to conveniently send data to MySQL server upon order completion.
*/
class Pizza {
    constructor(){
        this.size = " ";
        this.dough = " ";
        this.sauce = " ";
        this.cheese = " ";
        this.toppings = " "; //we don't actually need to do anything special with toppings, so just store it as a string!
    }
    toString(){
        console.log(`Size: ${this.size}  Dough: ${this.dough}  Sauce: ${this.sauce} 
        Cheese: ${this.cheese} Toppings: ${this.toppings}`);
    }
    /*Riley's Getters for use with accessing pizza information*/
    getSize(){
        return this.size;
    }

    getDough(){
        return this.dough;
    }

    getSauce(){
        return this.sauce;
    }

    getCheese(){
        return this.cheese;
    }

    getToppings(){
        return this.toppings;
    }
}

var pizzaOrders = new Array();

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
            adString += `</p></br></td></tr>`;
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
            /* Clear any error previously displayed */
            $("#error").html('');
            //In this case, they added a new address, so I need to actually add that to the global object.
            if(currentOrder.addressInfo == "new"){
                currentOrder.address = res;
            }
            console.log(currentOrder); //size dough sauce cheese
            $("#outputDiv").html(`<h1>Pizza Order Time!</h1>
        <h3>Please select one of each option below!</h3>
    <form id="pizzaForm" name="pizzaForm">
        <label for="selSize">Size: </label>
        <select id="selSize" name="selSize">
            <option value="small" selected="selected">Small</option>
            <option value="medium">Medium</option>
            <option value="large">Large</option>
            <option value="xlarge">Extra Large</option>
        </select> 
        <br/>
        <label for="selDough">Dough: </label>
        <select id="selDough" name="selDough">
            <option value="white" selected="selected">White</option>
            <option value="whole">Whole Wheat</option>
            <option value="gluten">Gluten Free</option>
            <option value="newyork">New York Style</option>
            <option value="neapolitan">Neapolitan</option>
            <option value="sicilian">Sicilian</option>
        </select> 
        <br/>
        <label for="selSauce">Sauce: </label>
        <select id="selSauce" name="selSauce">
            <option value="marinara" selected="selected">Marinara</option>
            <option value="alfredo">Alfredo</option>
            <option value="bbq">BBQ Sauce</option>
            <option value="salsa">Salsa</option>
            <option value="bechamel">Bechamel</option>
            <option value="hummus">Hummus</option>
            <option value="ranch">Ranch</option>
            <option value="garlic">Garlic Olive Oil</option>
            <option value="balsamic">Balsamc Glaze</option>
        </select> 
        <br/>
        <label for="selCheese">Cheese: </label>
        <select id="selCheese" name="selCheese">
            <option value="mozzarella" selected="selected">Mozzarella</option>
            <option value="cheddar">Cheddar Blend</option>
            <option value="7cheese">7-Cheese Mix</option>
            <option value="provolone">Provolone</option>
            <option value="gouda">Gouda</option>
            <option value="goat">Goat</option>
            <option value="gruyere">Gruyere</option>
            <option value="italian">Italian Blend</option>
        </select> 
        <br/>
        <input type="submit" id="opSubmit" name="opSubmit" value="Submit">
    </form>`);
            $("#pizzaForm").submit(function(event){
                var newPizza = new Pizza();
                newPizza.size = $("#selSize").val();
                newPizza.dough = $("#selDough").val();
                newPizza.sauce = $("#selSauce").val();
                newPizza.cheese = $("#selCheese").val();
                console.log("new pizza: ");
                newPizza.toString();
                toppingPage(newPizza);
            });
        }        
    } else {
        console.log("NANI! ... well, this is what I have");
        console.log(currentOrder);
    }    
}
//Step 4
function toppingPage(newPizza) {
    $("#outputDiv").html(`
    <h1>Pizza Order Time!</h1>
    <h3>Please select up to 7 toppings below!</h3>
    <form id="toppingForm" name="toppingForm">
        <label for="chkPepperoni">Pepperoni</label>
        <input type="checkbox" id="chkPepperoni" name="toppings" value="pepperoni"/><br/>

        <label for="chkHam">Ham</label>
        <input type="checkbox" id="chkHam" name="toppings" value="ham"/><br/>

        <label for="chkChicken">Chicken</label>
        <input type="checkbox" id="chkChicken" name="toppings" value="chicken"/><br/>

        <label for="chkBacon">Bacon</label>
        <input type="checkbox" id="chkBacon" name="toppings" value="bacon"/><br/>

        <label for="chkBeef">Ground Beef</label>
        <input type="checkbox" id="chkBeef" name="toppings" value="beef"/><br/>

        <label for="chkItSausage">Italian sausage</label>
        <input type="checkbox" id="chkItSausage" name="toppings" value="italian sausage"/><br/>

        <label for="chkHotSausage">Hot Sausage</label>
        <input type="checkbox" id="chkHotSausage" name="toppings" value="hot sausage"/><br/>

        <label for="chkRedOnion">Red Onion</label>
        <input type="checkbox" id="chkRedOnion" name="toppings" value="red onion"/><br/>

        <label for="chkSauOnion">Sauteed Onion</label>
        <input type="checkbox" id="chkSauOnion" name="toppings" value="sauteed onion"/><br/>

        <label for="chkGrePepper">Green Pepper</label>
        <input type="checkbox" id="chkGrePepper" name="toppings" value="green pepper"/><br/>

        <label for="chkHotPeppers">Hot Peppers</label>
        <input type="checkbox" id="chkHotPeppers" name="toppings" value="hot pepper"/><br/>

        <label for="chkMushrooms">Mushrooms</label>
        <input type="checkbox" id="chkMushrooms" name="toppings" value="mushroom"/><br/>

        <label for="chkPineapple">Pineapple</label>
        <input type="checkbox" id="chkPineapple" name="toppings" value="pineapple"/><br/>

        <label for="chkAnchovies">Anchovies</label>
        <input type="checkbox" id="chkAnchovies" name="toppings" value="anchovies"/><br/>

        <label for="chkOlives">Olives</label>
        <input type="checkbox" id="chkOlives" name="toppings" value="olives"/><br/>

        <label for="chkTomatoes">Sundried Tomatoes</label>
        <input type="checkbox" id="chkTomatoes" name="toppings" value="tomatoes"/><br/>
        <br/>
        <input type="submit" id="toppingSubmit" name="toppingSubmit" value="Submit">
    </form>`);
    $("#toppingForm").submit(function(event){
        var items = [];
        $( "input[type=checkbox]:checked" ).each(function(){
            items.push($(this).val());
        });
        items.forEach(function(item, index){
            newPizza.toppings += item + ".";
        });
        console.log("Here's the current Order:");
        console.log(currentOrder);
        console.log("Heres the current pizza");
        console.log(newPizza);
        pizzaOrders.push(newPizza);
        orderCheckPage();
    });
}

function orderCheckPage() { //ORDER CHECK PAGE - Riley
    console.log(pizzaOrders.length);
    var output = "<h1>Current Order Information</h1>";
    //create the table header
    output += "<table><tr><th>Size</th><th>Dough</th><th>Sauce</th><th>Cheese</th><th>Toppings</th></tr>";
    //go through all the pizzas added to the order
    for(var x = 0; x<pizzaOrders.length; ++x){ 
        //utilize getters to get the information from each pizza
        var size = pizzaOrders[x].getSize();
        var dough = pizzaOrders[x].getDough();
        var sauce = pizzaOrders[x].getSauce();
        var cheese = pizzaOrders[x].getCheese();
        var toppings = pizzaOrders[x].getToppings();
        var toppings = toppings.split(".");
        //add the information to an HTML table
        output += "<tr><td>"+size+"</td><td>"+dough+"</td><td>"+sauce+"</td><td>"+cheese+"</td><td>";
        for(var i = 0; i<toppings.length-1; ++i){
            output += toppings[i]+"</br>";
        }
        output += "</td></tr>";
    }
    output += "</table>"; 

    //create all the forms for the buttons
    output += "<form id='anotherForm' name='anotherForm'> <input type='submit' id='anotherSubmit' name='anotherSubmit' value='Add Another Pizza'/> </form></br>";
    output += "<form id='removeForm' name='removeForm'> <input type='submit' id='removeSubmit' name=removeSubmit' value='Remove then Add'/> </form></br>";
    output += "<form id='completeWithoutForm' name='completeWithoutForm'> <input type='submit' id='completeWithoutSubmit' name='completeWithoutSubmit' value='Complete the order without the most recent pizza'></form></br>"
    output += "<form id='completeForm' name='completeForm'> <input type='submit' id='completeSubmit' name='completeSubmit' value='Complete'/></form></br>";
    output += "<form id='cancelForm' name='cancelForm'> <input type='submit' id='cancelSubmit' name='cancelSubmit' value='Cancel'/></form></br>";
    $("#outputDiv").html(output); //add all the information to the HTML div
    $("#anotherForm").submit(function(event){ //functionality for adding another pizza
        var res = {
            status: 'YES'
        };
        beginOrder(res);
    });
    $("#removeForm").submit(function(event){ //functionality for removing the latest pizza and adding another one
        pizzaOrders.pop();
        var res = {
            status: 'YES'
        };
        beginOrder(res);
    });
    $("#completeWithoutForm").submit(function(event){ //functionality for completing without the most recent pizza
        pizzaOrders.pop();
        summaryPage();
    });
    $("#completeForm").submit(function(event){ //complete the current order
        summaryPage();
    });
    $("#cancelForm").submit(function(event){ //cancel the current order and return to the main page
        pizzaOrders.length = 0;
        loadFirstPage();
    });
}

function summaryPage(){
    //summary page displaying the order - Riley
    var output = '<h1>Summary Page</h1>'
    output += "<table><tr><th>Size</th><th>Dough</th><th>Sauce</th><th>Cheese</th><th>Toppings</th></tr>";
    for(var x = 0; x<pizzaOrders.length; ++x){
        var size = pizzaOrders[x].getSize();
        var dough = pizzaOrders[x].getDough();
        var sauce = pizzaOrders[x].getSauce();
        var cheese = pizzaOrders[x].getCheese();
        var toppings = pizzaOrders[x].getToppings();
        var toppings = toppings.split(".");
        output += "<tr><td>"+size+"</td><td>"+dough+"</td><td>"+sauce+"</td><td>"+cheese+"</td><td>";
        for(var i = 0; i<toppings.length-1; ++i){
            output += toppings[i]+"</br>";
        }
        output += "</td></tr>";
    }
    output += "</table>";

    //TODO: display delivery information
    //TODO: Display options for placing or canceling order
    $("#outputDiv").html(output);
}