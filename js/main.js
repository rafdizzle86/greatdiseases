( function( $ ){
    // Great Diseases JS object
    var gd = {

        bindChoiceClick: function(choiceElem){
            var self = this;
            choiceElem.click(function(){
                var choice_id = $(this).data('choiceid');
                var step_id = $(this).data('stepid');
                self.recordDecision( choice_id, step_id );
                return false;
            });
        },

        /**
         * Records a decision/choice taken by a user
         * @param choice_id
         * @param step_id
         */
        recordDecision: function(choice_id, step_id){
            var gd_nonce = $('#gd_decision_step').val();
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action : 'gd_record_decision',
                    choiceID : choice_id,
                    stepID: step_id,
                    gd_nonce : gd_nonce
                },
                success: function(data, textStatus, jqXHR){
                    if( data.url ){
                        window.location.replace( data.url );
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert(errorThrown)
                }
            });
        },
        /**
         * Binds click event to deleteButton and
         * calls deleteComment() to delete hte comment.
         */
        bindDeleteComment: function(deleteElem){
            var self = this;
            deleteElem.click(function(){
                var commentID = $(this).data('commentid');
                self.deleteComment(commentID);
            });
        },
        /**
         * Deletes a comment given a commentID
         * @param commentID the comment ID
         */
        deleteComment: function( commentID ){
            if(commentID == undefined || commentID <= 0){
                alert('Invalid commentID!')
                return;
            }

            var deleteComment = confirm('Are you sure you want to delete this comment?');
            if( deleteComment ){
                var gd_nonce = $('#gd_nonce').val();
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action : 'delete_gd_comment',
                        commentID : commentID,
                        gd_nonce : gd_nonce
                    },
                    success: function(data, textStatus, jqXHR){
                        if(data.success){
                            $('#li-comment-' + data.commentID).remove()
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert(errorThrown)
                    }
                });
            }
        },
        /**
         * Clears a team's progress
         * @param clearElem
         */
        clearTeamProgress: function( clearElem ){
            clearElem.click(function(){
                var gd_nonce = $('#gd_clear_team_progress_nonce').val();
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action : 'gd_clear_team_progress',
                        gd_nonce : gd_nonce
                    },
                    success: function(data, textStatus, jqXHR){
                        if(data.success){
                            alert("Changes saved.");
                            location.reload();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert(errorThrown)
                    }
                });
            });
        },

        /**
         * Make step titles editable using jEditable
         * @param titleElem
         */
        initEditableTitle: function( titleElem ){
            $( titleElem ).editable(
                ajaxurl,
                {
                    cssclass: 'gd_editable_title',
                    placeholder: '<span style="color: lightgrey;"><small>Click to edit</small></span>',
                    indicator: 'Saving...',
                    onblur: 'submit',
                    submitdata: function(value, settings) {
                        return {
                            action: 'gd_save_submission_post_title',
                            gd_nonce: $('#gd_edit_submission_title_nonce').val(),
                            postid: $(this).data('postid')
                        };
                    }
                }
            );
        },

        /**
         * Triggers the upload button and for submission
         */
        triggerUploadPicButton: function( triggerElem, uploadButtonElem, formElem ){
            triggerElem.click( function(){
               uploadButtonElem.click();
            });
            uploadButtonElem.change( function(){
                formElem.submit();
            });
        },

        /**
         * Deletes profile pic
         * @param deleteElem
         * @param formElem
         */
        triggerDeletePicButton: function( deleteElem, formElem ){
            deleteElem.click(function(){
                var delete_input = '<input type="hidden" id="delete_avatar" name="delete_avatar" value="true" />';
                formElem.append( delete_input );
                formElem.submit();
            });
        },

        init: function(){

            var gd_avatar_upload_form = $('#gd-avatar-upload');

            this.bindDeleteComment( $('.delete-comment') );
            this.bindChoiceClick( $( '.gd-choice' ) );
            this.clearTeamProgress( $('#clear-team-progress') );
            this.initEditableTitle( $('.team-submission-title' ) );
            this.triggerUploadPicButton( $('#gd-upload-profile-pic'), $("#simple-local-avatar"), gd_avatar_upload_form );
            this.triggerDeletePicButton( $('#gd-remove-profile-pic'), gd_avatar_upload_form );

            // hide the quickpost widget if we're displaying a submitted post
            if( $('.gd-submission').length > 0 ){
                $('.sp-qp-new-post').hide();
            }
        }
    };

    gd.init();

})( jQuery );

