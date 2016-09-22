$(document).ready(function() {
    var index = 0;
    function addFavorite(button, index) {
        button.attr('id', 'buttonRemoveFavorite'+index);
        $('#buttonRemoveFavorite'+index+' > span').removeClass('glyphicon-star-empty').addClass('glyphicon-star');
        index++; console.log(index);
        
        $.ajax({
            dataType: "json",
            url: Routing.generate('data_public_entity_favorite', {id: button.attr('idEntity')}),
            success: function(data){
                console.log(data);
                button.on('click', function() {removeFavorite(button, index);});
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function removeFavorite(button, index) {
        button.attr('id', 'buttonAddFavorite'+index);
        $('#buttonAddFavorite'+index+' > span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
        index++; console.log(index);

        $.ajax({
            dataType: "json",
            url: Routing.generate('data_public_entity_favorite', {id: button.attr('idEntity')}),
            success: function(data){
                console.log(data);
                button.on('click', function() {addFavorite(button, index);});
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    $('#buttonAddFavoriteOrigin').on('click', function() {addFavorite($(this), index);});
    $('#buttonRemoveFavoriteOrigin').on('click', function() {removeFavorite($(this), index)});
});