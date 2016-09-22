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
    
    function viewResults(data) {
        for(var i = 0; i < data.length; i++) {
            
            var suggestResult = '';
            if(data[i]['suggestResult'] !== null){
                suggestResult = data[i]['suggestResult'];
            } else {
                suggestResult = '<span class="text-warning">Vous n\'avez rien proposé pour ce champ.</span>';
            }
            
            $('#divForResults').append('\n\
                <div class="row">\n\
                    <div class="col-sm-3">\n\
                        <div class="control-label text-right">'+ data[i]['label']+' :</div>\n\
                    </div>\n\
                    <div class="col-sm-9">\n\
                        '+ suggestResult +'<br />\n\
                        <span class="text-success">'+ data[i]['trueResult']+'</span>\n\
                    </div>\n\
                </div>\n\
                <br />');
        }
    }
    
    var idPlayerProposal = $('#playerproposal').val();
    $.ajax({
        dataType: "json",
        url: Routing.generate('cliches_player_result_results', {'playerProposal_id': idPlayerProposal}),
        success: function(data){
            $('#ajax-loader').remove();
            viewResults(data);
        },
        error: function(error){
            console.log(error);
        }
     });


    $('.source-link').on('click', function() {
        $.ajax({
            dataType: "json",
            url: Routing.generate('data_data_source_addClick', {'source_id': $(this).attr('source-id'), 'context': $(this).attr('context')}),
            success: function(data){
                console.log('Résultat click : '+data);
            },
            error: function(error){
                console.log(error);
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