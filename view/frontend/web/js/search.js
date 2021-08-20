require(["jquery", "handlebars","UnbxdSearch"], function ($, handlebars,UnbxdSearch) {
  $(document).ready(function () {
    extendSearch();
    initSearch();
  });
  var suggest_retry_count = 0;
  function toggleMobileFacets(e){
    const facetBlock = document.querySelector(".UNX-fxd-facet");
    showFacet = !showFacet;
    const {
        action
    } = e.target.dataset;
    if(action === "applyFacets") {
        window.unbxdSearch.setPageStart(0);
        window.unbxdSearch.getResults();
    }
    if(action === "clearFacets") {
        window.unbxdSearch.clearAllFacets();
        window.unbxdSearch.setPageStart(0);
        window.unbxdSearch.getResults();
    }
    if(showFacet) {
        facetBlock.classList.add("UNX-show-facets")
    } else {
        facetBlock.classList.remove("UNX-show-facets")
    }
}

  function extendSearch(){
    if ("unbxdExtend" in window && typeof window.unbxdExtend === 'function'){
      window.unbxdExtend.call();
    }
  }
  function initSearch() {
      if ("magento_unbxd_listingconfig" in window) {
        window.unbxdSearch = new UnbxdSearch(magento_unbxd_listingconfig);
        bindEvents();
        //renderIfMobile();
        if (magento_unbxd_listingconfig.products && magento_unbxd_listingconfig.products.productType != "SEARCH"){
          unbxdSearch.getCategoryPage();
        }
      } else {
        if (this.counter > 5) {
          console.error("Unbxd listing config not found");
        } else {
          setTimeout(
            initSearch.bind({ counter: suggest_retry_count + 1 }),
            200
          );
        }
      }
  }

  

  let showFacet = false;
  

  function bindEvents(){
    const btnEls = document.querySelectorAll(".UNX-facet-trigger");
                    btnEls.forEach(item=> {
                    item.addEventListener("click", toggleMobileFacets)
                    });
    
  }

  function renderIfMobile(){
    

        //window.unbxdSearch.bindEvents();
        window.unbxdSearch.reRender();
  }
});

