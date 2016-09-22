$(document).ready(function() {
    $('#checkBoxSujetIcono').on('click', function() {
        $.ajax({
            dataType: "json",
            url: Routing.generate('data_data_entity_setSujetAsIconography', {entity_id: $('#entityId').val()}),
            success: function(data){
                console.log(data);
            },
            error: function(error){
                console.log(dump(error));
            }
        });
    });
});