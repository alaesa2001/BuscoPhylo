$(function() {
  /* variables */
  var percent = $('.percent');
  var bar = $('.bar');
  var p = 0;

  /* submit form with ajax request using jQuery.form plugin */
  $('form').ajaxForm({
    /* set data type json */
    dataType:'json',

    /* reset before submitting */
    beforeSend: function() {
        bar.width('0%');
        percent.html('0%');
    },

    /* progress bar call back*/
    uploadProgress: function(event, position, total, percentComplete) {
        var pVel = percentComplete + '%';
        if(pVel != "100%"){
        bar.width(pVel);
        percent.html(pVel);
        }
        if(pVel == "100%" && p != 10){
        bar.width(pVel);
        percent.html(pVel);
        p = 10;

        /* Wait 2 seconds before redirecting */
        setTimeout(function() {
            window.location.href = 'https://buscophylo.inra.org.ma/process.php';
        }, 1000);
        }
    },

    /* reset form after success */
    resetForm: function() {
        p = 0;
    }

  });
});