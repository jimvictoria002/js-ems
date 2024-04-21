<?php
$event_id = $_GET['event_id'];
$query = "SELECT * FROM forms WHERE event_id = $event_id";
$result = $conn->query($query);

?>
<div class="w-full flex flex-col items-start mb-40" id="evaluation-form">
    <?php if ($result->num_rows < 1): ?>
        <script>
            function create_form(e) {
                $(e).prop('disabled', true);
                $(e).removeClass('hover:bg-green-700');
                $(e).css({
                    'opacity': '.5',
                    'cursor': 'not-allowed'
                });
                $('#creating-note').show();
                setTimeout(() => {
                    $('#creating-note').hide();
                    $(e).hide();
                }, 1000);

                $.ajax({
                    type: "POST",
                    url: "../backend/create/create_evaluation_form.php",
                    data: {
                        event_id: <?= $_GET['event_id'] ?>
                    },
                    success: function (response) {
                        console.log(response);
                        window.location = '';
                    }
                });
            }

        </script>
        <button type="button"
            class="px-6 py-2 bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl"
            onclick="create_form(this)">Evaluation
            form <i class="fa-solid fa-plus text-yellow-300"></i></button>
        <p id="creating-note" class="text-sm hidden pl-1">Creating...</p>

    <?php else:
        $row = $result->fetch_assoc();
        $f_id = $row['f_id'];
        $q_questions = "SELECT * FROM questionnaire WHERE f_id = $f_id";
        $r_questions = $conn->query($q_questions);
        ?>
        <p class="text-3xl font-semibold">Evaluation form</p>
        <p class="text-sm my-3">For feedback purposes</p>

        <script>
            function removeChoice(c_id, e) {
                $.ajax({
                    type: "POST",
                    url: "../backend/delete/delete_choice.php",
                    data: {
                        c_id: c_id
                    },
                    success: function (response) {
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
                    success: function (response) {
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
                    success: function (c_id) {

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
                        success: function (response) {
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
                } else {
                    if (name == 'type') {
                        if (value == 'message') {
                            $.ajax({
                                type: "POST",
                                url: "../backend/delete/delete_choices.php",
                                data: {
                                    q_id: q_id
                                },
                                success: function (c_id) {
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
                                success: function (c_id) {
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
                        success: function (response) {
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
                            <input type="text" placeholder="Answer"
                                class="p-1 border-b mb-3 border-gray-400 mt-4 outline-none text-sm rounded-sm w-[80%]" name="event_title" disabled>

                        `;
            }

            function createNewQuestion(e) {
                $.ajax({
                    type: "POST",
                    url: "../backend/create/create_question.php",
                    data: {
                        f_id: <?= $f_id ?>
                    },
                    success: function (response) {

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
                        success: function (response) {

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

        <?php while ($question = $r_questions->fetch_assoc()): ?>
            <div class="question-container shadow-lg p-5 rounded-md border w-[98%] mx-auto my-11"
                id="question-main-container-<?= $question['q_id'] ?>">
                <div class="flex gap-x-10 md:flex-row flex-col-reverse">
                    <div class="w-full">
                        <div class="w-full text-sm md:text-lg  flex flex-col">
                            <label for="" class="font-semibold">Question</label>
                            <input type="text" placeholder="Enter question"
                                class=" p-1  outline-none border-b border-gray-400 rounded-sm w-full"
                                id="question-<?= $question['q_id'] ?>" name="question"
                                data-question-id="<?= $question['q_id'] ?>" value="<?= $question['question'] ?>"
                                onchange="saveQuestionInput(this)">
                        </div>
                        <div class="w-full" id="question-container-<?= $question['q_id'] ?>">
                            <?php if ($question['type'] == 'radio'):
                                $q_id = $question['q_id'];
                                $q_choices = "SELECT * FROM choices WHERE q_id = $q_id";
                                $r_choices = $conn->query($q_choices);
                                $count = 1;
                                ?>
                                <div class="flex flex-col w-[80%] my-2 items-start">


                                    <?php while ($choice = $r_choices->fetch_assoc()): ?>
                                        <div class="flex items-center gap-2 my-3 text-lg w-full">
                                            <input type="radio" id="" disabled>
                                            <div class="flex flex-col w-full">
                                                <input type="text" name="question-choice-<?= $question['q_id'] ?>[]"
                                                    class="question-choice p-1 border-b outline-none border-gray-400 text-sm rounded-sm w-full"
                                                    placeholder="Choice <?= $count ?>" data-choice-count="<?= $count ?>"
                                                    value="<?= $choice['choice_name'] ?>" id="<?= $choice['c_id'] ?>"
                                                    data-choice-id="<?= $choice['c_id'] ?>" onkeyup="checkIfEnter(this)"
                                                    onchange="saveChoice($(this),$(this).val(),<?= $choice['c_id'] ?>)">
                                            </div>
                                            <i class="fa-solid fa-x text-xs text-gray-400 cursor-pointer"
                                                onclick="removeChoice(<?= $choice['c_id'] ?>,this);"></i>

                                        </div>
                                        <?php $count++; endwhile; ?>



                                    <p class="add-choice font-semibold cursor-pointer my-2 text-xs md:text-sm text-gray-600"
                                        data-question-id="<?= $question['q_id'] ?>" onclick="addChoice($(this))">Add
                                        choice
                                        <i class="fa-solid fa-plus"></i>
                                    </p>
                                </div>
                            <?php else: ?>
                                <input type="text" placeholder="Answer"
                                    class="p-1 border-b mb-3 border-gray-400 mt-4 outline-none text-sm rounded-sm w-[80%]"
                                    name="event_title" disabled>
                            <?php endif; ?>
                        </div>


                    </div>

                    <div class="flex flex-col w-full md:w-[50%] text-sm md:text-lg">
                        <label for="" class="font-semibold">Question type</label>
                        <div class="w-full  text-sm md:text-lg my-2 gap-3 whitespace-nowrap">
                            <select name="type"
                                class="question-input question-type p-1 border border-gray-300 outline-none  rounded-md  w-full"
                                data-change="question-container-<?= $question['q_id'] ?>"
                                data-question-id="<?= $question['q_id'] ?>" id="question-type-<?= $question['q_id'] ?>"
                                onchange="saveQuestionInput(this)">

                                <option value="message">Message</option>
                                <option value="radio">Multiple choice</option>
                            </select>
                            <script>
                                $('#question-type-<?= $question['q_id'] ?>').val('<?= $question['type'] ?>');
                            </script>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full">
                    <div class="flex items-center mt-2 gap-1">
                        <input type="checkbox" name="required" class="question-input  cursor-pointer"
                            data-question-id="<?= $question['q_id'] ?>" onchange="saveQuestionInput(this)"
                            id="required-<?= $question['q_id'] ?>" <?= $question['required'] == 'yes' ? 'checked' : '' ?>>
                        <label for="required-<?= $question['q_id'] ?>" class="text-sm  cursor-pointer">Required</label>
                    </div>
                    <div class="self-end flex items-center gap-3">

                        <button type="button" onclick="deleteQuestion(this)" data-question-id="<?= $question['q_id'] ?>"
                            data-delete-question="question-main-container-<?= $question['q_id'] ?>"
                            class="px-4 py-2 self-end bg-red-700 hover:bg-red-600 transition-default text-white font-semibold rounded-xl">


                            <i class="fa-solid fa-trash text-yellow-50"></i></button>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>

        <button type="button" onclick="createNewQuestion(this)"
            class="px-6 self-center py-2 text-sm md:text-base mr-10 bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl">Add
            new question

            <i class="fa-solid fa-plus text-yellow-300"></i></button>

    <?php endif; ?>



    <script>
        $(document).ready(function () {



            // $('.question-input').on('change', function () {
            //     let name = $(this).prop('name');
            //     let q_id = $(this).data('questionId');
            //     let $this = $(this);
            //     let value = $(this).val();



            //     if (name == 'required') {
            //         let value = $(this).prop('checked') ? 'yes' : 'no';
            //         $(this).parent().after(`
            //             <p class="text-xs" id="save-note">Saving...</p>
            //         `);
            //         $.ajax({
            //             type: "POST",
            //             url: "../backend/update/update_questionnaire.php",
            //             data: {
            //                 field: name,
            //                 q_id: q_id,
            //                 value: value
            //             },
            //             success: function (response) {
            //                 console.log(response);
            //                 if (response == '0') {
            //                     $('#save-note').addClass('text-red-700').text('Could not save');
            //                 } else {
            //                     setTimeout(() => {
            //                         $('#save-note').remove();

            //                     }, 200)
            //                 }
            //             }
            //         });
            //     } else {
            //         if (name == 'type') {
            //             if (value == 'message') {
            //                 $.ajax({
            //                     type: "POST",
            //                     url: "../backend/delete/delete_choices.php",
            //                     data: {
            //                         q_id: q_id
            //                     },
            //                     success: function (c_id) {
            //                         let to_change = $this.data('change');
            //                         $('#' + to_change).html(messageOption(q_id));

            //                     }
            //                 });
            //             } else {

            //                 $.ajax({
            //                     type: "POST",
            //                     url: "../backend/delete/delete_choices.php",
            //                     data: {
            //                         q_id: q_id
            //                     },
            //                     success: function (c_id) {
            //                         let to_change = $this.data('change');
            //                         console.log(q_id)
            //                         $('#' + to_change).html(multipleChoice(q_id, 1, '', c_id));
            //                     }
            //                 });
            //             }
            //         }

            //         $(this).after(`
            //             <p class="text-xs" id="save-note">Saving...</p>
            //         `);
            //         $.ajax({
            //             type: "POST",
            //             url: "../backend/update/update_questionnaire.php",
            //             data: {
            //                 field: name,
            //                 q_id: q_id,
            //                 value: value
            //             },
            //             success: function (response) {
            //                 console.log(response)
            //                 if (response == '0') {
            //                     $('#save-note').addClass('text-red-700').text('Could not save');
            //                 } else {
            //                     setTimeout(() => {
            //                         $('#save-note').remove();

            //                     }, 200)
            //                 }
            //             }
            //         });
            //     }


            // })



            // $('.question-type').on('change', function () {
            //     let to_change = $(this).data('change');
            //     let q_id = $(this).data('questionId');
            //     let to_what = $(this).val();
            //     console.log();
            //     if (to_what == 'message') {
            //         $('#' + to_change).html(messageOption(q_id));
            //     } else {
            //         $('#' + to_change).html(multipleChoice(q_id, 1, value, c_id));
            //     }

            // });

        });</script>

</div>