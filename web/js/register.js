//document.write("JS file Working");


//Form Values
var FirstName = document.getElementById("FirstName");
var LastName = document.getElementById("LastName");
var Email = document.getElementById("Email");
var Password = document.getElementById("Password");
var ConfirmPassword = document.getElementById("ConfirmPassword");
var submitButton = document.getElementById("submitRegister");


//Listen for Key Press
FirstName.addEventListener("keypress", valueCheck);
LastName.addEventListener("keypress", valueCheck);
Email.addEventListener("keypress", valueCheck);
Password.addEventListener("keypress", valueCheck);
ConfirmPassword.addEventListener("keypress", valueCheck);

//Listen for Mouse Over
FirstName.addEventListener("mouseover", valueCheck);
LastName.addEventListener("mouseover", valueCheck);
Email.addEventListener("mouseover", valueCheck);
Password.addEventListener("mouseover", valueCheck);
ConfirmPassword.addEventListener("mouseover", valueCheck);
submitButton.addEventListener("mouseover", valueCheck);


//Master Check
function valueCheck()
{
	//Check First Name
	if(allLetter(FirstName)){
		document.getElementById("firstTest").style.color = "#00ff00";
		document.getElementById("firstTest").textContent = 'First Name Passed';
	}
	else{
		document.getElementById("firstTest").style.color = "#ff0000";
		document.getElementById("firstTest").textContent = 'First Name Failed! Must only be Letters';
	}
	
	//Check Last Name
	if(allLetter(LastName)){
		document.getElementById("lastTest").style.color = "#00ff00";
		document.getElementById("lastTest").textContent = 'Last Name Passed';
	}
	else{
		document.getElementById("lastTest").style.color = "#ff0000";
		document.getElementById("lastTest").textContent = 'Last Name Failed! Must only be Letters';
	}
	
	//Check Email
	if(ValidateEmail(Email)){
		document.getElementById("emailTest").style.color = "#00ff00";
		document.getElementById("emailTest").textContent = 'Valid Cal State Fullerton Email';
	}
	else{
		document.getElementById("emailTest").style.color = "#ff0000";
		document.getElementById("emailTest").textContent = 'Invalid Cal State Fullerton Email';
	}
	
	//Check Password
	if(passid_validation(Password,7,12)){
		document.getElementById("passTest").style.color = "#00ff00";
		document.getElementById("passTest").textContent = 'Password Passed';
	}
	else{
		document.getElementById("passTest").style.color = "#ff0000";
		document.getElementById("passTest").textContent = 'Password Failed with need between 7 to 12 characters';
	}
	
	//Check Confirm Password
	if(Password.value==ConfirmPassword.value)
	{
		document.getElementById("passConfirmTest").style.color = "#00ff00";
		document.getElementById("passConfirmTest").textContent = 'Matches Password';
	}
	else{
		document.getElementById("passConfirmTest").style.color = "#ff0000";
		document.getElementById("passConfirmTest").textContent = 'Does Not Match Password';
	}
	
}


//Testing Letters Only
function allLetter(name)
{
	var letters = /^[A-Za-z]+$/;
	//alert("Alphabet");
	if(name.value.match(letters))
	{
		//alert("Alphabet Pass");
		return true;
	}
	else
	{
		//alert("First and Last Name can only be Letters");
		return false;
	}
}


//Testing Email
function ValidateEmail(uemail)
{
	var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	if(uemail.value.match(mailformat))
	{
		if ( (/\@csu.fullerton.edu\s*$/).test(uemail.value)) 
		{
			//alert("Valid Email Address");
			return true;
		}
		else
		{
			//alert("Not Cal State Fullerton Email");
			return false;
		}
	}
	else
	{
		//alert("Invalid Email Address");
		return false;
	}
} 

//Testing Password
function passid_validation(passid,max,min)
{
	var passid_len = passid.value.length;
	if (passid_len == 0 ||passid_len >= min || passid_len < max)
	{
		//alert("Password should not be empty / length be between "+max+" - "+min);
		return false;
	}
	return true;
}



