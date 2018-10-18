<?php

function toshi_order_uses_toshi($order_id) {
  $order = new WC_Order($order_id);
  foreach ($order->get_items('shipping') as $item_id => $shipping) {
    if ($shipping['method_id'] == 'toshi') {
      return true;
    }
  }
  return false;
}

add_action('woocommerce_thankyou', function($order_id) {
  $url = toshi_api_url('api/v1/webhooks/order/confirm');

  if (! toshi_order_uses_toshi($order_id)) {
    return;
  }

  $request = curl_init($url);

  $data = array(
    'store_order_reference' => toshi_get_quote_reference(),
    'key' => toshi_api_key(),
    'order' => array(
      'store_order_reference' => $order_id
    )
  );

  $json = json_encode($data);

  curl_setopt($request, CURLOPT_POST, 1);
  curl_setopt($request, CURLOPT_RETURNTRANSFER,1);
  curl_setopt($request, CURLOPT_POSTFIELDS, $json);
  curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

  $response = curl_exec($request);
});
