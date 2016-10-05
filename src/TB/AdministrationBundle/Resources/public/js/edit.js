$(document).ready(function() {
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