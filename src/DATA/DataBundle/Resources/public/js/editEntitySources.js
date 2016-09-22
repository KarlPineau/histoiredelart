$(document).ready(function() {
    function addPrototype(container, name, index) {
        $(container).append($(container.attr('data-prototype').replace(/__name__label__/g, name+' n°'+(index+1)+' :').replace(/__name__/g, index)));
    }

    function deleteSource(index)
    {
        $('#data_databundle_entitysources_sources_'+index).parent().remove();
    }

    function addSource(container, index, addProto)
    {
        if(addProto == true) {addPrototype(container, 'Source ', index);}

        $('#data_databundle_entitysources_sources_'+index).addClass('row');

        $('#data_databundle_entitysources_sources_'+index+'_title').parent().addClass('form-group col-md-4');
        $('#data_databundle_entitysources_sources_'+index+'_title').prev().addClass('control-label col-md-3');
        $('#data_databundle_entitysources_sources_'+index+'_title').addClass('form-control').wrap('<div></div>')
        $('#data_databundle_entitysources_sources_'+index+'_title').parent().addClass('col-md-9');

        $('#data_databundle_entitysources_sources_'+index+'_url').parent().addClass('form-group col-md-4');
        $('#data_databundle_entitysources_sources_'+index+'_url').prev().addClass('control-label col-md-3');
        $('#data_databundle_entitysources_sources_'+index+'_url').addClass('form-control').wrap('<div></div>')
        $('#data_databundle_entitysources_sources_'+index+'_url').parent().addClass('col-md-9');

        $('#data_databundle_entitysources_sources_'+index+'_authorityControl').parent().addClass('form-group col-md-3');
        $('#data_databundle_entitysources_sources_'+index+'_authorityControl').prev().addClass('control-label col-md-8');
        $('#data_databundle_entitysources_sources_'+index+'_authorityControl').addClass('form-control').wrap('<div></div>')
        $('#data_databundle_entitysources_sources_'+index+'_authorityControl').parent().addClass('col-md-4');


        $('#data_databundle_entitysources_sources_'+index).append('' +
            '<div class="col-md-1 text-right">' +
            '   <button type="button" class="btn btn-danger" id="delete_data_databundle_entitysources_sources_'+index+'">' +
            '       <span class="glyphicon glyphicon-remove"></span>' +
            '   </button>' +
            '</div>');
        $('#delete_data_databundle_entitysources_sources_'+index).on('click', function() {deleteSource(index);});

    }

    var container_source = $('#data_databundle_entitysources_sources');
        container_source.prev().hide();

    var oldContent = container_source.html(); container_source.html('');

        container_source.append('' +
            '<div class="text-right">' +
            '   <button type="button" id="buttonAddSource" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button>' +
            '</div>');
        $('#buttonAddSource').on('click', function() {
            ++index;
            addSource(container_source, index, true);
        });

        container_source.append(oldContent);

        for(var i = 0 ; i < $('div[id^="data_databundle_entitysources_sources_"]').length ; i++) {
            addSource(container_source, i, false);
        }

        if(i === 'undefined') { var index = 0;} else { var index = i;}

        if(index == 0) {
            addSource(container_source, index, true);
        }
});