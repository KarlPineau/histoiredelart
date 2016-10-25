$(document).ready(function() {
    $.each($('div[id^="cliches_player_playerproposal_loadchoice_type_"]'), function() {
        var divType = $(this).prev();
        $.ajax({
            dataType: "json",
            url: Routing.generate('cliches_player_proposalchoice_getFieldsAjax', {field: divType.text()}),
            success: function(data){
                divType.text(data);
            },
            error: function(error){
                console.log(error);
            }
        });
    });
    $('div[id^="cliches_player_playerproposal_loadchoice_type_"]').prev().wrap('<div></div>').parent().addClass('col-sm-3 control-label');
    $('div[id^="cliches_player_playerproposal_loadchoice_type_"]').addClass('col-sm-9').css('margin-bottom', '10px');

    $.each($('input[id^="cliches_player_playerproposal_loadchoice_type_"]'), function() {
        var id = $(this).attr('id');
        var label = $('label[for^="'+id+'"]');
        var labelText = label.text();

        label.remove();
        $(this).wrap('<div></div>');
        $(this).wrap('<label for="'+id+'"></label>').parent().append('<span style="margin-left: 10px;">'+labelText+'</span>');
    });
});

