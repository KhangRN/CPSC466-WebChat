function registerButton(){
    var firstName = $("#first-name-input").val();
    var lastName = $("#last-name-input").val();
    var email = $("#email-input").val();
    var major = $("#major-input").val();
    var password = $("#password-input").val();
    var passwordMatch = $("#password-match-input").val();
    
    var firstCheck = firstName.length > 0;
    var lastCheck = lastName.length > 0;
    var emailCheck = email.indexOf("@csu.fullerton.edu") >= 0;
    var passwordCheck = password.length > 0;
    var passwordMatchCheck = password === passwordMatch;
    
    if(!firstCheck){
        $("#first-name-error").css('display', 'block');
    }
    else{
        $("#first-name-error").css('display', 'none');
    }
    
    if(!lastCheck){
        $("#last-name-error").css('display', 'block');
    }
    else{
        $("#last-name-error").css('display', 'none');
    }
    
    if(!emailCheck){
        $("#email-error").css('display', 'block');
    }
    else{
        $("#email-error").css('display', 'none');
    }
    
    if(!passwordCheck){
        $("#password-error").css('display', 'block');
    }
    else{
        $("#password-error").css('display', 'none');
    }
    
    if(!passwordMatchCheck){
        $("#password-match-error").css('display', 'block');
    }
    else{
        $("#password-match-error").css('display', 'none');
    }
    
    if(firstCheck && lastCheck && emailCheck && passwordCheck && passwordMatchCheck){
        $("#register-form").submit();
    }
}

$(document).keypress(function(e) {
    if(e.which == 13) {
        registerButton();
    }
});