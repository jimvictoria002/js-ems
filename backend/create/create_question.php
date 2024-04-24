<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $f_id = $_POST['f_id'];
    $type = $_POST['type'];

    $query = "INSERT INTO questionnaire (f_id, question, type) VALUES ($f_id, NULL, '$type');";
    $result = $conn->query($query);

    $question_id = $conn->insert_id;

    if ($type == 'radio') {
        $query = "INSERT INTO choices (q_id, choice_name) VALUES ($question_id, NULL );";
        $result = $conn->query($query);

        $choice_id = $conn->insert_id;
    }

    $question_card = '';

    ob_start();
    ?>

    <div class="question-container shadow-lg p-5 rounded-md border w-[98%] mx-auto my-11"
        id="question-main-container-<?= $question_id ?>">
        <div class="flex gap-x-10 md:flex-row flex-col-reverse">
            <div class="w-full">
                <div class="w-full text-sm md:text-lg  flex flex-col">
                    <label for="" class="font-semibold">Question</label>
                    <input type="text" placeholder="Enter question"
                        class=" p-1  outline-none border-b border-gray-400 rounded-sm w-full"
                        id="question-<?= $question_id ?>" name="question" data-question-id="<?= $question_id ?>" value=""
                        onchange="saveQuestionInput(this)">
                </div>
                <div class="w-full" id="question-container-<?= $question_id ?>">


                    <?php if ($type == 'radio'): ?>
                        <div class="flex flex-col w-[80%] my-2 items-start">

                            <div class="flex items-center gap-2 my-3 text-lg w-full">
                                <input type="radio" id="" disabled>
                                <div class="flex flex-col w-full">
                                    <input type="text" name="question-choice-<?= $question_id ?>[]"
                                        class="question-choice p-1 border-b outline-none border-gray-400 text-sm rounded-sm w-full"
                                        onkeyup="checkIfEnter(this)" placeholder="Choice 1" data-choice-count="1" value=""
                                        id="<?= $choice_id ?>" data-choice-id="<?= $choice_id ?>"
                                        onchange="saveChoice($(this),$(this).val(),<?= $choice_id ?>)">
                                </div>
                                <i class="fa-solid fa-x text-xs text-gray-400 cursor-pointer"
                                    onclick="removeChoice(<?= $choice_id ?>,this);"></i>

                            </div>



                            <p class="add-choice font-semibold cursor-pointer my-2 text-xs md:text-sm text-gray-600"
                                data-question-id="<?= $question_id ?>" onclick="addChoice($(this))">Add
                                choice
                                <i class="fa-solid fa-plus"></i>
                            </p>
                        </div>

                    <?php else: ?>
                        <input type="text" placeholder="Message"
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
                        data-change="question-container-<?= $question_id ?>" data-question-id="<?= $question_id ?>"
                        id="question-type-<?= $question_id ?>" onchange="saveQuestionInput(this)">

                        <option value="message">Message</option>
                        <option value="radio">Multiple choice</option>
                    </select>
                    <script>
                        $('#question-type-<?= $question_id ?>').val('<?= $type ?>');
                    </script>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-full">
            <div class="flex items-center mt-2 gap-1">
                <input type="checkbox" name="required" class="question-input  cursor-pointer"
                    data-question-id="<?= $question_id ?>" onchange="saveQuestionInput(this)"
                    id="required-<?= $question_id ?>" checked>
                <label for="required-<?= $question_id ?>" class="text-sm  cursor-pointer">Required</label>
            </div>
            <div class="self-end flex items-center gap-3">

                <button type="button" onclick="deleteQuestion(this)" data-question-id="<?= $question_id ?>"
                    data-delete-question="question-main-container-<?= $question_id ?>"
                    class="px-4 py-2 self-end bg-red-700 hover:bg-red-600 transition-default text-white font-semibold rounded-xl">


                    <i class="fa-solid fa-trash text-yellow-50"></i></button>
            </div>
        </div>
    </div>

    <?php
    $question_card .= ob_get_clean();

    echo $question_card;
}



