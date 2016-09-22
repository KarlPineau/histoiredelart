$(document).ready(function() {
    function addPrototype(container, name, index) {
        $(container).append($(container.attr('data-prototype').replace(/__name__label__/g, name+' n°'+(index+1)+' :').replace(/__name__/g, index)));
    }

    function deleteTestedItem(index)
    {
        $('#tb_modelbundle_testedgame_testedItems_'+index).parent().remove();
    }

    function addTestedItem(container, index)
    {
        addPrototype(container, 'Item ', index);

        $('#tb_modelbundle_testedgame_testedItems_'+index).addClass('row');
        $('#tb_modelbundle_testedgame_testedItems_'+index).parent().addClass('col-md-11');

        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemLabel').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemLabel').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemLabel').addClass('form-control').wrap('<div></div>')
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemLabel').parent().addClass('col-md-9');

        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').addClass('form-control').wrap('<div></div>')
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').parent().addClass('col-md-9');

        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage').wrap('<div></div>')
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage').parent().addClass('col-md-9');

        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').addClass('form-control').wrap('<div></div>')
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').parent().addClass('col-md-9');

        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage').wrap('<div></div>')
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage').parent().addClass('col-md-9');

        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage_imageFile').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage_imageFile').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage_imageFile').addClass('form-control').wrap('<div></div>')
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage_imageFile').parent().addClass('col-md-9');


        $('#tb_modelbundle_testedgame_testedItems_'+index).parent().parent().append('' +
            '<div class="col-md-1 text-right">' +
            '   <button type="button" class="btn btn-danger" id="delete_tb_modelbundle_testedgame_testedItems_'+index+'">' +
            '       <span class="glyphicon glyphicon-remove"></span>' +
            '   </button>' +
            '</div>');
        $('#delete_tb_modelbundle_testedgame_testedItems_'+index).on('click', function() {deleteTestedItem(index);});

    }

    var index = 0;
    var container_testedItem = $('#tb_modelbundle_testedgame_testedItems');

    $('#buttonAddTestedItem').on('click', function() {
        ++index;
        addTestedItem(container_testedItem, index);
    });
});