$(document).ready(function() {
    /* Hide du contenu formulaire à l'initialisation */
    $('#contentEntity_2').hide();
    $('#divPictureDescription').hide()

    function addPrototype(container, name, index) {
        container.append($(container.attr('data-prototype').replace(/__name__label__/g, name + ' :').replace(/__name__/g, index)));
    }

    function generateFields(arrayJson, container_fields) {
        for(var i = 0 ; i < arrayJson.length ; i++) {
            addPrototype(container_fields, arrayJson[i]['label'], i);

            $('#data_import_entityimport_fields_'+i).prev().remove();
            $('#data_import_entityimport_fields_'+i+' > div:first-child').addClass('row form-group');
            $('#data_import_entityimport_fields_'+i+' > div:first-child > label').wrap('<div class="col-sm-3 control-label"></div>');
            $('#data_import_entityimport_fields_'+i+' > div:first-child > div > label').text(arrayJson[i]['label']);
            $('#data_import_entityimport_fields_'+i+' > div:first-child > input').wrap('<div class="col-sm-9"></div>');
            $('#data_import_entityimport_fields_'+i+' > div:first-child > div > input').addClass('form-control');
            if(arrayJson[i]['field'] == 'sujet' || arrayJson[i]['field'] == 'name') {
                $('#data_import_entityimport_fields_'+i+' > div:first-child > div > input').attr('required', 'required');
            }

            $('#data_import_entityimport_fields_'+i+' > div:last-child').addClass('hidden');
            $('#data_import_entityimport_fields_'+i+' > div:last-child > label').remove();
            $('#data_import_entityimport_fields_'+i+' > div:last-child > input').val(arrayJson[i]['field']);

        }
    }

    function loadEntityForm() {
        $('button[id^="buttonLoad"]').on('click', function() {
            if($(this).attr('id') == 'buttonLoadArtwork') {var type = "artwork";}
            else if($(this).attr('id') == 'buttonLoadBuilding') {var type = "building";}
            $('#data_import_entityimport_type').val(type);

            var container_fields = $('#data_import_entityimport_fields');
            container_fields.prev().hide();

            $.ajax({
                dataType: "json",
                url: Routing.generate('data_import_import_entityField', {type: type}),
                success: function(data){
                    generateFields(data, container_fields);
                    $('#contentEntity_1').hide();
                    $('#contentEntity_2').show();
                    $('#ajax-loader').remove();

                    /* -- AFFICHAGE DU FORMULAIRE DES SOURCES -- */
                    var container_source = $('#data_import_entityimport_sources');
                        container_source.prev().hide();
                    var index = 0;
                    $('#buttonAddSource').on('click', function() { ++index; addSource(container_source, index, true);});
                    /* -- Fin des sources -- */
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
    }

    function deleteSource(index)
    {
        $('#data_import_entityimport_sources_'+index).parent().remove();
    }

    function addSource(container, index, addProto)
    {
        if(addProto == true) {addPrototype(container, 'Source ', index);}

        $('#data_import_entityimport_sources_'+index).parent().addClass('col-md-12');
        $('#data_import_entityimport_sources_'+index).addClass('row');
        $('#data_import_entityimport_sources_'+index).prev().hide();

        $('#data_import_entityimport_sources_'+index+'_title').parent().addClass('form-group col-md-6');
        $('#data_import_entityimport_sources_'+index+'_title').prev().addClass('control-label col-md-3');
        $('#data_import_entityimport_sources_'+index+'_title').addClass('form-control').wrap('<div></div>')
        $('#data_import_entityimport_sources_'+index+'_title').parent().addClass('col-md-9');

        $('#data_import_entityimport_sources_'+index+'_url').parent().addClass('form-group col-md-6');
        $('#data_import_entityimport_sources_'+index+'_url').prev().addClass('control-label col-md-3');
        $('#data_import_entityimport_sources_'+index+'_url').addClass('form-control').wrap('<div></div>')
        $('#data_import_entityimport_sources_'+index+'_url').parent().addClass('col-md-9');

        $('#data_import_entityimport_sources_'+index+'_authorityControl').parent().addClass('hidden'); //addClass('form-group col-md-4')
        $('#data_import_entityimport_sources_'+index+'_authorityControl').prev().addClass('control-label col-md-8');
        $('#data_import_entityimport_sources_'+index+'_authorityControl').addClass('form-control').wrap('<div></div>')
        $('#data_import_entityimport_sources_'+index+'_authorityControl').parent().addClass('col-md-4');


        $('#data_import_entityimport_sources_'+index).append('' +
            '<div class="col-md-1 text-right">' +
            '   <button type="button" class="btn btn-danger" id="delete_data_import_entityimport_sources_'+index+'">' +
            '       <span class="glyphicon glyphicon-remove"></span>' +
            '   </button>' +
            '</div>');
        $('#delete_data_import_entityimport_sources_'+index).on('click', function() {deleteSource(index);});

    }

    $('#data_import_entityimport_fields').html('');
    $('#data_import_entityimport_sources').html('');
    $('#data_import_entityimport_image_fileImage').prev().addClass('sr-only');
    $('#data_import_entityimport_image_fileImage_imageFile_file').prev().hide();
    $('#data_import_entityimport_image_fileImage_imageFile').prev().hide();
    $("#data_import_entityimport_image_fileImage_imageFile_file").fileinput({
        uploadUrl: '/file-upload-batch/2',
        maxFilePreviewSize: 10240,
        language: 'fr',
        showUpload: false
    });

    $('.fileinput-remove').on('click', function() {
        $('#divPictureDescription').hide();
        $('#data_import_entityimport_image_view_isPlan').attr('checked', false);
        $('#data_import_entityimport_image_view_vue').val('');
        $('#data_import_entityimport_image_view_title').val('');
        $('#data_import_entityimport_image_view_iconography').val('');
        $('#data_import_entityimport_image_view_location').val('');
        $('#data_import_entityimport_image_copyright').val('Inconnu');
    })

    $('#data_import_entityimport_image_fileImage_imageFile_file').on('change', function() {
        $('#divPictureDescription').show();
    });

    loadEntityForm();

    $('button[id="buttonBackTo1"]').on('click', function() {
        $('#contentEntity_2').hide();
        $('#contentEntity_1').show();
        $('#data_import_entityimport_fields').html('');
        $('#data_import_entityimport_sources').html('');
        //loadEntityForm();
    });
});