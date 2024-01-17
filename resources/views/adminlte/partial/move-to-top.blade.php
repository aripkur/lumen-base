<div class="move-to-top">
    <button class="btn btn-outline-danger" id="move_to_top"><i class="fas fa-arrow-up"></i></button>
</div>

<script type="text/javascript">
    $("#move_to_top").click(function () {
        window.moveToTop()
    });
    window.moveToTop = () => {
        $("html, body").animate({
            scrollTop: 0
        }, 500);
    }
    $(window).on("load scroll", function () {
        if ($(window).scrollTop() > 200) {
            $("#move_to_top").removeClass("d-none");
        }else{
            $("#move_to_top").addClass("d-none");
        }
    });
</script>
