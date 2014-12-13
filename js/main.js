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

        init: function(){

            this.bindDeleteComment( $('.delete-comment') );
            this.bindChoiceClick( $( '.gd-choice' ) );
            this.clearTeamProgress( $('#clear-team-progress') );

            // hide the quickpost widget if we're displaying a submitted post
            if( $('.gd-submission').length > 0 ){
                $('.sp-qp-new-post').hide();
            }
        }
    };

    gd.init();

})( jQuery );

