jQuery.fn.toshiTooltip = function() {
  var _tooltipElement = null;

  function getTooltipElement() {
    if (_tooltipElement) {
      return _tooltipElement;
    }
    return _tooltipElement = jQuery('<div></div>').css({
      position: 'absolute',
      background: 'black',
      color: 'white',

    });
  };


  this
    .mouseenter(function () {
      var thisElement = jQuery(this);
      var elementOffset = jQuery(this).offset();
      getTooltipElement().css('top', elementOffset.top - 15);
      getTooltipElement().css('left', elementOffset.left - (getTooltipElement().width() / 2));
      jQuery(this).after(getTooltipElement());
    })
    .mouseleave(function () {
      getTooltipElement().remove();
    });
};
