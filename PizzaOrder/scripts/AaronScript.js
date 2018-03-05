

function loadFirstPage(){
    document.getElementById("outputDiv").innerHTML = `        <h3>Welcome!</h3>
    <p>Thanks for choosing our store! <br><br>Please enter your email address below to begin placing your order!</p>
    <FORM method="POST" id="introForm">
        <input id="emailInput" name="emailInput" type="text"/><br/>
        <input id="subButton1" name="subButton1" type="submit"/>
    </FORM>`;

    $("#introForm").submit(function(event){
        $.post("php/intro.php", $(this).serialize(), onEntry);
        event.preventDefault();
    })
}

var onEntry = function(response){
 if(response.status == "None"){
    $("#error").html("No posts available");
 } else {
    //$("#outputDiv").append(response.status);
    $("#test").append(response.userEmail);
 }
};