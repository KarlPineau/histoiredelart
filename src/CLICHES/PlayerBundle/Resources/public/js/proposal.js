$(document).ready(function() {
    $('.fancybox').on('click', function() {
        $.ajax({
            dataType: "json",
            url: Routing.generate('cliches_player_zoom_log', {playerOeuvre_id: $('#oeuvre-id').val()}),
            success: function(data){
                console.log(data);
            },
            error: function(error){
                console.log(dump(error));
            }
        });
    });

    $('#home-logo').on('click', function() {
        $.ajax({
            dataType: "json",
            url: Routing.generate('cliches_player_session_forcedEnd', {'session_id': $('#session-id').val()}),
            success: function(data){
                console.log('Fin de session : ' + data);
            },
            error: function(error){
                console.log(error);
            }
        });
    });

    window.onbeforeunload = function() {
        $.ajax({
            dataType: "json",
            url: Routing.generate('cliches_player_session_forcedEnd', {'session_id': $('#session-id').val()}),
            success: function(data){
                console.log('Fin de session : ' + data);
            },
            error: function(error){
                console.log(error);
            }
        });
    };
});

