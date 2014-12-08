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
                var choiceText = $('#choice-text-' + choiceID + '-' + postID).val()
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
         * Make step titles editable using jEditable
         * @param titleElem
         */
        initEditableStepTitle: function( titleElem ){
            var gd_admin_nonce = $('#gd_admin_nonce').val();
            $( titleElem ).editable(
                ajaxurl,
                {
                    cssclass: 'gd_metadata_input',
                    placeholder: '<span style="color: lightgrey;"><small>Click to edit</small></span>',
                    indicator: 'Saving...',
                    onblur: 'submit',
                    submitdata: function(value, settings) {
                        return {
                            action: 'gd_save_post_title',
                            gd_admin_nonce: gd_admin_nonce,
                            stepid: $(this).data('stepid')
                        };
                    }
                }
            );
        },

        /**
         * Make progress step titles editable
         * @param titleElem
         */
        initEditableProgressStepTitle: function( titleElem ){
            var gd_admin_nonce = $('#gd_admin_nonce').val();
            $( titleElem ).editable(
                ajaxurl,
                {
                    cssclass: 'gd_metadata_input',
                    placeholder: '<span style="color: lightgrey;"><small>Click to edit</small></span>',
                    indicator: 'Saving...',
                    onblur: 'submit',
                    submitdata: function(value, settings) {
                        return {
                            action: 'gd_save_progress_step_title',
                            gd_admin_nonce: gd_admin_nonce,
                            stepid: $(this).data('stepid')
                        };
                    }
                }
            );
        },

        /**
         * Delets a progress tracker step
         * @param metaDataElem
         */
        deleteProgressTrackerStep: function( deleteElem ){
            var gd_admin_nonce = $('#gd_admin_nonce').val();
            deleteElem.click(function(){
                var stepid = $(this).data('stepid');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'gd_delete_progress_tracker_step',
                        gd_admin_nonce: gd_admin_nonce,
                        stepid: stepid
                    },
                    success: function(data, textStatus, jqXHR){
                        if( data.success ){
                            $("#" + stepid).remove();
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
                    submitdata: function(value, settings) {
                        return {
                            action: 'gd_save_metadata',
                            gd_admin_nonce: gd_admin_nonce,
                            stepid: $(this).data('stepid')
                        };
                    }
                }
            );
        },

        /**
         * Handles adding "required" steps for the progress tracker
         * @param addElem
         */
        initProgressTrackerStepAdder: function( addElem ){
            addElem.click(function(){
                var selected_step = $('#gd_steps_dropdown option:selected').text();
                var selected_step_id = $('#gd_steps_dropdown option:selected').val();
                var andOrDropdown = '<select id="' + selected_step_id + '-bool"><option>or</option><option>and</option></select>';

                var stepTable = $('#required-completed-steps');

                if( !stepTable.is(":visible") ){
                    stepTable.show();
                }

                if( stepTable.children().length > 1 ){
                    $('<tr class="required-step"><td>' +
                            selected_step + '<input type="hidden" id="step-' + selected_step_id + '" value="' + selected_step_id + '">' +
                    '</td>' +
                    '<td>' +
                        andOrDropdown + '</td>'+
                    '<td>' +
                        '<span id="delete-required-step-' + selected_step_id + '">Delete</span>' +
                    '</td></tr>').prependTo('#required-completed-steps');
                }else{
                    $('<tr class="required-step">' +
                        '<td>' +
                            selected_step + '<input type="hidden" id="step-' + selected_step_id + '" value="' + selected_step_id + '">' +
                        '</td><td></td><td>' +
                            '<span id="delete-required-step-' + selected_step_id + '">Delete</span>' +
                        '</td></tr>').prependTo('#required-completed-steps');
                }

                $('#delete-required-step-' + selected_step_id).click(function(){
                    $(this).closest('tr').remove();
                });
            });
        },

        /**
         * Submit progress bar logic/steps
         */
        submitProgressBarLogic: function( submitElem ){
            var gd_admin_nonce = $('#gd_admin_nonce').val();
            submitElem.click(function(){

                var stepTableRows = $('#required-completed-steps').find('tr'); // iterate through all the rows
                var requiredSteps = {}; // create object to capture step text and logic

                stepTableRows.each( function(i, row){
                    if( i > 0 ){
                        var $row = $(row);
                        var stepid = $row.find('input').val(); // capture step id
                        var stepLogic = $row.find('select option:selected').val(); // capture logic operator ('and' or 'or')
                        if( stepLogic == undefined ){
                            stepLogic = false;
                        }
                        requiredSteps[stepid] = stepLogic;
                    }
                });

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'gd_set_progress_tracker_steps',
                        gd_admin_nonce: gd_admin_nonce,
                        stepText: $('#progress-tracker-step-name').val(),
                        requiredSteps: requiredSteps
                    },
                    success: function(data, textStatus, jqXHR){
                        if( data.success ){
                            location.reload();
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
         * Makes the step table sortable
         */
        makeStepTableSortable: function( tableElem ){
            // Make admin table sortable
            tableElem.sortable({
                handle: ".step-sorting-handle",
                stop: function( event, ui ){
                    var sortedIDs = tableElem.sortable( "toArray" );
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
        },

        /**
         * Makes the progress tracker table sortable
         */
        makeProgressTrackerTableSortable: function( tableElem ){
            // Make admin table sortable
            tableElem.sortable({
                handle: ".step-sorting-handle",
                stop: function( event, ui ){
                    var sortedIDs = tableElem.sortable( "toArray" );
                    var gd_admin_nonce = $('#gd_admin_nonce').val();
                    console.log( sortedIDs );

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action : 'gd_set_progress_step_order',
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
        },

        init: function(){

            // Handlers for various buttons/elements on the admin page
            this.saveChoiceSettings( $( '.save-choice' ) );
            this.selectIsVisible( $( '.is-visible-checkbox') );
            this.selectMilestone( $( '.is-milestone-checkbox' ) );
            this.initEditableMetaData( $( '.gd_metadata_editable' ) );
            this.initEditableStepTitle( $( '.gd_post_title_editable' ) );
            this.initEditableProgressStepTitle( $('.step-text') );
            this.initProgressTrackerStepAdder( $('#gd_progress_tracker_new_step') );
            this.submitProgressBarLogic( $('#gd-progress-tracker-settings-submit') );
            this.makeStepTableSortable( $('.gd_list_progress_pts #the-list') );
            this.makeProgressTrackerTableSortable( $('.progress-tracker-steps #the-list') );
            this.bindDeleteComment( $('.edit-gd-choice-inline') );
            this.bindAddNewChoice( $( '.new-progress-pt-choice' ) );
            this.bindDeleteChoice( $('.delete-choice') );
            this.deleteProgressTrackerStep( $('.delete-progress-step' ) );

        }
    };

    $(document).ready(function(){
        gd_admin.init();
    });
})( jQuery );

