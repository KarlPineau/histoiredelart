/**
 * Created by karlpineau on 30/01/2016.
 */
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
    
    function loadNewPage() {
        document.location.href=Routing.generate('cliches_player_selection_selection', {'playerSession_id': $('.image-cliches').attr('session')})
    }

    $('#countdown').countdown({until: '+10m', compact: true, format: 'MS'}); //+10m
    window.setTimeout(loadNewPage, 600000); //10min = 600000

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