function passify() {
    var check=document.getElementById('eyecon').getAttribute('class');
    if (check=="fas fa-eye-slash") {
        document.getElementById('eyecon').setAttribute('class','fas fa-eye');
        document.getElementById('password').setAttribute('type','text');
    }else{
        document.getElementById('eyecon').setAttribute('class','fas fa-eye-slash');
        document.getElementById('password').setAttribute('type','password');       
    }
    document.getElementById('password').focus();
}

function filter() {
    var check=document.getElementById('category').value;
    if (null !==document.getElementById('category').value) {
        var cat=document.getElementById('category').value;
    }
}