$(function () {
    $(".sortable_table").sortable({items: "tr", handle: "td"});
    $gp.inputs.DraftCheckbox = function () {
        this.checked ? $("#sb_field_publish").slideUp() : $("#sb_field_publish").slideDown()
    }
});