$('#firstname').bind('keyup blur',function(){
    var name=$('#firstname').val();
    if(name.length < 3 || name.length > 20){
        document.getElementById("firstname").style="border: 2px solid red";
    }
    else{
        document.getElementById("firstname").style="border: 1px solid #ced4da";
    }
});

$('#lastname').bind('keyup blur',function(){
    var lastname=$('#lastname').val();
    if(lastname.length < 3 || lastname.length > 20){
        document.getElementById("lastname").style="border: 2px solid red";
    }
    else{
        document.getElementById("lastname").style="border: 1px solid #ced4da";
    }
});

//if password is less than 8 characters display red border
$('#password').bind('keyup blur',function(){
    var password=$('#password').val();
    if(password.length < 8){
        document.getElementById("password").style="border: 2px solid red";
    }
    else{
        document.getElementById("password").style="border: 1px solid #ced4da";
    }
});
  
//check if password is confirmed correctly
$('#password_confirm').bind('keyup blur',function(){ 

    var password=$('#password').val();
    var cPassword=$(this).val();
    if(password!==cPassword){
        document.getElementById("password_confirm").style="border: 2px solid red";
    }
    else{
        document.getElementById("password_confirm").style="border: 1px solid #ced4da";
    }
});