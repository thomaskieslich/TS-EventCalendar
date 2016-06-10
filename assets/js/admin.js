$(function () {
    $(".sortable_table").sortable({items: "tr", handle: "td"});
    $gp.inputs.DraftCheckbox = function () {
        this.checked ? $("#sb_field_publish").slideUp() : $("#sb_field_publish").slideDown()
    };
    initColorpicker();
});

function initDateTime() {

    $('#title').focus();
    $.datepicker.setDefaults($.datepicker.regional[event_lang]);

    $('.datepicker').datepicker({
        // dateFormat: event_date_format,
    });

    $('.timepicker').timepicker({
        defaultTime: '10:00'
    });
}

function initColorpicker() {
    $('input.colorpicker ').spectrum({
        preferredFormat: "hex",
        allowEmpty: true,
        showInput: true,
        showAlpha: false
    });

}