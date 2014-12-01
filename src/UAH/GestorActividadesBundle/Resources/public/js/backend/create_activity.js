$(window).load(function() {
    $('.selectpicker').selectpicker();
    $('.celebration_dates').datepicker({
        format: "dd/mm/yyyy",
        weekStart: 1,
        language: "es",
        multidate: true,
        multidateSeparator: ",",
        todayHighlight: true
    });
    $('.publicity_date').datepicker({
        format: "dd/mm/yyyy",
        weekStart: 1,
        language: "es",
        multidate: false,
        todayHighlight: true
    });
});