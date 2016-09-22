$(document).ready(function() {
    function addPrototype(container, name, index) {
        $('#').append($(container.attr('data-prototype').replace(/__name__label__/g, name).replace(/__name__/g, index)));
    }

    var container_entity = $('#data_data_artwork_views_views');
        container_entity.prev().hide();

    $('[id$=_isPlan]').parent().addClass('form-group col-md-2');
    $('[id$=_vue]').parent().addClass('form-group col-md-2');
    $('[id$=_title]').parent().addClass('form-group col-md-2');
    $('[id$=_iconography]').parent().addClass('form-group col-md-2');
    $('[id$=_location]').parent().addClass('form-group col-md-2');
});