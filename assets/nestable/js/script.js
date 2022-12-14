$(document).ready(function () {
    var updateOutput = function () {
        $('#nestable-output').val(JSON.stringify($('#nestable').nestable('serialize')));
    };
    $('#nestable').nestable().on('change', updateOutput);

    updateOutput();

    $("#add-item").submit(function (e) {
        e.preventDefault();
        id = Date.now();
        var label = $("#add-item > [name='name']").val();
        var price1 = $("#add-item > [name='price1']").val();
        var price2 = $("#add-item > [name='price2']").val();
        if ((label == "")) return;
        var item =
            '<li class="dd-item dd3-item" data-id="' + id + '" data-label="' + label + '"  data-url="' + id + '">' +
            '<div class="dd-handle dd3-handle" > Drag</div>' +
            '<div class="dd3-content"><input type="text" class="form-control2" name="navigation_label" value="' + label + '">' +
            '<div class="item-delete">삭제</div>' +
            '<div class="item-edit">가격 입력</div>' +
            '</div>' +
            '<div class="item-settings d-none">' +
            '<p><label for="">위탁 수수료<br><input type="text" name="price1" value="' + price1 + '"></label></p>' +
            '<p><label for="">처리 수수료<br><input type="text" name="price2" value="' + price2 + '"></label></p>' +
            '<p>' +
            '<a class="item-close" href="javascript:;">닫기</a></p>' +
            '</div>' +
            '</li>';

        $("#nestable > .dd-list").append(item);
        $("#nestable").find('.dd-empty').remove();
        $("#add-item > [name='name']").val('');
        $("#add-item > [name='price1']").val('');
        $("#add-item > [name='price2']").val('');
        updateOutput();
    });

    $("body").delegate(".item-delete", "click", function (e) {
        $(this).closest(".dd-item").remove();
        updateOutput();
    });


    $("body").delegate(".item-edit, .item-close", "click", function (e) {

/*        console.log($(this).closest('li').data('id'));
        console.log($(this).closest(".dd-item"));*/
        var item_setting = $(this).closest(".dd-item").find(".item-settings");
        if (item_setting.hasClass("d-none")) {
            item_setting.removeClass("d-none");
        } else {
            item_setting.addClass("d-none");
        }
    });

    $("body").delegate("input[name='navigation_label']", "change paste keyup", function (e) {
        $(this).closest(".dd-item").data("label", $(this).val());
        //$(this).closest(".dd-item").find(".dd3-content span").text($(this).val());
    });

    $("body").delegate("input[name='price1']", "change paste keyup", function (e) {
        $(this).closest(".dd-item").data("price1", $(this).val());
    });
    $("body").delegate("input[name='price2']", "change paste keyup", function (e) {
        $(this).closest(".dd-item").data("price2", $(this).val());
    });



});