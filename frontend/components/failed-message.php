<div class="fixed z-50 bg-red-500 shadow-2xl font-semibold rounded-2xl px-5 text-white p-3 bottom-[5%] right-[5%]"
    id="failed-msg">
    <p class="text-sm md:text-lg"><?= $_SESSION['failed'] ?> <i class="fa-solid fa-circle-xmark"></i> </p>
</div>
<script>
    setTimeout(() => {
        $('#failed-msg').remove();

    }, 3000);
</script>