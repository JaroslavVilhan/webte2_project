function changeLoginType() {
    if(document.getElementById("option1").checked){
        document.getElementById("loginForm").action="login.php?method=ldap"
    }else if(document.getElementById("option2").checked){
        document.getElementById("loginForm").action="login.php?method=local"
    }else {
        document.getElementById("loginForm").action = "login.php?method=admin"
    }
}