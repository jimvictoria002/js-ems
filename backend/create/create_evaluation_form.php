<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $event_id = $_POST['event_id'];

    $query = "INSERT INTO forms (event_id) VALUES ($event_id);";
    $result = $conn->query($query);

    $form_id = $conn->insert_id;

    $query = "UPDATE events SET f_id = $form_id WHERE event_id = $event_id";
    $result = $conn->query($query);

    $query = "INSERT INTO questionnaire (f_id, question, type) VALUES ($form_id, NULL, 'radio');";
    $result = $conn->query($query);

    $question_id = $conn->insert_id;

    $query = "INSERT INTO choices (q_id, choice_name) VALUES ($question_id, NULL );";
    $result = $conn->query($query);

    $choice_id = $conn->insert_id;

    ob_start();

    $select_form = "SELECT * FROM forms WHERE f_id = $form_id";
    $result = $conn->query($select_form);

    $row = $result->fetch_assoc();
    $f_id = $row['f_id'];
    $description = $row['description'];
    $title = $row['title'];
    $q_questions = "SELECT * FROM questionnaire WHERE f_id = $f_id";
    $r_questions = $conn->query($q_questions);

?>

    <div class="question-container flex flex-col shadow-lg p-5 rounded-md border mt-10 w-[98%] mx-auto my-2">

        <input type="text" name="title" value="<?= $title ?>" onchange="updateForm(this)" data-form-id="<?= $f_id ?>" placeholder="Enter form title" class="text-xl md:text-2xl font-semibold p-1 border-b border-b-gray-600 w-full outline-none " autocomplete="off">
        <input type="text" name="description" value="<?= $description ?>" onchange="updateForm(this)" data-form-id="<?= $f_id ?>" placeholder="Description" class="text-sm md:text-base my-3 p-1 border-b border-b-gray-600 w-[90%] outline-none " autocomplete="off">

        <button type="button" data-form-id="<?= $f_id ?>" data-event-id="<?= $event_id ?>" onclick="deleteForm(this)" class="px-3 md:px-4 py-1 text-sm md:text-base md:py-2 self-end bg-red-700 hover:bg-red-600 transition-default text-white font-semibold rounded-md md:rounded-xl mt-7">
            Delete form

        </button>

        <div class="flex items-center gap-2">
            <p>Form ID: <span id="formId"><?= $f_id ?></span> </p>
            <i class="fa-solid fa-copy text-xl cursor-pointer" onclick="copyText(this)"></i>
        </div>
        <script>
            function copyText(e) {
                // Get the text to copy
                var text = document.getElementById("formId").innerText;

                // Create a temporary input element
                var input = document.createElement("input");
                input.value = text;
                document.body.appendChild(input);

                // Select the text within the input element
                input.select();
                input.setSelectionRange(0, 99999); // For mobile devices

                // Copy the text to the clipboard
                document.execCommand("copy");

                // Remove the temporary input
                document.body.removeChild(input);

                $(e).css('color', 'green')
            }
        </script>

    </div>

    <?php require "../../src/input-functions.php"; ?>

    <?php while ($question = $r_questions->fetch_assoc()) : ?>
        <div class="question-container shadow-lg p-5 rounded-md border w-[98%] mx-auto my-11" id="question-main-container-<?= $question['q_id'] ?>">
            <div class="flex gap-x-10 md:flex-row flex-col-reverse">
                <div class="w-full">
                    <div class="w-full text-sm md:text-lg  flex flex-col">
                        <label for="" class="font-semibold">Question</label>
                        <input type="text" placeholder="Enter question" class=" p-1  outline-none border-b border-gray-400 rounded-sm w-full" id="question-<?= $question['q_id'] ?>" name="question" data-question-id="<?= $question['q_id'] ?>" value="<?= $question['question'] ?>" onchange="saveQuestionInput(this)">
                    </div>
                    <div class="w-full" id="question-container-<?= $question['q_id'] ?>">
                        <?php if ($question['type'] == 'radio') :
                            $q_id = $question['q_id'];
                            $q_choices = "SELECT * FROM choices WHERE q_id = $q_id";
                            $r_choices = $conn->query($q_choices);
                            $count = 1;
                        ?>
                            <div class="flex flex-col w-[80%] my-2 items-start">


                                <?php while ($choice = $r_choices->fetch_assoc()) : ?>
                                    <div class="flex items-center gap-2 my-3 text-lg w-full">
                                        <input type="radio" id="" disabled>
                                        <div class="flex flex-col w-full">
                                            <input type="text" name="question-choice-<?= $question['q_id'] ?>[]" class="question-choice p-1 border-b outline-none border-gray-400 text-sm rounded-sm w-full" placeholder="Choice <?= $count ?>" data-choice-count="<?= $count ?>" value="<?= $choice['choice_name'] ?>" id="<?= $choice['c_id'] ?>" data-choice-id="<?= $choice['c_id'] ?>" onkeyup="checkIfEnter(this)" onchange="saveChoice($(this),$(this).val(),<?= $choice['c_id'] ?>)">
                                        </div>
                                        <i class="fa-solid fa-x text-xs text-gray-400 cursor-pointer" onclick="removeChoice(<?= $choice['c_id'] ?>,this);"></i>

                                    </div>
                                <?php $count++;
                                endwhile; ?>



                                <p class="add-choice font-semibold cursor-pointer my-2 text-xs md:text-sm text-gray-600" data-question-id="<?= $question['q_id'] ?>" onclick="addChoice($(this))">Add
                                    choice
                                    <i class="fa-solid fa-plus"></i>
                                </p>
                            </div>
                        <?php else : ?>
                            <input type="text" placeholder="Answer" class="p-1 border-b mb-3 border-gray-400 mt-4 outline-none text-sm rounded-sm w-[80%]" name="event_title" disabled>
                        <?php endif; ?>
                    </div>


                </div>

                <div class="flex flex-col w-full md:w-[50%] text-sm md:text-lg">
                    <label for="" class="font-semibold">Question type</label>
                    <div class="w-full  text-sm md:text-lg my-2 gap-3 whitespace-nowrap">
                        <select name="type" class="question-input question-type p-1 border border-gray-300 outline-none  rounded-md  w-full" data-change="question-container-<?= $question['q_id'] ?>" data-question-id="<?= $question['q_id'] ?>" id="question-type-<?= $question['q_id'] ?>" onchange="saveQuestionInput(this)">

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
                    <input type="checkbox" name="required" class="question-input  cursor-pointer" data-question-id="<?= $question['q_id'] ?>" onchange="saveQuestionInput(this)" id="required-<?= $question['q_id'] ?>" <?= $question['required'] == 'yes' ? 'checked' : '' ?>>
                    <label for="required-<?= $question['q_id'] ?>" class="text-sm  cursor-pointer">Required</label>
                </div>
                <div class="self-end flex items-center gap-3">

                    <button type="button" onclick="deleteQuestion(this)" data-question-id="<?= $question['q_id'] ?>" data-delete-question="question-main-container-<?= $question['q_id'] ?>" class="px-4 py-2 self-end bg-red-700 hover:bg-red-600 transition-default text-white font-semibold rounded-xl">


                        <i class="fa-solid fa-trash text-yellow-50"></i></button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <button type="button" onclick="createNewQuestion(this)" class="px-6 self-center py-2  mb-10 text-sm md:text-base mr-10 bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl">Add
        new question

        <i class="fa-solid fa-plus text-yellow-300"></i></button>

<?php

    $result = ob_get_clean();

    echo $result;
}
