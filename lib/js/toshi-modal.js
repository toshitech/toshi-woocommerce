(function($, toshi) {
  var hideFactory = function(element, overlay) {
    return function() {
      element.hide();
      overlay.hide();
    };
  };

  var showFactory = function(element, overlay) {
    return function() {
      element.show();
      overlay.show();
    };
  };

  var preventDefaultBefore = function(f) {
    return function(event) {
      event.preventDefault();
      f(event);
    };
  };

  // Calls the callback with the value of the element supplied. 
  // It will call initially if the value is present, and listen for further changes.
  var bindToFieldValue = function(el, f) {
    if (el.val()) {
      f(el.val());
    }

    el.on('change', function(event) {
      f(el.val());
    });
  };

  var bindToFields = function() {
    bindToFieldValue($('form.checkout [name=shipping_first_name]'), function(
      value
    ) {
      getModal().setFirstName(value);
    });

    bindToFieldValue($('form.checkout [name=shipping_last_name]'), function(
      value
    ) {
      getModal().setLastName(value);
    });

    bindToFieldValue($('form.checkout [name=shipping_last_name]'), function(
        value
      ) {
        getModal().setLastName(value);
      });
  };

  $(document).ready(function() {
    bindToFields();
  });

  var options = {};
  var _modal;

  var getModal = function() {
    if (typeof _modal !== 'undefined') {
      return _modal;
    }

    _modal = window.toshi.createToshiModal({
      api: {
        url: 'https://staging.toshi.co',
        key: options.apiKey
      },
      services: {
        waitAndTry: {
          selectedDefault: true
        },
        inspireMe: true
      },
      analytics: {
        trackingCode: 'GA-ABC123-2'
      }
    });

    return _modal;
  };

  window.wp_toshi_plugin = {
    showModal: function(event, key) {
      event.preventDefault();

      var modal = getModal();

      var modalOverlay = $('#js-toshi-modal-overlay');
      var modalWindow = $('#js-toshi-modal-window');
      var modalClose = $('#js-toshi-close');

      var show = showFactory(modalWindow, modalOverlay);
      var hide = hideFactory(modalWindow, modalOverlay);

      show();

      modalOverlay.click(preventDefaultBefore(hide));
      modalClose.click(preventDefaultBefore(hide));

      modal.mount($('#js-toshi-app').get(0));

      console.log(modalOverlay, modalWindow);
    },
    configure: function(o) {
      options = o;
    }
  };
})(jQuery);
