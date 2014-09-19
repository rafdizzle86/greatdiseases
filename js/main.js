( function( $ ){
    // Great Diseases JS object
    var gd = {
        /**
         * Binds click event to deleteButton and
         * calls deleteComment() to delete hte comment.
         */
        bindEditChoices: function(deleteElem){
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
        init: function(){
            this.bindEditChoices( $('.delete-comment') );
        }
    };

    gd.init();

})( jQuery );

