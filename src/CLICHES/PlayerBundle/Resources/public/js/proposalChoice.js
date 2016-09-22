$(document).ready(function() {
    /*
    function generateChoiceValues(field)
    {
        $.ajax({
            dataType: "json",
            url: Routing.generate('cliches_player_proposalchoice_getvalues', {oeuvre_id: $('#oeuvre-id').val(), field: field, choices_number:3}),
            success: function(data){
                //$('#ajax-loader').remove();
                //generateFields(data, container_fields);
                console.log(data);
            },
            error: function(error){
                console.log(error);
            }
        });
    }
    function addPrototype(container, name, index) {
        container.append($(container.attr('data-prototype').replace(/__name__label__/g, name + ' :').replace(/__name__/g, index)));
    }

    function generateFields(arrayJson, container_fields) {
        for(var i = 0 ; i < arrayJson.length ; i++) {
            addPrototype(container_fields, arrayJson[i]['label'], i);
            //containerChoices = $('#cliches_player_playerproposal_loadchoice_type_playerProposalChoices_'+i+'_playerProposalChoiceValues');
            //addPrototype(containerChoices, 'test', i);

            generateChoiceValues(arrayJson[i]['field'])
        }
    }

    var container_fields = $('#cliches_player_playerproposal_loadchoice_type_playerProposalChoices');
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
    */
});

