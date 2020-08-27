require.config({
  paths: {
      "unbxdsearch": "https://libraries.unbxdapi.com/search-sdk/v0.0.6/vanillaSearch"
  }
}
);
require(["jquery", "handlebars","unbxdsearch"], function ($, handlebars) {
  $(document).ready(function () {
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
  function initSearch() {
    if ("UnbxdSearch" in window) {
      if ("magento_unbxd_listingconfig" in window) {
        window.unbxdSearch = new UnbxdSearch(magento_unbxd_listingconfig);
        bindEvents();
        renderIfMobile();
        if (magento_unbxd_listingconfig.productType != "SEARCH"){
          unbxdSearch.renderFromUrl();
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
    } else {
      if (this.counter > 5) {
        console.error("UnbxdSearch SDK not loaded");
      } else {
        setTimeout(initSearch.bind({ counter: suggest_retry_count + 1 }), 200);
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
    
    if(checkMobile()) {
      window.unbxdSearch.setFacetWidget({
          rangeTemplate:function(ranges,selectedRanges) {
              let ui  = ``;
              const {
                selectedFacetClass,
                facetClass,
                applyMultipleFilters
              } = this.options.facet;
              let selected = false;
              ranges.forEach(range => {
                  const {
                    displayName,
                    facetName,
                    values
                  } = range;
                  let valueUI = ``;
                  selected = (selectedRanges[facetName]) ? true :false;
                  values.forEach(item =>{
                      const {
                        from,
                        to
                      } = item;
                    const isSelected = this.isSelectedRange(facetName,item);
                    const btnCss = (isSelected) ? `UNX-selected-facet-btn ${facetClass} ${selectedFacetClass}`:`${facetClass}`;
                    valueUI +=[`<button class="${btnCss} UNX-range-facet UNX-change-facet" data-action="setRange" data-facet-name="${facetName}" data-start="${from.dataId}" data-end="${to.dataId}" >`,
                        `<span class="UNX-facet-text">${from.name}  -  ${to.name}</span>`,
                        `<span class="UNX-facet-count">(${from.count})</span>`,
                   `</button>`].join('');
                  });
                  ui += [`<div class="UNX-facets-inner-wrapper">`,
                    `<h3 class="UNX-facet-header">${displayName}</h3>`,
                        `<div class="UNX-facets">${valueUI}</div>`,
                  `</div>`].join('');
              });
              let clearBtn = ``;
              let applyBtn = ``;
              if(selected) {
                  if(applyMultipleFilters) {
                      applyBtn = `<button class="UNX-default-btn UNX-primary-btn" data-action="applyRange"> Apply</button>`;
                  }
                  clearBtn = `<button class="UNX-default-btn " data-action="clearRangeFacets"> clear</button>`;
              }
              return [`<div class="UNX-range-wrapper">`,
                ui,
                `<div class="UNX-price-action-row">`,
                    applyBtn,clearBtn,
                `<div>`,
              `</div>`].join('')
          }
        });
        //window.unbxdSearch.bindEvents();
        window.unbxdSearch.reRender();
  
    };
  }
});
