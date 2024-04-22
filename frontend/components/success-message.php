<div class="fixed z-50 bg-green-500 shadow-2xl font-semibold rounded-2xl px-5 text-white p-3 bottom-[5%] right-[5%]"
    id="success-msg">
    <p class="text-sm md:text-lg"><?= $_SESSION['success'] ?> <i class="fa-solid fa-circle-check"></i> </p>
</div>
<script>
    setTimeout(() => {
        $('#success-msg').remove();

    }, 3000);
</script>