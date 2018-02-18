import 'bootstrap';
import './bootstrap.file-input';
import 'jquery-inputmask';

$(document).ready(function() {
    $('input[type=file]').bootstrapFileInput();
    $('input.js-date-mask').mask('99/99/9999');

    var $hamburger = $(".hamburger");
    $hamburger.on("click", function(e) {
      $hamburger.toggleClass("is-active");
    });

    /** Numerci input */
    $('input.js-numeric').restrictKey(/[0-9]/);
});


(function($) {
    $.fn.restrictKey = function(regexp)
    {
        $(this).on("keypress", function(event) {
            var key = String.fromCharCode(event.which);

            return regexp.test(key) || event.which == 8 || event.which == 0;
        });
    };
})(jQuery);
