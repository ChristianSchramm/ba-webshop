// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.

/**********************************************************
  @author mail@markus-falk.com
  @description sets css min-height of given elements to the highest calculated value
***********************************************************/

;(function($) {

  $.fn.mf_EqualHeight = function() {

    var heights = 0;

    this.each(function() {

      // cache object height
      var obj_height = $(this).height();

      // save it if it is higher than before
      if(heights < obj_height) {
        heights = obj_height;
      }
    });

    // set height of given elements to calculated value
    this.css('min-height', heights);

    // preserve chainability
    return this;
  };

})(jQuery);