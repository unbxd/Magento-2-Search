require(["jquery"], function (jQuery) {
  jQuery(document).ready(function () {
    if ("unbxdMagentoConfig" in window) {
      triggerProductViewEvent();
      bindCartAction();
      bindOrderAction();
    }
  });

  function trackEvent(eventName,obj){
    internalTrack.call({
        eventName: eventName,
        data:obj,
        counter:1
    })
  }


  function internalTrack(){
    if ("Unbxd" in window && "track" in Unbxd) {
        console.log("Inside internalTrack");
        Unbxd.track(this.eventName, this.data);
    }else{
        console.log("Into ele loop"+this.counter);
        if (this.counter <10){
            this.counter +=1;
        setTimeout(internalTrack.bind(this),200);
        }else{
            console.error(
                eventName+" event not fired (Analytics sdk not loaded)"
              );
        }
    }
  }

  function triggerProductViewEvent() {
    var analyticsConf = window.unbxdMagentoConfig.analytics;
    if ("productId" in analyticsConf && analyticsConf.productId) {
        trackEvent("product_view", { pid: String(analyticsConf.productId) });
    }
  }

  function bindCartAction() {
    if ("cartBtnSelector" in window.unbxdMagentoConfig.analytics && window.unbxdMagentoConfig.analytics.cartBtnSelector) {
      jQuery(document).on("click", window.unbxdMagentoConfig.analytics.cartBtnSelector, function (e) {
        try {
            console.log("Inside click");
          var $ele = jQuery(this);
          var $form = $ele.closest("form");
          if ($form.length) {
            var skuCode = $form.data("productSku");
            var productId = $form.find('input[name="product"]');
            var $qtyInput = $form.find("input#qty");
            var qty = $qtyInput.length ? $qtyInput.val() : 1;
            trackEvent("addToCart", { pid: String(productId.length ?productId.val(): skuCode), qty: qty.toString(),requestId: null,variantId:null});
          }
        } catch (e) {
          console.log(e);
        }
      });
    }

    //Delete cart action
    if (
      "removeCartItemSelector" in window.unbxdMagentoConfig.analytics &&
      window.unbxdMagentoConfig.analytics.removeCartItemSelector
    ) {
      jQuery(document).on(
        "click",
        window.unbxdMagentoConfig.analytics.removeCartItemSelector,
        function (e) {
          try {
            var $ele = jQuery(this);
            var pid = $ele.data("id");
            if (pid) {
                trackEvent("cartRemoval", { pid: String(pid) });
            }
          } catch (e) {
            console.log(e);
          }
        }
      );
    }
  }

  function bindOrderAction() {
      try {
        if (
          "orderConversionEntities" in window.unbxdMagentoConfig.analytics &&
          window.unbxdMagentoConfig.analytics.orderConversionEntities &&
          window.unbxdMagentoConfig.analytics.orderConversionEntities.length
        ) {
            window.unbxdMagentoConfig.analytics.orderConversionEntities.forEach(function (item) {
                if (item.price > 0){
                  item.pid=String(item.pid);
                  item.qty=String(item.qty);
                trackEvent("order", item);
                }else{
                    console.warn("Item with no price not posted-"+item.pid+"-"+item.qty);
                }
          });
        }
      } catch (E) {
        console.log(e);
      }
  }
});
