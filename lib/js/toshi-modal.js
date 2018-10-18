(function($, toshi) {
  /**
   * Returns an Observable of the given element's value.
   * @param {*} element
   */
  var createValueStreamForField = function(element) {
    return rxjs.Observable.create(function(resolver) {
      bindToFieldValue(element, function(value) {
        resolver.next(value);
      });
    }).pipe(rxjs.operators.startWith(''));
  };

  /**
   * Returns a method that hides the given element.
   * @param {*} element
   * @param {*} overlay
   */
  var hideFactory = function(element, overlay) {
    return function() {
      element.hide();
      overlay.hide();
    };
  };

  /**
   * Returns a method that hides the given element.
   * @param {*} element
   * @param {*} overlay
   */
  var showFactory = function(element, overlay) {
    return function() {
      element.show();
      overlay.show();
    };
  };

  /**
   * Higher-order function for DOM event handler callbacks
   * which prevents the default action from executing.
   * @param {*} f
   */
  var preventDefaultBefore = function(f) {
    return function(event) {
      event.preventDefault();
      f(event);
    };
  };

  // Calls the callback with the value of the DOM element supplied.
  // It will call initially if the value is present, and listen for further changes.
  var bindToFieldValue = function(el, f) {
    var getValue = function(element) {
      if (element.is('[type=checkbox]')) {
        return element.is(':checked');
      }
      return element.val();
    };

    // On DOM load, return the element's value
    f(getValue(el));

    // On change, return value
    el.on('change', function() {
      f(getValue(el));
    });
  };

  /**
   * Register listeners with input fields to communicate order details with the modal.
   */
  var bindToFields = function() {
    /**
     * Returns an Observable stream that switches between the
     * shipping and billing values depending on whether or
     * not shipToDifferentAddress is checked.
     * @param {*} billingValue$
     * @param {*} shippingValue$
     * @param {*} shipToDifferentAddress$
     */
    var billingShippingSwitch = function(
      billingValue$,
      shippingValue$,
      shipToDifferentAddress$
    ) {
      return rxjs
        .combineLatest(
          billingValue$,
          shippingValue$,
          shipToDifferentAddress$,
          function(billing, shipping, shipToDifferentAddress) {
            return shipToDifferentAddress ? shipping : billing;
          }
        )
        .pipe(rxjs.operators.shareReplay());
    };

    /**
     * Selects a checkout field with the supplied name,
     * and returns a stream of the element's values.
     * @param {*} namePartial
     */
    var getFieldStreamFromName = function(namePartial) {
      return createValueStreamForField(
        $('form.checkout [name=' + namePartial + ']')
      );
    };

    var billingFirstName$ = getFieldStreamFromName('billing_first_name');
    var shippingFirstName$ = getFieldStreamFromName('shipping_first_name');

    var billingLastName$ = getFieldStreamFromName('billing_last_name');
    var shippingLastName$ = getFieldStreamFromName('shipping_last_name');

    var billingEmail$ = getFieldStreamFromName('billing_email');
    var shippingEmail$ = getFieldStreamFromName('shipping_email');

    var billingAddress1$ = getFieldStreamFromName('billing_address_1');
    var shippingAddress1$ = getFieldStreamFromName('shipping_address_1');

    var billingAddress2$ = getFieldStreamFromName('billing_address_2');
    var shippingAddress2$ = getFieldStreamFromName('shipping_address_2');

    var billingCity$ = getFieldStreamFromName('billing_city');
    var shippingCity$ = getFieldStreamFromName('shipping_city');

    var billingPostcode$ = getFieldStreamFromName('billing_postcode');
    var shippingPostcode$ = getFieldStreamFromName('shipping_postcode');

    // Non-switching
    var orderPhone$ = getFieldStreamFromName('billing_phone');

    var shipToDifferentAddress$ = createValueStreamForField(
      $('form.checkout [name=ship_to_different_address]')
    );

    /**
     * Creates a function which logs args passed through
     * to it, prepending the prefix.
     * @param {*} prefix
     */
    var debug = function(prefix) {
      return function(args) {
        console.log(prefix, args);
      };
    };

    var timeslotCreated$ = rxjs.Observable.create(function(resolver) {
      getModal().onShadowOrderCreated(function(order) {
        resolver.next(order);
      });
    });

    var orderFirstName$ = billingShippingSwitch(
      billingFirstName$,
      shippingFirstName$,
      shipToDifferentAddress$
    );
    var orderLastName$ = billingShippingSwitch(
      billingLastName$,
      shippingLastName$,
      shipToDifferentAddress$
    );
    var orderEmail$ = billingShippingSwitch(
      billingEmail$,
      shippingEmail$,
      shipToDifferentAddress$
    );
    var orderAddress1$ = billingShippingSwitch(
      billingAddress1$,
      shippingAddress1$,
      shipToDifferentAddress$
    );
    var orderAddress2$ = billingShippingSwitch(
      billingAddress2$,
      shippingAddress2$,
      shipToDifferentAddress$
    );
    var orderCity$ = billingShippingSwitch(
      billingCity$,
      shippingCity$,
      shipToDifferentAddress$
    );
    var orderPostcode$ = billingShippingSwitch(
      billingPostcode$,
      shippingPostcode$,
      shipToDifferentAddress$
    );
    var orderAddress$ = rxjs.combineLatest(
      orderAddress1$,
      orderAddress2$,
      orderCity$,
      orderPostcode$,
      function(address1, address2, city, postcode) {
        return {
          line1: address1,
          line2: address2,
          town: city,
          postcode: postcode
        };
      }
    );

    orderFirstName$.subscribe(function(value) {
      getModal().setFirstName(value);
    });

    orderLastName$.subscribe(function(value) {
      getModal().setLastName(value);
    });

    orderEmail$.subscribe(function(value) {
      getModal().setEmail(value);
    });

    orderAddress$.subscribe(function(address) {
      getModal().setAddress(address);
    });

    orderPhone$.subscribe(function(phone) {
      getModal().setPhone(phone);
    });

    /*
    name: string
    size?: string
    sku: string
    quantity: number
    unitPrice: number
    imageUrl: string
    description?: string
    category?: string
    salePrice?: number
    totalPrice?: number

    sizeUpSku?: string
    sizeDownSku?: string
    sizeUpSize?: string
    sizeDownSize?: string
    sizeUpBlacklisted?: boolean
    sizeDownBlacklisted?: boolean

    sizeUpSelected?: boolean
    sizeDownSelected?: boolean
    */

    getModal().setStoreOrderReference(options.checkout.basket.quoteNumber);

    getModal().onShadowOrderCreated(function(e) {
      shadowOrderCreated = true;
    });

    var canProceed$ = timeslotCreated$.pipe(
      rxjs.operators.map(function(order) {
        return Boolean(order.id);
      }),
      rxjs.operators.startWith(false)
    );
    window.wp_toshi_plugin_streams = {
      canProceed$: canProceed$.pipe(rxjs.operators.shareReplay(1))
    }

    getModal().setProducts(options.checkout.basket.products);
    getModal().setOrderTotal({
      value: options.checkout.basket.orderTotalPrice,
      currency: options.checkout.basket.orderCurrency
    });

    /**
     * setEmail(email: string)
     setStore(store: number)
      setAddress(address: Address)
      setProducts(products: Product[])
      setPhone(phone: string)
      setStoreOrderReference(storeOrderReference: string)
      setOrderTotal(orderTotal: Price)
      setApiBaseUrl(apiBaseUrl: string)
      */
  };

  $(document).ready(function() {
    var modalOverlay = $('#js-toshi-modal-overlay');
    var modalWindow = $('#js-toshi-modal-window');
    var modalClose = $('#js-toshi-close');
    var modalDismissOverlayInteractor = $('#js-toshi-dismiss-modal');

    showModal = showFactory(modalWindow, modalOverlay);
    hideModal = hideFactory(modalWindow, modalOverlay);

    modalDismissOverlayInteractor.click(preventDefaultBefore(hideModal));
    modalClose.click(preventDefaultBefore(hideModal));

    bindToFields();
  });

  var options = {};
  var shadowOrderCreated = false;
  var _modal;
  var showModal = null;
  var hideModal = null;

  var getModal = function() {
    if (typeof _modal !== 'undefined') {
      return _modal;
    }

    _modal = window.toshi.createToshiModal({
      api: {
        url: options.apiUrl,
        key: options.apiKey
      },
      interfaceProps: {
        showDoneButton: true,
        onCancelButtonClicked: hideModal,
        onDoneButtonClicked: hideModal
      },
      analytics: {
        trackingCode: 'GA-ABC123-2'
      }
    });

    return _modal;
  };

  /**
   * Globally accessible methods.
   */
  window.wp_toshi_plugin = {
    showModal: function(event, key) {
      event.preventDefault();

      var modal = getModal();

      showModal();

      modal.mount($('#js-toshi-app').get(0));
    },
    configure: function(o) {
      options = o;
    },
    isShadowOrderCreated: () => {
      return shadowOrderCreated;
    }
  };

  window;
})(jQuery);
