$(document).ready(function() {
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
                    <div class="col-sm-3 control-label text-right">\n\
                        <strong>'+ data[i]['label']+' :</strong>\n\
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
        url: Routing.generate('cliches_player_resultfield_results', {'playerProposal_id': idPlayerProposal}),
        success: function(data){
            $('#ajax-loader').remove();
            viewResults(data);
        },
        error: function(error){
            console.log(error);
        }
     });
});