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
