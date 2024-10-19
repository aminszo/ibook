Base_url = "http://localhost/ibook";

var Modal, EDIT_CATEGORY, DELETE_CATEGORY, ADD_BOOKMARK, EDIT_BOOKMARK, DELETE_BOOKMARK, Is_Disable = 1, flags = [0, 0];

AJAX_ERROR_MESSAGE = "خطایی در ارتباط با سرور رخ داده است." + "\n\n" + "لطفا صفحه را دوباره بارگذاری کنید.";

$(document).ready(function () {

    fix_height();

    //------------------------------ Show Options Button ----------------------------

    $(document).on("click", ".show-options", function () {
        has_class = !$(this).next().hasClass("show");
        $(".action-buttons").removeClass("show");
        $(this).next().toggleClass("show", has_class);
    });

    //------------------------------ COLOR SELECTOR ---------------------------------

    $(document).on("click", ".color-indicator", function () {
        $(".color-selector").fadeIn();
        $(window).click(function (event) { if (!($(event.target).is($(".color-indicator")))) { $(".color-selector").fadeOut(200) } });
    });

    $('.i-color').click(function () {
        $(this).prev().prop("checked", true);
        color_select_box = $(this).parent().parent();
        color_indicator = color_select_box.children("span.color-indicator");
        selected_color = color_select_box.find("input.color-i:checked").next().css("background-color");
        color_indicator.css("background-color", selected_color);
    });

    //------------------------------ ADD CATEGORY ---------------------------------

    $("#add-category").click(function () { open_modal("add-category-modal") });

    $("#add-category-accept").click(function () {
        if (!Is_Disable) {
            name_value = $("#category-title").val();
            side_value = $("input[name='side']:checked").val();
            color_value = $("input[name='color']:checked").val();

            add_category(name_value, side_value, color_value);
            close_modal();
        }
    });

    validate_category_title("category-title", "add-category-accept")

    //------------------------------ EDIT CATEGORY ---------------------------------

    $(document).on('click', ".group-header .act-button.edit", function () {
        open_modal("edit-category-modal");
        EDIT_CATEGORY = $(this).parent().parent().parent().parent();

        $("#category-title-edit").val(EDIT_CATEGORY.children(".group-header").children(".head-box").children("span").text());

        category_color = EDIT_CATEGORY.children(".group-header").attr("color_name");
        $("#category-edit-csb input." + category_color).prop("checked", true);
        $("#category-edit-csb .color-indicator").css("background-color", $("#category-edit-csb input.color-i:checked").next().css("background-color"));

        enable_accept_button();
    });

    $("#edit-category-accept").click(function () {
        if (!Is_Disable) {
            name_value = $("#category-title-edit").val();
            color_value = $("input[name='color-e']:checked").val();

            edit_category(name_value, color_value);
            close_modal();
        }
    });

    validate_category_title("category-title-edit", "edit-category-accept");

    //------------------------------ DELETE CATEGORY ---------------------------------

    $(document).on("click", ".group-header .act-button.delete", function () {
        open_modal("delete-category-modal");
        DELETE_CATEGORY = $(this).parent().parent().parent().parent();

        category_name = DELETE_CATEGORY.find(".group-header .head-box span").text();
        Modal.find(".modal-box .box-content p.message span").text(category_name);

        category_id = DELETE_CATEGORY.attr("category_id");
        get_categories_list(category_id);

        link_items = DELETE_CATEGORY.children(".group-content").children(".link-item");
        if (link_items.length < 1) {
            $("#bookmarks-decide-delete").prop("checked", true);
            enable_accept_button();
            Modal.find(".sub.message").fadeOut(1);
            Modal.find(".buttons-container").fadeOut(1);
            Modal.find(".fieldbox").fadeOut(1);
        } else {
            Modal.find(".sub.message").fadeIn(1);
            Modal.find(".buttons-container").fadeIn(1);
        }

        checked = $("input[name=bookmarks-decide]:checked").val();
        if (checked == '2') {
            $("#box_move_to").fadeIn(1);
            disable_accept_button();
        } else if (checked == '1') { enable_accept_button(); }
    });

    $("input[name=bookmarks-decide]").change(function () {
        checked = $("input[name=bookmarks-decide]:checked").val();
        moveto_option = $("select[name=move_to] option:selected").val();

        if (checked == '2') {
            $("#box_move_to").fadeIn(1);
            if (moveto_option == '0') { disable_accept_button() }
            else { enable_accept_button() }
        } else {
            $("#box_move_to").fadeOut(1);
            enable_accept_button();
        }
    });

    $("select[name=move_to]").change(function () {
        moveto_option = $("select[name=move_to] option:selected").val();
        if (moveto_option == '0') { disable_accept_button() }
        else { enable_accept_button() }
    });

    $("#delete-category-accept").click(function () {
        if (!Is_Disable) {
            category_id = DELETE_CATEGORY.attr("category_id");
            decide_option = $("input[name=bookmarks-decide]:checked").val();
            moveto_option = $("select[name=move_to] option:selected").val();

            delete_category(category_id, decide_option, moveto_option);
            close_modal();
        }
    });

    //------------------------------ ADD BOOKMARK ---------------------------------

    $(document).on("click", ".add-bookmark", function () {
        open_modal("add-bookmark-modal");
        ADD_BOOKMARK = $(this).parent().parent();
    });

    validate_bookmark_fields("bookmark-link", "bookmark-title", "add-bookmark-accept");

    $("#add-bookmark-accept").click(function () {
        if (!Is_Disable) {
            title_value = $("#bookmark-title").val();
            link_value = $("#bookmark-link").val();

            add_bookmark(title_value, link_value);
            close_modal();
        }
    });

    // The "get title" function is temporarily disabled. (Because it's not optimized and slows down the server).
    // $("#bookmark-link").blur(function () {
    //     title_value = $("#bookmark-title").val();
    //     if (title_value.length < 1) get_title($(this).val());
    // });

    //------------------------------ EDIT BOOKMARK ---------------------------------

    $(document).on("click", ".link-item .act-button.edit", function () {
        open_modal("edit-bookmark-modal");
        EDIT_BOOKMARK = $(this).parent().parent();

        $("#bookmark-link-edit").val(EDIT_BOOKMARK.children("a").attr("href"));
        $("#bookmark-title-edit").val(EDIT_BOOKMARK.children("a").text());
    });

    validate_bookmark_fields_2("bookmark-link-edit", "bookmark-title-edit", "edit-bookmark-accept");

    $("#edit-bookmark-accept").click(function () {
        if (!Is_Disable) {
            title_value = $("#bookmark-title-edit").val();
            link_value = $("#bookmark-link-edit").val();

            edit_bookmark(title_value, link_value);
            close_modal();
        }
    });

    //------------------------------ DELETE BOOKMARK ---------------------------------

    $(document).on("click", ".link-item .act-button.delete", function () {
        open_modal("delete-bookmark-modal");
        enable_accept_button();

        DELETE_BOOKMARK = $(this).parent().parent();
        bookmark_name = DELETE_BOOKMARK.children("a.anchor").text();
        Modal.find(".modal-box .box-content p.message span").text(bookmark_name);
    });

    $("#delete-bookmark-accept").click(function () {
        if (!Is_Disable) {
            delete_bookmark(DELETE_BOOKMARK.attr("link-id"));
            close_modal();
        }
    });

    //------------------------------ CLOSE MODAL ---------------------------------

    $(".cancel").click(function () { close_modal() });
    //$(window).click(function (event) { if ($(event.target).is(Modal)) { close_modal() } });

});

//------------------------------ MOVE UP/DOWN BOOKMARKS ---------------------------------

$(document).on("click", ".link-item .act-button.moveup", function () {
    bookmark_id = $(this).parent().parent().attr("link-id");
    move_bookmark(bookmark_id, "up");
    prev = $(this).parent().parent().prev();
    item = $(this).parent().parent().detach();
    item.insertBefore(prev);
});

$(document).on("click", ".link-item .act-button.movedown", function () {
    bookmark_id = $(this).parent().parent().attr("link-id");
    move_bookmark(bookmark_id, "down");
    next = $(this).parent().parent().next();
    item = $(this).parent().parent().detach();
    item.insertAfter(next);
});

//------------------------------ MOVE UP/DOWN/RIGHT/LEFT CATEGORY ---------------------------------

$(document).on("click", ".group-header .act-button.moveup", function () {
    category = $(this).parent().parent().parent().parent();
    move_category(category.attr("category_id"), "up")
    prev = category.prev();
    item = category.detach();
    item.insertBefore(prev);
});

$(document).on("click", ".group-header .act-button.movedown", function () {
    category = $(this).parent().parent().parent().parent();
    move_category(category.attr("category_id"), "down")
    next = category.next();
    item = category.detach();
    item.insertAfter(next);
});

$(document).on("click", ".group-header .act-button.moveright", function () {
    category = $(this).parent().parent().parent().parent();
    move_category(category.attr("category_id"), "right")
    right = $(".column.right");
    item = category.detach();
    right.append(item);
});

$(document).on("click", ".group-header .act-button.moveleft", function () {
    category = $(this).parent().parent().parent().parent();
    move_category(category.attr("category_id"), "left")
    left = $(".column.left");
    item = category.detach();
    left.append(item);
});

//------------------------------ AJAX FUNCTIONS ---------------------------------

function add_category(name, side, color) {
    $.ajax({
        url: Base_url + "/ajax_handler/add_category",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            name: name,
            side: side,
            color: color,
        },
        success: function (result) {
            if (result.status == true) {
                if (side === "1") place = $("#right-column");
                else place = $("#left-column");
                place.append(result.text);
            }
        },
        error: function () { alert(AJAX_ERROR_MESSAGE) },
    });
}

function edit_category(title, color) {
    category_id = EDIT_CATEGORY.attr("category_id");
    category_color = EDIT_CATEGORY.children(".group-header").attr("color_name");
    $.ajax({
        url: Base_url + "/ajax_handler/edit_category",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            title: title,
            color: color,
            category_id: category_id,
        },
        success: function (result) {
            if (result.status == true) {
                EDIT_CATEGORY.removeClass(category_color).addClass(result.color);
                EDIT_CATEGORY.children(".group-header").attr("color_name", result.color);
                EDIT_CATEGORY.children(".group-header").children(".head-box").children("span").text(result.title);
            }
        },
        error: function () { alert(AJAX_ERROR_MESSAGE) },
    });
}

function delete_category(category_id, decide_option, moveto_option) {
    $.ajax({
        url: Base_url + "/ajax_handler/delete_category",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            category_id: category_id,
            delete: decide_option,
            moveto: moveto_option,
        },
        success: function (result) {
            if (result.status == true) {
                if (decide_option == '1') { DELETE_CATEGORY.remove(); }
                else { location.reload(); }
            }
        },
        error: function () { alert(AJAX_ERROR_MESSAGE) },
    });
}

function get_categories_list(category_id) {
    $.ajax({
        url: Base_url + "/ajax_handler/get_except_categories",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            category_id: category_id,
        },
        success: function (result) {
            if (result.status == true) {
                $("#move_to").empty();
                $("#move_to").append("<option value=\"0\">انتقال به دسته...</option>");
                for (i = 0; i < result.categories.length; i++) {
                    $("#move_to").append("<option value=\"" + result.categories[i].id + "\">" + result.categories[i].name + "</option>");
                }
            }
        },
        error: function () { alert(AJAX_ERROR_MESSAGE) },
    });
}

function add_bookmark(title, link) {
    category_id = ADD_BOOKMARK.attr("category_id");
    $.ajax({
        url: Base_url + "/ajax_handler/add_bookmark",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            title: title,
            link: link,
            category_id: category_id,
        },
        success: function (result) {
            if (result.status == true) { ADD_BOOKMARK.children(".group-content").append(result.text) }
        },
        error: function () { alert(AJAX_ERROR_MESSAGE) },
    });
}

function get_title(link) {
    label = $("#bookmark-title").next("label").children("i");
    $(document).ajaxStart(function () { label.addClass("fa-spinner fa-spin").removeClass("fa-pennant"); });
    $.ajax({
        url: Base_url + "/ajax_handler/get_title",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            link: link,
        },
        success: function (result) {
            if (result.status == true) {
                $("#bookmark-title").val(result.text);
                check_a_field("bookmark-title", 100, 1, "add-bookmark-accept");
            }
        },
        timeout: 5000,
    });
    $(document).ajaxComplete(function () { label.removeClass("fa-spinner fa-spin").addClass("fa-pennant"); });
}

function edit_bookmark(title, link) {
    bookmark_id = EDIT_BOOKMARK.attr("link-id");
    $.ajax({
        url: Base_url + "/ajax_handler/edit_bookmark",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            title: title,
            link: link,
            bookmark_id: bookmark_id,
        },
        success: function (result) {
            if (result.status == true) {
                EDIT_BOOKMARK.children("a").attr("href", result.link);
                EDIT_BOOKMARK.children("a").text(result.title);
            }
        },
        error: function () { alert(AJAX_ERROR_MESSAGE); },
    });
}

function delete_bookmark(bookmark_id) {
    $.ajax({
        url: Base_url + "/ajax_handler/delete_bookmark",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            bookmark_id: bookmark_id,
        },
        success: function (result) {
            if (result.status == true) { DELETE_BOOKMARK.remove() }
        },
        error: function () { alert(AJAX_ERROR_MESSAGE); },
    });
}

function move_bookmark(bookmark_id, direction) {
    $.ajax({
        url: Base_url + "/ajax_handler/move_bookmark",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            bookmark_id: bookmark_id,
            direction: direction,
        },
        error: function () { alert(AJAX_ERROR_MESSAGE); },
    });
}

function move_category(category_id, direction) {
    $.ajax({
        url: Base_url + "/ajax_handler/move_category",
        type: "POST",
        data: {
            authentication: "#_asz_#",
            category_id: category_id,
            direction: direction,
        },
        error: function () { alert(AJAX_ERROR_MESSAGE); },
    });
}

//------------------------------ OTHER FUNCTIONS ---------------------------------

$(document).ajaxComplete(function () { fix_height(); });

$(window).resize(function () { fix_height() });

function open_modal(modal_id) {
    Modal = $("#" + modal_id);
    Is_Disable = 1;
    Modal.fadeIn(330);
}

function close_modal() {
    Modal.fadeOut(330);
    setTimeout(() => {
        Modal.find(':input[type=text]').val('');
        Modal.find(':input[type=text]').removeClass("wrong_value");
        Modal.find('button.accept').addClass("disabled");
        flags = [0, 0];
    }, 335);
}

function enable_accept_button() {
    Modal.find("button.accept").removeClass("disabled");
    Is_Disable = 0
}
function disable_accept_button() {
    Modal.find("button.accept").addClass("disabled");
    Is_Disable = 1
}

function validate_category_title(title_field_id, accept_button_id) {
    $("#" + title_field_id).keyup(function () {
        if ($(this).val().length < 1 || $(this).val().length > 45) {
            $("#" + accept_button_id).addClass("disabled");
            $(this).addClass("wrong_value");
            Is_Disable = 1;
        } else {
            $("#" + accept_button_id).removeClass("disabled");
            $(this).removeClass("wrong_value");
            Is_Disable = 0;
        }
    });
}

function check_a_field(field_id, max_length, flag_index, accept_button_id) {
    if ($("#" + field_id).val().length < 1 || $("#" + field_id).val().length > max_length) { $("#" + field_id).addClass("wrong_value"); flags[flag_index] = 0; }
    else { $("#" + field_id).removeClass("wrong_value"); flags[flag_index] = 1; }
    if (flags[0] && flags[1]) { Is_Disable = 0; $("#" + accept_button_id).removeClass("disabled"); }
    else { Is_Disable = 1; $("#" + accept_button_id).addClass("disabled") }
}

function validate_bookmark_fields(link_field_id, title_field_id, accept_button_id) {
    function validate_field(field_id, max_length, flag_index) {
        $("#" + field_id).keyup(function () { check_a_field(field_id, max_length, flag_index, accept_button_id) });
    }
    validate_field(link_field_id, 400, 0);
    validate_field(title_field_id, 100, 1);
}

function validate_bookmark_fields_2(link_field_id, title_field_id, accept_button_id) {
    function validate_field(field_id) {
        $("#" + field_id).keyup(function () {
            check_a_field(link_field_id, 400, 0, accept_button_id);
            check_a_field(title_field_id, 100, 1, accept_button_id);
        });
    }
    validate_field(link_field_id);
    validate_field(title_field_id);
}

function fix_height() {
    vHeight = $(window).height();
    bodyHeight = $('body').height();
    stdHeight = vHeight - bodyHeight;
    if (stdHeight > 0) { $("footer").css("position", "fixed"); }
    else { $("footer").css("position", "initial"); }
}