/**
 * TODO: Beschreibung erstellen
 */
// Single point of Ajax entry   
var ajaxEntry = "ajax.php";

// Test if user is online, if then show chat, else hide
$(document).ready(function() {    
    $.post(ajaxEntry, {
        action: "user"
    }, function (response){
        if (response == "Not logged in"){
            $("#imbaUsers").hide();
            $("#imbaMessagesDialog").hide();
            $("#imbaContentDialog").hide();
        } 
    })
});

String.prototype.format = function() {
    var formatted = this;
    for(arg in arguments) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};