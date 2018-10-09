(function($) {
  $(function() {
    var displayMessage = function(status, message) {
      $('#js-toshi-message').remove();
      $(
        '<div class="' +
          status +
          '" id="js-toshi-message"><p>' +
          message +
          '</p></div>'
      ).insertBefore('#mainform .form-table');
    };

    $('#js-check-api-key').click(function() {
      var button = $(this);
      var apiKeyField = $('input[name=woocommerce_toshi_api_key]');
      var apiUrlField = $('input[name=woocommerce_toshi_api_url]');

      var request = $.get(
        apiUrlField.val() + '/api/v2/modals/' + apiKeyField.val()
      );

      button.attr('disabled', true);

      request.fail(function(error) {
        displayMessage(
          'error',
          'Check failed. Please check your details and try again.'
        );
        button.attr('disabled', false);
      });

      request.done(function(result) {
        displayMessage('created', 'Connected to TOSHI');
        button.attr('disabled', false);
      });
    });
  });
})(jQuery);
