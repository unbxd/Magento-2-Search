require(["jquery", "handlebars"], function ($, handlebars) {
  $(document).ready(function () {
    if ("magento_unbxd_asconfig" in window) {
      initAutoSuggest();
      bindEvents();
    } else {
      console.error("Unbxd Autosuggest config not found");
    }
  });
  function bindEvents() {
    require(["jquery"], function ($) {
      $('label[data-role="minisearch-label"]').click(function (e) {
        var focusSearch = true;
        $target = $(e.target);
        $target.toggleClass("button-clicked");
        if ($target.hasClass("active")) {
          focusSearch = false;
        }
        $target.toggleClass("active");
        $("form.unbxd-search-form-js").toggleClass("active");
        if (focusSearch) {
          $("form.unbxd-search-form-js #search").focus();
        }
      });
      $("form.unbxd-search-form-js #search").focusout(function (e) {
         setTimeout(function () {
            if (!$('label[data-role="minisearch-label"]').hasClass("button-clicked")) {
            $("form.unbxd-search-form-js").removeClass("active");
            $('label[data-role="minisearch-label"]').removeClass(
              "active button-clicked"
            );
            $(".unbxd-as-wrapper").hide();
            }
          }, 1000);
      });
    });
  }
  var suggest_retry_count = 0;
  function initAutoSuggest() {
    if ("unbxdAutoSuggestFunction" in window) {
      unbxdAutoSuggestFunction(jQuery, Handlebars);
      if (
        "unbxdMagentoConfig" in window &&
        unbxdMagentoConfig.autoSuggest.customTemplate
      ) {
        $(unbxdMagentoConfig.autoSuggest.searchInputSelector).unbxdautocomplete(
          magento_unbxd_asconfig
        );
      } else {
        jQuery(".unbxd-search-form-js input#search").unbxdautocomplete(
          magento_unbxd_asconfig
        );
      }
    } else {
      if (this.counter > 5) {
        console.error("UnbxdAutosuggest SDK not loaded");
      } else {
        setTimeout(
          initAutoSuggest.bind({ counter: suggest_retry_count + 1 }),
          200
        );
      }
    }
  }
});
