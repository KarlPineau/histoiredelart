$(document).ready(function() {
    function addPrototype(container, name, index) {
        $(container).append($(container.attr('data-prototype').replace(/__name__label__/g, name+' n°'+(index)+' :').replace(/__name__/g, index)));
    }

    function deleteTestedItem(index)
    {
        $('#delete_tb_modelbundle_testedgame_testedItems_'+index).parent().remove();
        $('#tb_modelbundle_testedgame_testedItems_'+index).parent().parent().remove();
    }

    function addTestedItem(container, index)
    {
        addPrototype(container, 'Image ', index);

        $('#tb_modelbundle_testedgame_testedItems_'+index).addClass('row');
        $('#tb_modelbundle_testedgame_testedItems_'+index).parent().addClass('col-md-11').css('padding-left', '0px');
        $('#tb_modelbundle_testedgame_testedItems_'+index).wrap('<div/>').parent().addClass('well well-sm');

        /* LABEL */
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemLabel').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemLabel').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemLabel').addClass('form-control').wrap('<div></div>');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemLabel').parent().addClass('col-md-9');

        /* ORDER */
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').addClass('form-control').wrap('<div></div>');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').parent().addClass('col-md-9');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_itemOrder').val(index);

        /* IMAGE */
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage').prev().addClass('sr-only');

        /* IMAGE : COPYRIGHT */
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').parent().addClass('form-group col-md-12');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').prev().addClass('control-label col-md-3');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').addClass('form-control').wrap('<div></div>');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').parent().addClass('col-md-9');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_copyright').val('Inconnu');

        /* IMAGE : FILE IMAGE */
        //$('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage').parent().css('display', 'inline-block');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage').prev().addClass('sr-only');

        /* IMAGE : FILE IMAGE : FILE */
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage_imageFile').parent().addClass('form-group col-md-12').css('margin-left', '0px');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage_imageFile').prev().addClass('sr-only');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage_imageFile_file').prev().addClass('sr-only');
        $('#tb_modelbundle_testedgame_testedItems_'+index+'_dataImage_fileImage_imageFile_file').fileinput({
            uploadUrl: '/file-upload-batch/2',
            maxFilePreviewSize: 10240,
            language: 'fr',
            showUpload: false,
            allowedFileExtensions: ["jpg", "png", "gif"],
            maxImageWidth: 800,
            maxImageHeight: 800,
            minImageWidth: 300,
            minImageHeight: 300
        });

        $('#tb_modelbundle_testedgame_testedItems_'+index).parent().parent().parent().append('' +
            '<div class="col-md-1 text-right">' +
            '   <button type="button" class="btn btn-danger" id="delete_tb_modelbundle_testedgame_testedItems_'+index+'">' +
            '       <span class="glyphicon glyphicon-remove"></span>' +
            '   </button>' +
            '</div>');
        $('#delete_tb_modelbundle_testedgame_testedItems_'+index).on('click', function() {deleteTestedItem(index);});

        if(index == 1) {
            var button = $('#buttonAddTestedItem').parent().html();
            $('#buttonAddTestedItem').remove();
            $('#tb_modelbundle_testedgame_testedItems').after(button);
            $('#buttonAddTestedItem').addClass('btn-block').html('<span class="glyphicon glyphicon-plus"></span> Ajouter une image');
            $('#buttonAddTestedItem').on('click', function() {
                ++index;
                addTestedItem(container_testedItem, index);
            });
        }
    }

    var index = 0;
    var container_testedItem = $('#tb_modelbundle_testedgame_testedItems');

    $('#buttonAddTestedItem').on('click', function() {
        ++index;
        addTestedItem(container_testedItem, index);
    });

    /* IMAGE : COPYRIGHT */
    $('#tb_modelbundle_testedgame_icon_copyright').parent().addClass('form-group col-md-12');
    $('#tb_modelbundle_testedgame_icon_copyright').prev().addClass('control-label col-md-3');
    $('#tb_modelbundle_testedgame_icon_copyright').addClass('form-control').wrap('<div></div>');
    $('#tb_modelbundle_testedgame_icon_copyright').parent().addClass('col-md-9');
    $('#tb_modelbundle_testedgame_icon_copyright').val('Inconnu');

    $('#tb_modelbundle_testedgame_icon_fileImage_imageFile_file').prev().hide();
    $('#tb_modelbundle_testedgame_icon_fileImage_imageFile').prev().hide();
    $("#tb_modelbundle_testedgame_icon_fileImage_imageFile_file").fileinput({
        uploadUrl: '/file-upload-batch/2',
        maxFilePreviewSize: 10240,
        language: 'fr',
        showUpload: false,
        allowedFileExtensions: ["jpg", "png", "gif"],
        maxImageWidth: 800,
        maxImageHeight: 800,
        minImageWidth: 300,
        minImageHeight: 300
    });
});