<?php

add_action('woocommerce_before_checkout_form', function () {
    ?>
    <style type="text/css">
      .toshi__woo-modal-overlay, .toshi__woo-modal {
          display: none;
      }

      .toshi__woo-modal {
          background: white;
          position: relative;
          width: 60%;
          left: 20%;
          top: calc(50% - 40vh);
          z-index: 1000;
      }

      @media all and (max-width: 720px) {
          .toshi__woo-modal {
              width: 90%;
              left: 5%;
          }
      }

      .toshi__woo-modal .toshi__woo-modal__close {
          background: url(<?php echo plugins_url('./images/close.png', __FILE__) ?>);
          background-size: contain;
          -moz-background-size: contain;
          -o-background-size: contain;
          -webkit-background-size: contain;
          width: 40px;
          height: 40px;
          position: absolute;
          top: -20px;
          left: -20px;
          z-index: 1100;
      }

      .toshi__woo-modal > div {
          position: relative;
      }

      .toshi__woo-modal-overlay {
          position: fixed;
          top: 0;
          left: 0;
          background: rgba(0, 0, 0, .8);
          width: 100vw;
          height: 100%;
          z-index: 999;
          overflow-y: auto;
          box-sizing: border-box;
          padding-bottom: 90px;
          padding-top: 60px;
      }

      .toshi__woo-modal-overlay__dismiss {
          width: 100vw;
          height: 100%;
          position: absolute;
          top: 0;
          left: 0;
          background: clear;
      }
  </style>
  <div id="js-toshi-modal-overlay" class="toshi__woo-modal-overlay">
      <div id="js-toshi-dismiss-modal" class="toshi__woo-modal-overlay__dismiss"></div>
      <div id="js-toshi-modal-window" class="toshi__woo-modal">
          <a href="#" id="js-toshi-close" class="toshi__woo-modal__close"></a>
          <div id="js-toshi-app"></div>
      </div>
  </div>
  <script type="text/javascript">
      window.wp_toshi_plugin.configure({
          apiKey: '<?php echo toshi_api_key(); ?>',
          apiUrl: '<?php echo toshi_api_url() ?? ''; ?>',
          checkout: <?php echo json_encode(toshi_get_checkout_data()); ?>
      });
  </script>
  <?php
});