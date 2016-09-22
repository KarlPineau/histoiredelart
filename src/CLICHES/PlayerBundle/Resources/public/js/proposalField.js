$(document).ready(function() {
    function addPrototype(container, name, index) {
        container.append($(container.attr('data-prototype').replace(/__name__label__/g, name + ' :').replace(/__name__/g, index)));
    }

    function generateFields(arrayJson, container_fields) {
        for(var i = 0 ; i < arrayJson.length ; i++) {
            addPrototype(container_fields, arrayJson[i]['label'], i);

            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i).prev().remove();
            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i+' > div:first-child').addClass('row form-group');
            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i+' > div:first-child > label').wrap('<div class="col-sm-3 control-label"></div>');
            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i+' > div:first-child > div > label').text(arrayJson[i]['label']);
            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i+' > div:first-child > input').wrap('<div class="col-sm-9"></div>');
            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i+' > div:first-child > div > input').addClass('form-control');

            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i+' > div:last-child').addClass('hidden');
            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i+' > div:last-child > label').remove();
            $('#cliches_player_playerproposal_loadfield_type_playerProposalFields_'+i+' > div:last-child > input').val(arrayJson[i]['field']);

        }
    }

    var container_fields = $('#cliches_player_playerproposal_loadfield_type_playerProposalFields');
    container_fields.prev().hide();

    $.ajax({
        dataType: "json",
        url: Routing.generate('cliches_player_proposalfield_proposalfield', {idImg: $('img[class="image-cliches"]').attr('clichesnumber')}),
        success: function(data){
            $('#ajax-loader').remove();
            generateFields(data, container_fields);
        },
        error: function(error){
            console.log(error);
        }
    });
});

