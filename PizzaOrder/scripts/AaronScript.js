

function loadFirstPage(){
    document.getElementById("outputDiv").innerHTML = `        <h3>Welcome!</h3>
    <p>Thanks for choosing our store! <br><br>Please enter your email address below to begin placing your order!</p>
    <FORM method="POST" id="introForm">
        <input id="emailInput" name="emailInput" type="text"/><br/>
        <input id="subButton1" name="subButton1" type="submit"/>
    </FORM>`;

    $("#introForm").submit(function(event){
        //add logic here to test for if user entered no email address or invalid, to reload page, else run below
        $.post("php/intro.php", $(this).serialize(), onEntry);
        event.preventDefault();
    })
}

var onEntry = function(response){
 if(response.status == "None"){
    $("#error").html("No posts available");
 } else {
    //$("#outputDiv").append(response.status);
    //currently just shows the email address. change intro.php to run queries on valid email addresses. Then here will be a function call to the next function that will change the outputDiv to the content for the next page.
    $("#test").append(response.userEmail);
 }
};