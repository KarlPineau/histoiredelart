/**
 * Created by karlpineau on 25/09/2016.
 */
//https://jqueryui.com/droppable/#photo-manager
$( function() {
    $(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        if(scroll < 50) {
            $('#draggable-container').css('margin-top', '');
        } else if(scroll >= 50) {
            $('#draggable-container').css('margin-top', '-50px');
        }
    });

    // Let the gallery items be draggable
    $('.draggable').draggable({
        revert: "invalid", // when not dropped, the item will revert back to its initial position
        containment: "document",
        helper: "clone",
        cursor: "move"
    });

    // Let the trash be droppable, accepting the gallery items
    $('.droppable').droppable({
        accept: $('.draggable'),
        classes: {
            "ui-droppable-active": "ui-state-highlight"
        },
        drop: function( event, ui ) {
            /* if parent is a labelPlace, we re-add the comment sentence */
            var isBlockLabel = false;
            if($(ui.draggable).parent().is('ul.ui-helper-reset')) {
                parent = $(ui.draggable).parent().parent(); isBlockLabel = true;
            }

            /* Move Label */
            placeImage( ui.draggable, $(this) );

            /* Clean previous labelPlace*/
            if(isBlockLabel == true) {
                parent.children().first().remove();
                parent.append('<p class="infoPlace">Placez ici votre label</p>');
            }
        }
    });

    // Let the labels be droppable as well, accepting items from the $('.droppable')
    $('#labels').droppable({
        accept: $('.draggable'),
        classes: {
            "ui-droppable-active": "custom-state-active"
        },
        drop: function( event, ui ) {
            var item = ui.draggable,
                containerItem = item.parent().parent();
            resetImage( ui.draggable );

            containerItem.html('<p class="infoPlace">Placez ici votre label</p>');
        }
    });
    /* -- END : DRAG AND DROP ACTIONS -- */


    /* -- LABEL MOVEMENTS -- */
    function placeImage( item, droppable) {
        if(droppable.children().first().is('ul.gallery') == true) {
            var labelToMove = droppable.children().first().children().first();
                droppable.html();
            resetImage(labelToMove);
        } else if(droppable.children().first().is('p.infoPlace') == true) {
            droppable.find('p.infoPlace').remove();
        }

        //Déplacement de l'objet à proprement parlé
        item.fadeOut(function() {
            var list = $( "ul", droppable ).length ?
                $( "ul", droppable ) :
                $( "<ul class='gallery ui-helper-reset'/>" ).appendTo( droppable );

            item.css("background-color", "");
            item.find('i.fa-undo').removeClass('hidden');
            item.find('i.fa-undo').on( "click", function( event ) {
                var item = $( this ).parent(), target = $( event.target );

                if ( target.is( "i.fa-undo" ) ) {
                    parent = item.parent().parent();
                    resetImage( item );
                    parent.children().first().remove();
                    parent.append('<p class="infoPlace">Placez ici votre label</p>');
                }

                return false;
            });
            item.appendTo(list).fadeIn();
            item.addClass('ui-draggable ui-draggable-handle');
            item.draggable({
                //cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                revert: "invalid", // when not dropped, the item will revert back to its initial position
                containment: "document",
                helper: "clone",
                cursor: "move"
            });
        });
        droppable.droppable( "option", "disabled" );
    }

    // Image recycle function
    function resetImage( item ) {
        item.fadeOut(function() {
            item
                .css("background-color", "")
                .addClass('ui-draggable ui-draggable-handle')
                .appendTo('#labels')
                .fadeIn()
                .find("i.fa-undo").addClass('hidden');
            item.draggable({
                //cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                revert: "invalid", // when not dropped, the item will revert back to its initial position
                containment: "document",
                helper: "clone",
                cursor: "move"
            });

        });
    }
    /* -- END : LABEL MOVEMENTS -- */

    /* -- LABEL CHECK -- */
    function getCheck() {
        /* Submit Tracking to server */
        submitTracking();

        /* Check if suggested labels are corrects or not */
        var checkGoodAnwser = 0, count = 0;
        $.each($('div[id^="item-"]'), function() {
            count++;
            imageId = $(this).attr('itemid');
            if($(this).children().first().is('ul') && $(this).children().first().children().first().is('div')) {
                labelId = $(this).children().first().children().first().attr('itemid');

                if(imageId == labelId) {
                    $(this).children().first().children().first().css('background-color', 'green').css('color', '#FFF');
                    $(this).children().first().children().first().children().first().addClass('hidden');
                    checkGoodAnwser++;
                } else {
                    $(this).children().first().children().first().css('background-color', 'orange').css('color', '#FFF');
                }
            }
        });

        /* If there is wrong anwsers, we change button -> One "Donner Sa langue au chat" and one "Vérifier à nouveau" */
        if(checkGoodAnwser != count) {
            $('.draggable-footer').html('' +
                '<div class="btn-group-vertical btn-block" role="group" aria-label="Action button">' +
                '   <button type="button" class="btn btn-lg btn-success" id="checkButton"><span style="font-size: 18px; font-weight: bold;">Vérifier à<br />nouveau  <i class="fa fa-question-circle" aria-hidden="true"></i></span></button>' +
                '   <button type="button" class="btn btn-lg btn-warning giphy-container" id="answerButton"><div><img class="giphy hidden" src=""/></div><span style="font-weight: bold;">Donne ta langue<br />au chat</span></button>' +
                '</div>');
            $('#checkButton').on('click', function () {getCheck();});
            $('#answerButton').on('click', function () {getAnwser();});
            var xhr = $.get("http://api.giphy.com/v1/gifs/random?api_key=dc6zaTOxFJmzC&tag=cat&rating=pg-13");
            xhr.done(function(data) {
                console.log("success got data", data);
                $('.giphy').attr('src', data.data.image_original_url).removeClass('hidden');
            });
        } else {
            $('.draggable-text').css('cursor', 'default');
            $('.ifaundo').hide();
            $('#container-player').prepend('' +
                '<div class="alert alert-success text-center" style="margin-bottom: 0px; border-radius: 0px">' +
                '   <span style="font-size:25px; font-weight: bold;">Bien joué ! <i class="fa fa-thumbs-o-up" aria-hidden="true"></i></span>' +
                '   <br/>' +
                '   <div style="margin-top: 5px;"><div class="addthis_sharing_toolbox"></div></div>' +
                '   <span style="font-size:15px; font-weight: bold;">Partage ton score et vois si tes amis savent eux-aussi répondre !</span>' +
                '</div>');
            $('.draggable-footer').html('' +
                '<div class="alert alert-success">' +
                '   <span style="font-size:25px; font-weight: bold;">Bien joué ! <i class="fa fa-thumbs-o-up" aria-hidden="true"></i></span>' +
                '</div>').css('margin-top', '0px');
            $.getScript("//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56a802c313ab49f1");
        }
    }

    function positionAnswer() {
        $.each($('div[id^="item-"]'), function() {
            var imageId = $(this).attr('itemid'),
                label = $('#itemLabel-'+imageId).wrap('<div id="removeDiv-'+imageId+'"></div>').parent().html(),
                ulGallery = false;

            if($(this).children().first().is('ul') && $(this).children().first().children().first().is('div')) {
                var labelId = $(this).children().first().children().first().attr('itemid');

                if(imageId != labelId) {
                    /* if there is still a label in the place, we move it to the list */
                    if($('#item-'+imageId).children().first().is('ul')) {
                        var movingLabel = $('#item-'+imageId).children().first().children().first().wrap('<div id="movingRemoveDiv"></div>').parent().html();
                        $('#item-' + imageId).html('<p class="infoPlace">Placez ici votre étiquette</p>');
                        $('#labels').append(movingLabel);
                    }
                    /* (then) we move the label to the correct place*/
                    $('#removeDiv-'+imageId).remove();
                    $('#item-' + imageId).html(label);
                    $('#itemLabel-'+imageId).wrap('<ul class="gallery ui-helper-reset" />');
                }
            } else if($(this).children().first().is('p')) {
                if($('#itemLabel-'+imageId).parent().is('ul.gallery')) {ulGallery = true;}

                label = $('#itemLabel-'+imageId).wrap('<div id="removeDiv-'+imageId+'"></div>').parent().html();
                //console.log(label);
                if(ulGallery == true) {
                    $('#removeDiv-'+imageId).parent().parent().append('<p class="infoPlace">Placez ici votre étiquette</p>');
                    $('#removeDiv-'+imageId).parent().remove();
                } else {
                    $('#removeDiv-'+imageId).remove();
                }

                $('#item-'+imageId).html(label);
                $('#itemLabel-'+imageId).wrap('<ul class="gallery ui-helper-reset" />');
            } else if($(this).children().length == 0 || ($(this).children().first().is('ul.gallery') && $(this).children().first().children().length == 0)) {
                $(this).html('<p class="infoPlace">Placez ici votre étiquette</p>');
            }
        });
    }

    function getAnwser() {
        /* Submit Tracking to server */
        submitTracking();

        $.each($('div[id^="itemLabel-"]'), function() {
            var imageId = $(this).attr('itemid');
            $(this).css('cursor', 'default');
            $(this).find('ifaundo').addClass('hidden');

            if($(this).parent().attr('id') == 'labels') { //Style in the list
                $(this).css('background-color', '#d9edf7').css('color', '#000');
            } else if($(this).parent().parent().is('div.droppable') && $(this).parent().parent().attr('itemid') != imageId) { //Wrong prosition
                $(this).css('background-color', '#d9edf7').css('color', '#000');
            } else if($(this).parent().parent().is('div.droppable') && $(this).parent().parent().attr('itemid') == imageId) { //Correct position
                $(this).css('background-color', 'green').css('color', '#FFF');
            } else { // Else
                $(this).css('background-color', '#d9edf7').css('color', '#000');
            }
        });

        /* update answers  */
        positionAnswer();
        /* if there is still label in the list, we replay the script */
        for(var i = 0; $('#labels').children().length > 0 && i < 10; i++) {
            positionAnswer();
        }

        /* cleaning card by removing undo icons */
        $('.ifaundo').remove();

        /* Update messages to user */
        $('#container-player').prepend('' +
            '<div class="alert alert-primary text-center" style="margin-bottom: 0px; border-radius: 0px">' +
            '   <span style="font-size:25px; font-weight: bold;">Bien essayé !</span>' +
            '   <br/>' +
            '   <div style="margin-top: 5px;"><div class="addthis_sharing_toolbox"></div></div>' +
            '   <span style="font-size:15px; font-weight: bold;">Partage ton score ou <a href="'+Routing.generate('tb_player_player_index', {'testedGame_id': $('#testedGameId').val()})+'">retente ta chance !</a></span>' +
            '</div>');
        $.getScript("//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56a802c313ab49f1");
        $('.draggable-footer').html('' +
            '<div class="alert alert-primary text-center" style="font-weight: bold;">' +
            '   <div class="giphy-container"><img class="giphy hidden" src=""/></div>' +
            '   <span>Heureusement que<br /> le chat était là !</span>' +
            '</div>').css('margin-top', '0px');
        var xhr = $.get("http://api.giphy.com/v1/gifs/random?api_key=dc6zaTOxFJmzC&tag=cat+tired&rating=pg-13");
        xhr.done(function(data) {
            console.log("success got data", data);
            $('.giphy').attr('src', data.data.image_original_url).removeClass('hidden');
        });
    }

    $('#checkButton').on('click', function () {
        getCheck();
    });
    /* -- END : LABEL CHECK -- */

    /* -- TRACKING FUNCTIONS -- */
    function submitTracking() {
        var answers = [];
        $.each($('div[id^="item-"]'), function() {
            var testedSession = $('#testedSession').val(),
                imageId = $(this).attr('itemid'),
                label = '';
            if($(this).children().first().is('ul.gallery')) {
                label = $(this).children().first().children().first().text();
            } else {
                label = null;
            }

            answers.push({'testedSession_id': testedSession, 'testedItem_id': imageId, 'label': label})
        });

        $.ajax({
            dataType: "json",
            url: Routing.generate('tb_player_player_tracking', {'answers': JSON.stringify(answers)}),
            success: function(data){
                console.log(data);
            },
            error: function(error){
                console.log(error);
            }
        });
    }
    /* -- END : TRACKING FUNCTIONS -- */

    /* -- TRACKING WINDOW SIZE -- */
    if(screen.width < 1250 && screen.width > 800) {
        $('.thumbnail-tb-player').parent().removeClass('col-md-3').addClass('col-md-4');
    } else if (screen.width < 800) {
        $('#sizeNoEnough').modal({show: true, backdrop: 'static'});

        $.ajax({
            dataType: "json",
            url: Routing.generate('tb_player_player_trackingToSmallWindow', {'testedSession_id': $('#testedSession').val()}),
            success: function(data){
                console.log(data);
            },
            error: function(error){
                console.log(error);
            }
        });

        var xhr = $.get("http://api.giphy.com/v1/gifs/random?api_key=dc6zaTOxFJmzC&tag=sorry&rating=pg-13");
        xhr.done(function(data) {
            $('.giphySorry').attr('src', data.data.image_original_url).removeClass('hidden');
        });
    }
    $( window ).resize(function() {
        if(screen.width < 1250) {
            $('.thumbnail-tb-player').parent().removeClass('col-md-3').addClass('col-md-4');
        }
    });
    /* -- END : TRACKING WINDOW SIZE -- */
} );