$(document).ready(() => {

    $("#dccardform").hide()
    $("#paypalform").hide()

    $("#dccard").click(() => {
        $("#dccardform").slideToggle(500)
        $("#paypalform").hide()

        $("#pname").removeAttr('required');
        $("#pemail").removeAttr('required');

        $("#cname").attr('required', '');
        $("#cardnum").attr('required', '');
        $("#expire").attr('required', '');
        $("#cvv").attr('required', '');
    })

    $("#paypal").click(() => {
        $("#paypalform").slideToggle(500)
        $("#dccardform").hide()

        $('#pname').attr('required', '');
        $('#pemail').attr('required', '');

        $("#cname").removeAttr('required');
        $("#cardnum").removeAttr('required');
        $("#expire").removeAttr('required');
        $("#cvv").removeAttr('required');
    })

});