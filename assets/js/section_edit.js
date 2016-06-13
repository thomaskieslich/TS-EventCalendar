function gp_init_inline_edit(area_id, section_object) {


    gp_editing.editor_tools();

    gp_editor = {
        save_path: gp_editing.get_path(area_id),
        option_area: $('<form id="ec-options-form"/>').prependTo('#ckeditor_controls'),

        resetDirty: function () {
        },
        gp_saveData: function () {
            var options = $('#ec-options-form').serialize();
            return '&' + options;
        }
    };

    /* OPTIONS */
    $(
        '<div class="full_width">'
        + '<label>List Title</label>'
        + '<input type="text" style="width:100%;" name="listTitle" class="option-list-title ckeditor_input" value="' + section_object.listTitle + '" />'
        + '</div>')
        .appendTo(gp_editor.option_area);

    $(
        '<div class="full_width">'
        + '<label>Max Items</label>'
        + '<input type="text" style="width:100%;" name="maxItems" class="option-max-items ckeditor_input" value="' + section_object.maxItems + '" />'
        + '</div>')
        .appendTo(gp_editor.option_area);

    // var categories = '<option value=""> - </option>';
    var categories = '';

    $.each(EventCalendarCategories, function (index, value) {
        var selected = '';
        if ($.inArray(value.id + "", section_object.categories) != -1) {
            selected = 'selected';
        }
        categories += '<option value="' + value.id + '" ' + selected + '>' + value.label + '</option>';
    });

    $(
        '<div class="full_width">'
        + '<label>Categories</label>'
        + '<select name="categories[]" multiple class="gpinput full_width">'
        + categories
        + '</select>'
        + '</div>'
    ).appendTo(gp_editor.option_area);


    var layouts = ['List', 'Teaser'];
    $.each(layouts, function (index, value) {
        var selected = '';
        if(section_object.layout == value){
            selected = 'selected';
        }
        layouts += '<option value="' + value + '" ' + selected + '>' + value + '</option>';
    });

    $(
        '<div class="full_width">'
        + '<label>Layout</label>'
        + '<select name="layout" class="full_width">'
        + layouts
        + '</select>'
        + '</div></form>'
    ).appendTo(gp_editor.option_area);

    loaded();
}