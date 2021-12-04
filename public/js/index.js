$(function () {


    $('#bouton-légumes').click(function () {
        if ($("#printemps").css('display') == 'block') {
            $("#légumes-prin").show();
            $('#fruits-prin').hide();
        }

        else if ($("#hiver").css('display') == 'block') {
            $("#légumes-hiv").show();
            $('#fruits-hiv').hide();
        }

        else if ($("#ete").css('display') == 'block') {
            $("#légumes-ete").show();
            $('#fruits-ete').hide();
        }

        else {
            $("#légumes-aut").show();
            $('#fruits-aut').hide();

        }
    });


    $('#bouton-fruits').click(function () {
        if ($("#hiver").css('display') == 'block') {

            $("#légumes-hiv").hide();
            $("#fruits-hiv").css("display", "flex");

        }
        else if ($("#printemps").css('display') == 'block') {

            $('#légumes-prin').hide();
            $("#fruits-prin").css("display", "flex");
        }
        else if ($("#ete").css('display') == 'block') {
            $("#légumes-ete").hide();
            $('#fruits-ete').css("display", "flex");
        }
        else {
            $("#légumes-aut").hide();
            $('#fruits-aut').css("display", "flex");
        }

    });

    $('#btn-hiv').click(function () {
        $('#printemps').hide();
        $('#automne').hide();
        $('#ete').hide();
        $('#hiver').show();
    });

    $('#btn-prin').click(function () {
        $('#hiver').hide();
        $('#automne').hide();
        $('#ete').hide();
        $('#printemps').show();
    });

    $('#btn-ete').click(function () {
        $('#hiver').hide();
        $('#automne').hide();
        $('#printemps').hide();
        $('#ete').show();
    });
    $('#btn-aut').click(function () {
        $('#hiver').hide();
        $('#printemps').hide();
        $('#ete').hide();
        $('#automne').show();
    });



});