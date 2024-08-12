$(function() {
    // 詳細ボタンのモーダル表示
    $(".modal-open").click(function() {

        var modal_id = "#modal-content-" + $(this).attr('id');
        $(this).blur();
        if ($("#modal-overlay")[0]) return false;
        $("body").append('<div id="modal-overlay"></div>');
        $("#modal-overlay").fadeIn("slow");
        centeringModalSyncer(modal_id);
        $(modal_id).fadeIn("slow");

        $("#modal-overlay,#modal-close").unbind().click(function() {

            $(modal_id + ",#modal-overlay").fadeOut("slow", function() {
                $('#modal-overlay').remove();
            });
        });
    });

    $(window).resize(centeringModalSyncer);

    function centeringModalSyncer(modal_id) {
        var w = $(window).width();
        var h = $(window).height();
        var cw = $(modal_id).outerWidth();
        var ch = $(modal_id).outerHeight();

        $(modal_id).css({
            "left": ((w - cw) / 2) + "px",
            "top": ((h - ch) / 2) + "px"
        });
    }

    // リセットボタンの入力値初期化
    $('.search-form__button-reset').on('click', function() {
        $('.search-form__item-input').val("");
        $('.search-form__item-select').val("");


        $('.admin__alert--success').remove();
    });

});