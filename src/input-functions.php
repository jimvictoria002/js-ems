<script>
    function removeChoice(c_id, e) {
        $.ajax({
            type: "POST",
            url: "../backend/delete/delete_choice.php",
            data: {
                c_id: c_id
            },
            success: function(response) {
                $(e).parent().remove();

            }
        });
    }



    function saveChoice(e, choice_name, c_id) {
        $(e).after(`<p class="text-xs" id="save-note">Saving...</p>`);
        $.ajax({
            type: "POST",
            url: "../backend/update/update_choice.php",
            data: {
                choice_name: choice_name,
                c_id: c_id
            },
            success: function(response) {
                console.log(response);
                if (response == '0') {
                    $('#save-note').addClass('text-red-700').text('Could not save');
                } else {
                    $('#save-note').remove();

                }
            }
        });
    };

    function addChoice(e) {
        var $this = $(e);
        let count = $this.prev().children().find("input[type='text']").data('choiceCount');
        let q_id = $this.data('questionId');

        if (isNaN(count)) {
            count = 0;
        }

        $.ajax({
            type: "POST",
            url: "../backend/create/create_choice.php",
            data: {
                q_id: q_id
            },
            success: function(c_id) {

                $this.before(`
                            <div class="flex items-center gap-2 my-3 text-lg w-full">
                                <input type="radio" id="" disabled>
                                <div class="flex flex-col w-full">
                                <input type="text" name="question-choice"
                                    class="question-choice p-1 border-b outline-none border-gray-400 text-sm rounded-sm w-full"
                                    placeholder="Choice ${count + 1}" data-choice-count="${count + 1}"
                                    value="" id="${count}" onkeyup="checkIfEnter(this)"
                                    data-choice-id="${c_id}"  onchange="saveChoice($(this),$(this).val(),${c_id})" >
                                    </div>
                                <i class="fa-solid fa-x text-xs text-gray-400 cursor-pointer"
                                    onclick="removeChoice(${c_id},this);"></i>
                            </div>
                                `);
                $this.prev().children().find("input[type='text']").focus();

            }
        });
    }

    function deleteForm(e) {

        if (confirm('Do you really want to delete this form?')) {
            let f_id = $(e).data('formId');
            $.ajax({
                type: "POST",
                url: "../backend/delete/delete_form.php",
                data: {
                    f_id: f_id
                },
                success: function(response) {

                    
                    // console.log(response);
                   window.location = "my-form.php";

                }
            });
        }
    }


    function unLinkForm(e) {

        if (confirm('Do you really want to unlink this form?')) {
            let event_id = $(e).data('eventId');
            $.ajax({
                type: "POST",
                url: "../backend/update/unlink_form.php",
                data: {
                    event_id: event_id
                },
                success: function(response) {
                    console.log(response);
                    if (response == 'unlinked') {
                        $('#create-form-link').show();

                        $('#form-container').html('');
                        $('#create-form-btn').show();
                        $('#create-form-btn').css({
                            'opacity': '',
                            'cursor': ''
                        });

                        $('#create-form-btn').prop('disabled', false);
                        $('#create-form-btn').addClass('hover:bg-green-700');
                    }



                }
            });
        }
    }

    function updateForm(e) {
        let name = $(e).prop('name');
        let f_id = $(e).data('formId');
        let $e = $(e);
        let value = $(e).val();

        $(e).after(`
            <p class="text-xs" id="save-note">Saving...</p>
        `);

        $.ajax({
            type: "POST",
            url: "../backend/update/update_form.php",
            data: {
                field: name,
                f_id: f_id,
                value: value
            },
            success: function(response) {
                console.log(response);
                if (response == '0') {
                    $('#save-note').addClass('text-red-700').text('Could not save');
                } else {
                    setTimeout(() => {
                        $('#save-note').remove();

                    }, 200)
                }
            }
        });
    }

    function saveQuestionInput(e) {
        let name = $(e).prop('name');
        let q_id = $(e).data('questionId');
        let $e = $(e);
        let value = $(e).val();



        if (name == 'required') {
            let value = $(e).prop('checked') ? 'yes' : 'no';
            $(e).parent().after(`
                <p class="text-xs" id="save-note">Saving...</p>
            `);
            $.ajax({
                type: "POST",
                url: "../backend/update/update_questionnaire.php",
                data: {
                    field: name,
                    q_id: q_id,
                    value: value
                },
                success: function(response) {
                    if (response == '0') {
                        $('#save-note').addClass('text-red-700').text('Could not save');
                    } else {
                        setTimeout(() => {
                            $('#save-note').remove();

                        }, 200)
                    }
                }
            });
        } else {
            if (name == 'type') {
                if (value == 'message') {
                    $.ajax({
                        type: "POST",
                        url: "../backend/delete/delete_choices.php",
                        data: {
                            q_id: q_id
                        },
                        success: function(c_id) {
                            let to_change = $e.data('change');
                            $('#' + to_change).html(messageOption(q_id));

                        }
                    });
                } else {

                    $.ajax({
                        type: "POST",
                        url: "../backend/delete/delete_choices.php",
                        data: {
                            q_id: q_id
                        },
                        success: function(c_id) {
                            let to_change = $e.data('change');
                            $('#' + to_change).html(multipleChoice(q_id, 1, '', c_id));
                        }
                    });
                }
            }

            $(e).after(`
                                <p class="text-xs" id="save-note">Saving...</p>
                            `);
            $.ajax({
                type: "POST",
                url: "../backend/update/update_questionnaire.php",
                data: {
                    field: name,
                    q_id: q_id,
                    value: value
                },
                success: function(response) {
                    console.log(response)
                    if (response == '0') {
                        $('#save-note').addClass('text-red-700').text('Could not save');
                    } else {
                        setTimeout(() => {
                            $('#save-note').remove();

                        }, 200)
                    }
                }
            });
        }


    }

    function multipleChoice(q_id, count, value, c_id) {
        return `
                <div class="flex flex-col w-[80%] my-2 items-start">
                    <div class="flex items-center gap-2 my-3 text-lg w-full">
                        <input type="radio" id="" disabled>
                        <div class="flex flex-col w-full">
                            <input type="text" name="question-choice-${q_id}[]"
                                class="question-choice p-1 border-b outline-none border-gray-400 text-sm rounded-sm w-full"
                                placeholder="Choice ${count}" onkeyup="checkIfEnter(this)" data-choice-count="${count}"
                                value="${value}" id="${c_id}"
                                data-choice-id="${c_id}"
                                onchange="saveChoice($(this),$(this).val(),${c_id})">
                        </div>
                        <i class="fa-solid fa-x text-xs text-gray-400 cursor-pointer"
                            onclick="removeChoice(${c_id},this);"></i>

                    </div>


                    <p class="add-choice font-semibold cursor-pointer my-2 text-xs md:text-sm text-gray-600"
                        data-question-id="${q_id}" onclick="addChoice($(this))">Add
                        choice
                        <i class="fa-solid fa-plus"></i>
                    </p>
                </div>
                `;
    }

    function messageOption(q_id) {
        return `
                            <input type="text" placeholder="Message"
                                class="p-1 border-b mb-3 border-gray-400 mt-4 outline-none text-sm rounded-sm w-[80%]" name="event_title" disabled>

                        `;
    }

    function createNewQuestion(e) {
        let type = $(e).prev().children().find('select').val();
        if (!type) {
            type = 'radio';
        }
        $.ajax({
            type: "POST",
            url: "../backend/create/create_question.php",
            data: {
                f_id: <?= $f_id ?>,
                type: type
            },
            success: function(response) {

                $(e).before(response)
            }
        });
    }

    function deleteQuestion(e) {

        if (confirm('Do you really want to delete this question?')) {
            let toDelete = $(e).data('deleteQuestion');
            let q_id = $(e).data('questionId');
            $.ajax({
                type: "POST",
                url: "../backend/delete/delete_question.php",
                data: {
                    q_id: q_id
                },
                success: function(response) {

                    $('#' + toDelete).addClass('transition-default');
                    $('#' + toDelete).css('opacity', '0');
                    setTimeout(() => {
                        $('#' + toDelete).remove();
                    }, 500);

                }
            });
        }
    }

    function checkIfEnter(e) {
        let key = event.key;
        if (key === "Enter") {
            $(e).parent().parent().next().click();
            var tabKeyEvent = $.Event("keydown", {
                key: "Tab",
                code: "Tab",
                keyCode: 9,
                which: 9,
                shiftKey: false,
                altKey: false,
                ctrlKey: false,
                metaKey: false
            });
            $(e).trigger(tabKeyEvent);
        }
    }
</script>