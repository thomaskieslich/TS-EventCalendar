$(function () {
    $(".sortable_table").sortable({items: "tr", handle: "td"});
    $gp.inputs.DraftCheckbox = function () {
        this.checked ? $("#sb_field_publish").slideUp() : $("#sb_field_publish").slideDown()
    };

});

function initDateTime() {

    $('#title').focus();
    $.datepicker.setDefaults($.datepicker.regional[event_lang]);

    $('.datepicker').datepicker({
        dateFormat: event_date_format,
    });

    $('.timepicker').timepicker({
        defaultTime: '10:00'
    });
}