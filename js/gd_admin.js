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
        bindEditChoices: function(editChoicesElem){
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
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert(errorThrown)
                }
            });
        },

        /**
         * Binds click event to the saveChoicesSettings element
         * @param saveChoiceSettingsElem
         */
        bindSaveChoiceSettings: function(saveChoiceSettingsElem){
            var self = this;
            saveChoiceSettingsElem.click(function(){
                this.saveChoiceSettings();
            });
        },
        /**
         * Serializes choice form and saves changes to the various choices
         */
        saveChoiceSettings: function(){

        },

        init: function(){
            this.bindEditChoices( $('.edit-gd-choice-inline') );
            this.bindAddNewChoice( $( '.new-progress-pt-choice' ) );
        }
    };

    $(document).ready(function(){
        gd_admin.init();
    });
})( jQuery );

