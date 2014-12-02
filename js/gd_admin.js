/**
 * Created by ryagudin on 9/17/14.
 */
( function( $ ){
    // Great Diseases JS object
    var gd_admin = {
        /**
         * Binds click event to deleteButton and
         * calls deleteComment() to delete hte comment.
         */
        bindDeleteComment: function(editChoicesElem){
            editChoicesElem.click(function(){
                var postID = $(this).data( 'postid' );
                $( '#gd-progress-pt-choices-' + postID ).fadeToggle();
                return false;
            });
        },
        /**
         *
         * @param addNewChoiceElem
         */
        bindAddNewChoice: function(addNewChoiceElem){
            var self = this;
            addNewChoiceElem.click(function(){
                var postID = $(this).data('postid');
                self.addNewChoice( postID );
            });
        },
        /**
         * Deletes a comment given a commentID
         * @param postID the comment ID
         */
        addNewChoice: function( postID ){
            if(postID == undefined || postID <= 0){
                alert('Error: could render choices, invalid postID!');
                return;
            }
            var self = this;
            var gd_admin_nonce = $('#gd_admin_nonce').val();
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'html',
                data: {
                    action : 'gd_add_new_choice',
                    gd_admin_nonce : gd_admin_nonce,
                    postID : postID,
                    choice_title : $( '#new-progress-pt-choice-title-' + postID ).val(),
                    choice_goto : $( '#new-progress-pt-choice-goto-' + postID ).val()
                },
                success: function(data, textStatus, jqXHR){
                    var existingChoices = $('#gd-progress-pt-existing-choices-' + postID);
                    if( existingChoices.text() == '' ){
                        existingChoices.append('<p><b>Existing Choices:</b></p>' + data);
                    }else{
                        existingChoices.append(data);
                        var deleteElemID = $(data).find('.delete-choice').attr('id');
                        self.bindDeleteChoice( $('#' + deleteElemID ) );
                    }
                    // clear input
                    $('#new-progress-pt-choice-title-' + postID).val("");
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert(errorThrown)
                }
            });
        },

        /**
         * Binds click even to deleteChoiceElem an calls deleteStepChoice
         * @param deleteChoiceElem
         */
        bindDeleteChoice: function(deleteChoiceElem){
            var self = this;
            deleteChoiceElem.click( function(){
                var postID = $(this).data( 'postid' );
                var choiceID = $(this).data( 'choiceid' );
                self.deleteStepChoice( choiceID, postID );
            });
        },
        /**
         * Given a choice ID and step ID, removes the choice from the step ID's
         * post meta with choice ID
         * @param choiceID
         * @param postID
         */
        deleteStepChoice: function( choiceID, postID ){
            var gd_admin_nonce = $('#gd_admin_nonce').val();
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action : 'gd_delete_step_choice',
                    gd_admin_nonce : gd_admin_nonce,
                    postID : postID,
                    choiceID : choiceID
                },
                success: function(data, textStatus, jqXHR){
                    if( data.success ){
                        $('#choice-' + choiceID + '-' + postID).fadeOut().remove();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert(errorThrown)
                }
            });
        },
        /**
         * AJAX handler for visibility checkboxes
         * @param isVisibleElem
         */
        selectIsVisible: function ( isVisibleElem ){
            isVisibleElem.click(function(){
                var is_visible = Boolean( $(this).attr('checked') );
                var gd_admin_nonce = $('#gd_admin_nonce').val();
                var postID = $(this).data('postid');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'gd_set_visibility',
                        gd_admin_nonce: gd_admin_nonce,
                        postID: postID,
                        is_visible: is_visible
                    },
                    success: function(data, textStatus, jqXHR){
                        if( data.success ){
                            console.log(data);
                        }else{
                            alert('Something went wrong!');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert(errorThrown);
                    }
                });
            });
        },
        /**
         * Onclick for edit elem for the milestone column
         * @param mileStoneElem
         */
        selectMilestone: function( mileStoneElem ){
                mileStoneElem.click(function(){
                    var is_milestone = Boolean( $(this).attr('checked') );
                    var gd_admin_nonce = $('#gd_admin_nonce').val();
                    var postID = $(this).data('postid');
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'gd_set_milestone',
                            gd_admin_nonce: gd_admin_nonce,
                            postID: postID,
                            is_milestone: is_milestone
                        },
                        success: function(data, textStatus, jqXHR){
                            if( data.success ){
                                console.log(data);
                            }else{
                                alert('Something went wrong!');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert(errorThrown);
                        }
                    });
                });
        },

        /**
         * Serializes choice form and saves changes to the various choices
         */
        saveChoiceSettings: function(saveChoiceSettingsElem){
            saveChoiceSettingsElem.click(function(){
                var postID = $(this).data('postid');
                var choiceID = $(this).data('choiceid');
                var gd_admin_nonce = $('#gd_admin_nonce').val();
                var choiceText = $('#choice-text-' + choiceID).val()
                var nextStep = $('#choice-goto-' + choiceID + '-' + postID).val();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'gd_save_choice_settings',
                        gd_admin_nonce: gd_admin_nonce,
                        postID: postID,
                        choiceID: choiceID,
                        choiceText: choiceText,
                        nextStep: nextStep
                    },
                    success: function(data, textStatus, jqXHR){
                        if( data.success ){
                            alert('Changes successfully saved.');
                        }else{
                            alert('Something went wrong!');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert(errorThrown);
                    }
                });
            });
        },

        /**
         * Uses jEditable on the meta data field
         * @param metaDataElem
         */
        initEditableMetaData: function( metaDataElem ){
            var gd_admin_nonce = $('#gd_admin_nonce').val();
            $( metaDataElem).editable(
                ajaxurl,
                {
                    cssclass: 'gd_metadata_input',
                    placeholder: '<span style="color: lightgrey;"><small>Click to edit</small></span>',
                    indicator: 'Saving...',
                    onblur: 'submit',
                    submitdata: {
                        action: 'gd_save_metadata',
                        gd_admin_nonce: gd_admin_nonce,
                        stepid: $(this).data('stepid')
                    }
                }
            );
        },

        init: function(){

            // Handlers for 'Save Choice', 'Is Milestone' and 'Is Visible' checkboxes/buttons
            this.saveChoiceSettings( $( '.save-choice' ) );
            this.selectIsVisible( $( '.is-visible-checkbox') );
            this.selectMilestone( $( '.is-milestone-checkbox' ) );
            this.initEditableMetaData( $( '.gd_metadata_editable' ) );

            // Make admin table sortable
            $('.gd_list_progress_pts #the-list').sortable({
                stop: function( event, ui ){
                    var sortedIDs = $( ".gd_list_progress_pts #the-list" ).sortable( "toArray" );
                    var gd_admin_nonce = $('#gd_admin_nonce').val();
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action : 'gd_set_step_order',
                            gd_admin_nonce : gd_admin_nonce,
                            step_order: sortedIDs
                        },
                        success: function(data, textStatus, jqXHR){
                            if( data.success ){
                                $('#choice-' + choiceID + '-' + postID).fadeOut().remove();
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert(errorThrown)
                        }
                    });
                }
            });

            this.bindDeleteComment( $('.edit-gd-choice-inline') );
            this.bindAddNewChoice( $( '.new-progress-pt-choice' ) );
            this.bindDeleteChoice( $('.delete-choice') );
        }
    };

    $(document).ready(function(){
        gd_admin.init();
    });
})( jQuery );

