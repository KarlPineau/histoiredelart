$(document).ready(function() {
    function addPrototype(container, name, index) {
        $(container).append($(container.attr('data-prototype').replace(/__name__label__/g, name+' n°'+(index+1)+' :').replace(/__name__/g, index)));
    }

    function deleteSameAs(index)
    {
        $('#data_databundle_entitysameas_sameAs_'+index).parent().remove();
    }

    function addSameAs(container, index, addProto)
    {
        if(addProto == true) {addPrototype(container, 'SameAs ', index);}

        $('#data_databundle_entitysameas_sameAs_'+index).addClass('row');

        $('#data_databundle_entitysameas_sameAs_'+index+'_url').parent().addClass('form-group col-md-11');
        $('#data_databundle_entitysameas_sameAs_'+index+'_url').prev().addClass('control-label col-md-3');
        $('#data_databundle_entitysameas_sameAs_'+index+'_url').addClass('form-control').wrap('<div></div>')
        $('#data_databundle_entitysameas_sameAs_'+index+'_url').parent().addClass('col-md-9');


        $('#data_databundle_entitysameas_sameAs_'+index).append('' +
            '<div class="col-md-1 text-right">' +
            '   <button type="button" class="btn btn-danger" id="delete_data_databundle_entitysameas_sameAs_'+index+'">' +
            '       <span class="glyphicon glyphicon-remove"></span>' +
            '   </button>' +
            '</div>');
        $('#delete_data_databundle_entitysameas_sameAs_'+index).on('click', function() {deleteSameAs(index);});

    }

    var container_sameAs = $('#data_databundle_entitysameas_sameAs');
        container_sameAs.prev().hide();

    var oldContent = container_sameAs.html(); container_sameAs.html('');

        container_sameAs.append('' +
            '<div class="text-right">' +
            '   <button type="button" id="buttonAddSameAs" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button>' +
            '</div>');
        $('#buttonAddSameAs').on('click', function() {
            ++index;
            addSameAs(container_sameAs, index, true);
        });

        container_sameAs.append(oldContent);

        for(var i = 0 ; i < $('div[id^="data_databundle_entitysameas_sameAs_"]').length ; i++) {
            addSameAs(container_sameAs, i, false);
        }

        if(i === 'undefined') { var index = 0;} else { var index = i;}

        if(index == 0) {
            addSameAs(container_sameAs, index, true);
        }
});