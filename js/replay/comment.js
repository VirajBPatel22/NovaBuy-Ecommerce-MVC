$(function () {
    
    const urlParams = new URLSearchParams(window.location.search);
    const ticketId = urlParams.get('ticket_id');
    let prevClass = '';
    $(document).on('click', ".add-btn", function (event) {
        const prevTd =   $(this).parent().prev('td');
        const parentId = prevTd.data('id');
        let classList = prevTd.attr('class');
        prevClass = (classList || '').split(' ').find(cls => cls.startsWith('level-'));
        const input = $('<input type="text" name="comments[]">').addClass('comment-input');
        const hidden = $('<input type="hidden" name="parent_ids[]">').val(parentId);
        $(this).parent().append(input).append(hidden);
    });
    $(document).on('change',".complate-comment", function () {
        const commentid = $(this).data("commentid");
        const isChecked = $(this).is(":checked");
        const parentid = $(this).data("parentid");
        if(isChecked){
            $.ajax({
                url: "http://localhost/ecommerecemvc/admin/replay_index/complete",
                type: "POST",
                data: {
                    comment_id : commentid,
                    is_complete: 1,
                    parent_id : parentid
                },
                success: function(response) {
                    console.log("data updated");
                    console.log(response);
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("Failed to add comment in table!");
                }
            });
    

        }
    });
    $(document).on('click', "#saveAllBtn", function (e) {
        let data = [];
        let parentIds = [];
        $('#commentTable tbody tr').each(function() {
            if(prevClass == undefined){
                parentIds.push(0);
            } else {
                parentIds.push($(this).find('.' + prevClass).data('id'));
            }
            
            $(this).find('.addComment').each(function() {
                const textInputs = $(this).find('input[type="text"]');
                const hiddenInputs = $(this).find('input[type="hidden"]');

                textInputs.each(function(index) {
                    const commentText = $(this).val().trim();
                    const parentId = hiddenInputs.eq(index).val();                    
                    if (commentText !== '') {
                        data.push({
                            'comment': commentText,
                            'parent_id': parentId == ticketId ? null : parentId,
                            'ticket_id': ticketId
                        });
                    }
                });
            });
        });
        const finalPayload = {
            comment: data,
            'parentIds': parentIds
        };
        console.log("Submitting:", finalPayload);

        $.ajax({
            url: "http://localhost/ecommerecemvc/admin/replay_index/save",
            type: "POST",
            data: finalPayload,
            success: function(response) {
                console.log("data updated");
                console.log(response);
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Failed to add comment in table!");
            }
        });

    });

});