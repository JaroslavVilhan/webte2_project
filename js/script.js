function changeLoginType() {
    if(document.getElementById("option1").checked){
        document.getElementById("loginForm").action="login.php?method=ldap"
    }else if(document.getElementById("option2").checked){
        document.getElementById("loginForm").action="login.php?method=local"
    }else {
        document.getElementById("loginForm").action = "login.php?method=admin"
    }
}

function selectOption(){
    var value = $( "#sel1" ).val();
    switch (value) {
        case "1":{
            $('#selForm1').show();
            $('#selForm2').hide();
            $('#selForm3').hide();
            break;
        }
        case "2":{
            $('#selForm1').hide();
            $('#selForm2').show();
            $('#selForm3').hide();
            break;
        }
        case "3":{
            $('#selForm1').hide();
            $('#selForm2').hide();
            $('#selForm3').show();
            break;
        }
    }
}

function showInputForNewCourse(){
    $('#selForm1Hidden').show();
}

$(document).ready(function(){
    $('#selForm1').hide();
    $('#selForm2').hide();
    $('#selForm3').hide();

    $('#selForm1Hidden').hide();
    $('#form1predmet2').hide();

    $("#form2predmet").hide();
    $("#form3predmet").hide();


    $('#form1rok').on('change', function () {
        var selectData = $(this).val();
        if(selectData[0] == 'L'){
            $('#form1predmet').hide();
            $('#form1predmet2').show();
            $('#form1hidden').attr('value', "form1course2");

        }else{
            $('#form1predmet').show();
            $('#form1predmet2').hide();
            $('#form1hidden').attr('value', "form1course");
        }
        // $.ajax({
        //     type: "POST",
        //     data: {'form1Ajax': selectData },
        // });
    });

    $('#form2rok').on('change', function () {
        $("#form2predmet").show();
        $("#form2predmet").val('1');
        var selectYear = $(this).val();

        if(selectYear != "1") {
            $("#form2predmet option[hideopt='1']").hide();
            $("#form2predmet option[year='" + selectYear + "']").show();
        }
    });

    $('#form3rok').on('change', function () {
        $("#form3predmet").show();
        $("#form3predmet").val('1');
        var selectYear = $(this).val();

        if(selectYear != "1") {
            $("#form3predmet option[hideopt='1']").hide();
            $("#form3predmet option[year='" + selectYear + "']").show();
        }
    });
});