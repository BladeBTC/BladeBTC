/**
 * Date Range Picker - Settings
 */
var dateRangePickerSettings = {
    showDropdowns: true,
    ranges: {
        'Aujourd\'hui': [moment(), moment()],
        'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Les 7 derniers jours': [moment().subtract(6, 'days'), moment()],
        'Les 30 derniers jours': [moment().subtract(29, 'days'), moment()],
        'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
        'Le mois dernier': [moment().subtract(1, 'month').startOf('month'),
            moment().subtract(1, 'month').endOf('month')]
    },
    alwaysShowCalendars: true,
    autoApply: true,
    autoUpdateInput: true,
    applyClass: 'btn-sm btn-primary',
    cancelClass: 'btn-sm btn-default',
    locale: {
        format: 'DD-MM-YYYY',
        separator: ' / ',
        applyLabel: 'Sélectionner',
        cancelLabel: 'Annuler',
        fromLabel: 'De',
        toLabel: 'À',
        customRangeLabel: 'Personnalisé',
        daysOfWeek: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre',
            'Décembre']
    }
};


/**
 * Tool tip and popover
 */
$('[data-toggle="tooltip"]').tooltip();
$('[data-toggle="popover"]').popover();

/**
 * iCheck
 */
$('input').iCheck({
    checkboxClass: 'icheckbox_square-red',
    radioClass: 'iradio_square-red'
});

/**
 * Custom confirm message
 */
function iconfirm(title, msg, href) {
    var loc = href;

    alertify.confirm(title, msg,

        //ok
        function () {
            document.location.href = loc;
        },

        //cancel
        function () {
            return true;
        }
    ).set('labels', {ok: 'Oui', cancel: 'Non'});

    return false;
}

/**
 * Bootstrap datepicker defaults options
 *
 * @type {string}
 */
$.fn.datepicker.defaults.format = "dd/mm/yyyy";
$.fn.datepicker.defaults.language = "fr";
$.fn.datepicker.defaults.todayHighlight = true;
$.fn.datepicker.defaults.weekStart = 0;