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

function adminSendPoints(team, tableName){
    var value = $("#"+ team ).val();
    value = parseInt(value);
    $("#"+ team ).val(value);

    if(value > -1) {
        $.ajax({
            type: 'POST',
            url: '../../ajaxCalls.php?f=processPointsFromAdmin',
            data: { team: team, tableName: tableName, value: value },
            success: function (response) {
                alert(response);
            },
        });

        // $.ajax({
        //     type: 'POST',
        //     url: '../../AjaxCalls/processPointsFromAdmin.php',
        //     dataType: 'json',
        //     //contentType: "application/json; charset=utf-8",
        //     data: { team: team, tableName: tableName, value: value },
        //     success: function (response) {
        //         console.log(response);
        //     },
        // });

    }
    else {
        $("#"+ team ).val('');
        alert("Neplatný vstup ! \nInvalid input !");
    }
}

function adminCheckCells(team, table) {
    var result="";

    $.ajax({
        async: false,
        type: 'POST',
        url: '../../ajaxCalls.php?f=adminCheckFilledCells',
        data: { team: team, tableName: table },
        success: function (response) {
            if(response == 'yes'){
                result=true;
            }else{
                result=false;
            }
        },
    });

     return result;
}

function studentCheckCells(team, table) {
    var result="";

    $.ajax({
        async: false,
        type: 'POST',
        url: '../../ajaxCalls.php?f=studentCheckFilledCells',
        data: { team: team, tableName: table },
        success: function (response) {
            if(response == 'yes'){
                result=true;
            }else{
                result=false;
            }
        },
    });
    return result;
}

function adminSendAgree(state, team, table, messID, ButtID, lang) {
    if(adminCheckCells(team, table)) {
        $.ajax({
            type: 'POST',
            url: '../../ajaxCalls.php?f=setAgreeFromAdmin',
            data: {team: team, tableName: table, state: state},
            // success: function (response) {
            //     alert(response);
            // },
        });
        if (lang == 'en' && state == 'Y') {
            $('#' + messID).append("<i class=\"far fa-thumbs-up\"></i> You agree with the students divided points");
        } else if (lang == 'en' && state == 'N') {
            $('#' + messID).append("<i class=\"far fa-thumbs-down\"></i> You don't agree with the students divided points");
        } else if (lang == 'sk' && state == 'Y') {
            $('#' + messID).append("<i class=\"far fa-thumbs-up\"></i> Súhlasíte z rozdelením bodov medzi študentami");
        } else {
            $('#' + messID).append("<i class=\"far fa-thumbs-down\"></i> Nesúhlasíte z rozdelením bodov medzi študentami");
        }

        $('#' + ButtID).hide();
    }else {
        alert("Ešte neboli vyplnené všetky polia ! \nNot all cells have been filled in yet !");
    }
}

function studentSendAgree(state, id, team, table, messID, ButtID, lang) {
    if(studentCheckCells(team, table)) {

        if(lang == 'sk') {
            bootbox.confirm({
                message: "Prosím potvrdte možnosť...",
                locale: 'sk',
                callback: function (result) {
                    if (result == true){
                        $.ajax({
                            type: 'POST',
                            url: '../../ajaxCalls.php?f=setAgreeFromStudent',
                            data: {tableName: table, state: state, id: id},
                        });
                        if (state == 'Y') {
                            $("td[class=" + id + "][data-ord=4]").append("<i class=\"far fa-thumbs-up\"></i>");
                        } else {
                            $("td[class=" + id + "][data-ord=4]").append("<i class=\"far fa-thumbs-down\"></i>");
                        }

                        $("td[class=" + id + "][data-ord=3] input.inputStudentValue").attr("disabled", true);
                        $("td[class=" + id + "][data-ord=3] input.pointsButt").hide();
                        $('#' + ButtID).hide();
                    }
                }
            });
        }else {
            bootbox.confirm({
                message: "Are you sure?",
                locale: 'en',
                callback: function (result) {
                    if (result == true){
                        $.ajax({
                            type: 'POST',
                            url: '../../ajaxCalls.php?f=setAgreeFromStudent',
                            data: {tableName: table, state: state, id: id},
                        });
                        if (state == 'Y') {
                            $("td[class=" + id + "][data-ord=4]").append("<i class=\"far fa-thumbs-up\"></i>");
                        } else {
                            $("td[class=" + id + "][data-ord=4]").append("<i class=\"far fa-thumbs-down\"></i>");
                        }

                        $("td[class=" + id + "][data-ord=3] input.inputStudentValue").attr("disabled", true);
                        $("td[class=" + id + "][data-ord=3] input.pointsButt").hide();
                        $('#' + ButtID).hide();
                    }
                }
            });
        }

    }else {
        if(lang == 'sk'){
            bootbox.alert("<b>Chyba</b>: Nevyplnené polia alebo nesedí súčet !");
        }else {
            bootbox.alert("<b>Error</b>: Not filled cells or bad sum !");
        }
    }
}


function prepareData(){
    var array = ($( "td" ).toArray());
    var userId=[];
    var k=0;
    for (var i=0; i<array.length; ++i){
        if(array[i].attributes[2].nodeValue == 0){
            userId[k]=array[i].textContent;
            ++k;
        }
    }
    return userId;
}

function studentAgree(table, id) {
    var result="";

    $.ajax({
        async: false,
        type: 'POST',
        url: '../../ajaxCalls.php?f=getStudentAgree',
        data: { id: id, tableName: table },
        success: function (response) {
            if(response == 'yes'){
                result=true;
            }else{
                result=false;
            }
        },
    });
    return result;
}

function manageAdminAjaxCalls(tableName){
    var studentIds = prepareData();

    setInterval(function () {
        for (var i=0; i<studentIds.length; ++i) {
            $("td[class="+studentIds[i]+"][data-ord=3]").load('../../ajaxCalls.php?f=getStudentPointsToAdmin&id='+studentIds[i]+'&table='+tableName);
        }
        for (var i=0; i<studentIds.length; ++i) {
            $("td[class="+studentIds[i]+"][data-ord=4]").load('../../ajaxCalls.php?f=getStudentAgreeToAdmin&id='+studentIds[i]+'&table='+tableName);
        }
    }, 3000);
}

function studentSendPoints(id, team, table, lang) {
    if(!studentAgree(table, id)) {
        var value = $("td[class=" + id + "][data-ord=3] input.inputStudentValue").val();
        value = parseInt(value);
        if (isNaN(value)) {
            $("td[class=" + id + "][data-ord=3] input.inputStudentValue").val('');
        } else {
            $("td[class=" + id + "][data-ord=3] input.inputStudentValue").val(value);
        }


        if (value > -1) {
            $.ajax({
                type: 'POST',
                url: '../../ajaxCalls.php?f=processPointsFromStudent',
                data: {tableName: table, value: value, id: id},
                success: function (response) {
                    if(lang == 'sk'){
                        bootbox.alert("Body boli vložené !");
                    }else {
                        bootbox.alert("The points have been added !");
                    }
                },
            });

        }
        else {
            $("td[class=" + id + "][data-ord=3] input.inputStudentValue").val('');
        }
    }
}

function manageStudentsAjaxCalls(team, tableName, messID, lang, studentID){
    var studentIds = prepareData();

    // setTimer1 = setInterval(function(id){
    //     for (var i = 0; i < studentIds.length; ++i) {
    //         if(!studentAgree(tableName, studentIds[i])) {
    //             var value = $("td[class=" + studentIds[i] + "][data-ord=3] input").val();
    //             value = parseInt(value);
    //             if (isNaN(value)) {
    //                 $("td[class=" + studentIds[i] + "][data-ord=3] input").val('');
    //             } else {
    //                 $("td[class=" + studentIds[i] + "][data-ord=3] input").val(value);
    //             }
    //
    //
    //             if (value > -1) {
    //                 $.ajax({
    //                     type: 'POST',
    //                     url: '../../ajaxCalls.php?f=processPointsFromStudent',
    //                     data: {tableName: tableName, value: value, id: studentIds[i]},
    //                     // success: function (response) {
    //                     //     alert(response);
    //                     // },
    //                 });
    //
    //             }
    //             else {
    //                 $("td[class=" + studentID + "][data-ord=3] input").val('');
    //             }
    //         }
    //     }
    // },1000,(1));

    setTimer3 = setInterval(function(id){
        for (var i=0; i<studentIds.length; ++i) {
            $("td[class=" + studentIds[i] + "][data-ord=4]").load('../../ajaxCalls.php?f=getStudentAgreeToAdmin&id=' + studentIds[i] + '&table=' + tableName);

            $.ajax({
                async: false,
                type: 'POST',
                url: '../../ajaxCalls.php?f=getStudentAgree',
                data: { id: studentIds[i], tableName: tableName },
                success: function (response) {
                    if(response == 'yes'){
                        $("td[class=" + studentIds[i] + "][data-ord=3] input.inputStudentValue").attr("disabled", true);
                        $("td[class=" + studentIds[i] + "][data-ord=3] input.pointsButt").hide();
                    }
                },
            });
        }

        $('#' + messID).load('../../ajaxCalls.php?f=getAgreeFromAdmin&team='+team+'&table='+tableName+'&lang='+lang);
    },3000,(3));

    setTimer0 = setInterval(function(id){
        for (var i = 0; i < studentIds.length; ++i) {
            if(!studentAgree(tableName, studentIds[i])) {
                //$("td[class=" + studentIds[i] + "][data-ord=3] input").load('../../ajaxCalls.php?f=getStudentPointsToAdmin&id=' + studentIds[i] + '&table=' + tableName);
                $.ajax({
                    async: false,
                    type: 'POST',
                    url: '../../ajaxCalls.php?f=getStudentPointsToAdmin2',
                    data: { id: studentIds[i], table: tableName },
                    success: function (response) {
                        $("td[class=" + studentIds[i] + "][data-ord=3] input.inputStudentValue").val(response);
                    },
                });
            }
        }
    },6000,(0));
}

function hideSelectCell(){
    $('#selForm').hide();
}

function viewGraph(lang, p1, p2, p3, p4){
    am4core.ready(function() {
        console.log(p1);
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chartdiv", am4charts.PieChart);

        if(lang == 'sk') {
            chart.data = [{
                "country": "Počet študentov",
                "litres": p1
            }, {
                "country": "Počet súhlasiacich študentov",
                "litres": p2
            }, {
                "country": "Počet nesúhlasiacich študentov",
                "litres": p3
            }, {
                "country": "Počet nevyjadrených študentov",
                "litres": p4
            }];
        }else {
            chart.data = [{
                "country": "Number of students",
                "litres": p1
            }, {
                "country": "Student agree",
                "litres": p2
            }, {
                "country": "Student disagree",
                "litres": p3
            }, {
                "country": "Students with no response",
                "litres": p4
            }];
        }
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "litres";
        pieSeries.dataFields.category = "country";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeWidth = 2;
        pieSeries.slices.template.strokeOpacity = 1;

        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;

    });
}

function viewGraph2(lang, p1, p2, p3, p4){
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chartdiv2", am4charts.PieChart);


        if(lang == 'sk') {
            chart.data = [{
                "country": "Počet tímov",
                "litres": p1
            }, {
                "country": "Počet uzavretých tímov",
                "litres": p2
            }, {
                "country": "Počet neuzavretých tímov",
                "litres": p3
            }, {
                "country": "Počet tímov s nekompletnými vyjadreniami",
                "litres": p4
            }];
        }else {
            chart.data = [{
                "country": "Number of teams",
                "litres": p1
            }, {
                "country": "Closed teams",
                "litres": p2
            }, {
                "country": "Unclosed teams",
                "litres": p3
            }, {
                "country": "Teams with no complete answers",
                "litres": p4
            }];
        }

        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "litres";
        pieSeries.dataFields.category = "country";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeWidth = 2;
        pieSeries.slices.template.strokeOpacity = 1;

        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;

    });
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

    $('#sendForm').submit(function() {
        var string = $(".ql-editor").html();
        document.cookie = "var1="+string;
    });

    $(document).ready(function() {
        $('#historyTable').DataTable();
    });

});