<form action="../backend/update/form_done.php" method="POST" class="flex flex-col  w-full md:w-[90%]  mx-auto" id="answer-from">
    <div class="flex flex-col shadow-lg p-5 rounded-md border mt-5 w-full my-2 mb-10">
        <p class="text-2xl font-semibold md:text-3xl "><?= $form_title ?></p>
        <p class="text-lg mt-2">
            <?= $event_description  ?>
        </p>
        <div class="flex flex-col mt-5">
            <?php if ($is_done) : ?>
                <p class="text-xs font-semibold ">This form recorded as</p>

            <?php else : ?>
                <p class="text-xs font-semibold ">Your response will be recorded as</p>


            <?php endif; ?>
            <p class=" text-base "><?= $fullname .  ' | ' . $email   ?></p>

        </div>
    </div>
    <input type="hidden" name="r_f_id" value="<?= $r_f_id ?>">
    <?php
    $required_fields = [];

    while ($question = $result->fetch_assoc()) : ?>
        <div class="flex flex-col shadow-lg p-5 rounded-md border w-full mt-5 my-7 " id="parent-<?= $question['q_id'] ?>">
            <?php
            $q_id = $question['q_id'];

            if ($question['required'] == 'yes') {
                $required_fields[$question['q_id']] = 'required';
                $required = '<span class="text-red-600"> *</span>';
            } else {
                $required = '';
            }
            ?>
            <p class="text-base md:text-lg font-semibold cursor-default"><?= $question['question'] . $required; ?></p>
            <?php if ($question['type'] == 'radio') : ?>
                <?php
                $q_choices = "SELECT * FROM choices WHERE q_id = $q_id ";
                $r_choices = $conn->query($q_choices);
                ?>
                <div class="flex flex-col my-3">
                    <?php while ($choice = $r_choices->fetch_assoc()) : ?>
                        <div class="flex my-2">
                            <input type="radio" class="form-input <?= ($is_done ? "" : 'cursor-pointer') ?>" name="<?= $q_id ?>" value="<?= $choice['c_id'] ?>" <?= ($is_done ? "disabled" : '') ?> id="<?= $choice['c_id'] ?>" <?= $choice['c_id'] == $question['answer'] ? 'checked' : '' ?>>
                            <label for="<?= $choice['c_id'] ?>" class="<?= ($is_done ? "" : 'cursor-pointer') ?> pl-1 text-sm md:text-base"><?= $choice['choice_name'] ?></label>
                        </div>

                    <?php endwhile; ?>
                </div>

            <?php else : ?>
                <textarea name="<?= $q_id ?>" placeholder="Message" <?= ($is_done ? "disabled" : '') ?> class="form-input border p-2 my-2 rounded-md text-sm md:text-base  <?= ($is_done ? "opacity-60" : '') ?>" cols="30" rows="4"><?= $question['answer'] ?></textarea>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
    <div class="w-full flex justify-end my-4 pr-5 mb-5">
        <?php if ($is_done) : ?>

        <?php else : ?>
            <button class="px-6 py-2 self-end md:text-base text-sm  bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl">Submit</button>


        <?php endif; ?>
    </div>
    <script>
        $('.form-input').on('change', function() {
            let answer = $(this).val();
            let q_id = $(this).prop('name');
            let $parent = $(this).parent();
            $parent.css('opacity', '.3');
            $.ajax({
                url: '../backend/update/update_answer.php',
                type: 'POST',
                data: {
                    answer: answer,
                    q_id: q_id,
                    r_f_id: <?= $r_f_id ?>,

                },
                success: function(response) {
                    console.log(response)
                    $parent.css('opacity', '1');

                }

            });
        })
        let required_fields = <?php echo json_encode($required_fields); ?>;
        $('#answer-from').validate({
            rules: required_fields,
            errorPlacement: function(error, element) {
                $('#parent-' + $(element).prop('name')).addClass('border-2 border-red-600');
                console.log($(element).prop('name'))
            },
            success: function(label, element) {
                $('#parent-' + $(element).prop('name')).removeClass('border-2 border-red-600');
                console.log($(element).prop('name'))
            },
            submitHandler: function(form) {

                if (confirm("You're about to submit the form \n\nOnce submitted you can't edit this form again.")) {
                    form.submit();
                }

            }
        });
    </script>

    </from>