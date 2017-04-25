function loginButton(){
    var email = $("#inputEmail").val();
    var password = $("#inputPassword").val();
    
    
    var emailCheck = email.indexOf("@csu.fullerton.edu") >= 0;
    var passwordCheck = password.length > 0;
    
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
    
    
    if(emailCheck && passwordCheck){
        $("#login-form").submit();
    }
}

$(document).keypress(function(e) {
    if(e.which == 13) {
        loginButton();
    }
});