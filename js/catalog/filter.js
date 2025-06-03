// class ProductFilter {
//     constructor() {
      
//       this.init();
//     }
  
//     // Set up event handlers.
//     init() {
//       // When any checkbox changes, update products.
//       $("#filter-form").on(
//         "change",
//         "input[name='id[]'], input[name='color[]'], input[name='brand[]']",
//         () => {
//           this.updateProducts();
//         }
//       );
  
//       // Handle "Select All" for categories.
//       $("#selectAll").on("change", (event) => {
//         $(".category").prop("checked", $(event.target).prop("checked"));
//         this.updateProducts();
//       });
//     }
  
//     // Build query string from checked filters, update URL, and fetch products via AJAX.
//     updateProducts() {
//       let queryParams = [];
  
//       // Build category filter: join checked category ids with commas.
//       const categories = [];
//       $("input[name='id[]']:checked").each(function () {
//         // console.log(this);
//         categories.push($(this).val());
//       });
//       if (categories.length > 0) {
//         queryParams.push("id=" + categories.join(","));
//       }
  
//       // Process color checkboxes.
//       const colors = [];
//       $("input[name='color[]']:checked").each(function () {
//         // console.log($(this).val());
//         // console.log(this);
//         colors.push($(this).val());
//       });
//       if (colors.length > 0) {
//         queryParams.push("color=" + colors.join(","));
//       }
  
//       // Process brand checkboxes.
//       const brands = [];
//       $("input[name='brand[]']:checked").each(function () {
//         brands.push($(this).val());
//       });
//       if (brands.length > 0) {
//         queryParams.push("brand=" + brands.join(","));
//       }
  
//       // Construct the query string and new URL.

//       const arr1 = (window.location.search.slice(1)).split("&");
//       let arr2 = [];
//       // let arr3 ;
//       arr1.forEach(e => {
//         arr2.push(e.split('='));
//       });
//       arr2.forEach(e=>{
//         if(e[0]=='limit' || e[0]=='page'){
//           queryParams.push(e.join('='));
//         }
//       })
//       // queryParams.push()
//       // console.log(arr2);
//       // let page = arr2[];
//       // let limit = arr2[];
//       // console.log(page);
//       // console.log(limit);
//         // queryParams.push(page.join('='));
//         // queryParams.push(limit.join('='));
        

//       const queryString = queryParams.join("&");
      

//       console.log("12345");
//       console.log(arr1);
//       console.log("12345");

//       const newUrl =
//         window.location.protocol +
//         "//" +
//         window.location.host +
//         window.location.pathname +
//         // window.location.search +
//         (queryString ? "?" + queryString : "");
  
//       // Update the browser's URL without reloading the page.
//       history.pushState(null, "", newUrl);
  
//       // AJAX request to fetch the filtered products.
//       $.ajax({
//         url: newUrl,
//         type: "GET",
//         success: function (response) {
//           const newProductHtml = $(response).find("#product-container").html();
//           $("#product-container").html(newProductHtml);
//         },
//         error: function () {
//           alert("An error occurred while loading products. Please try again.");
//         },
//       });
//     }
//   }
  
//   // Instantiate the class once the document is ready.
//   $(document).ready(() => {
//     new ProductFilter();
//   });
class ProductFilter {
  constructor() {
    this.init();
  }

  // Set up event handlers.
  init() {
    // When any checkbox changes, update products.
    $("#filter-form").on(
      "change",
      "input[name='id[]'], input[name='color[]'], input[name='brand[]']",
      () => {
        this.updateProducts();
      }
    );

    // Handle "Select All" for categories.
    $("#selectAll").on("change", (event) => {
      $(".category").prop("checked", $(event.target).prop("checked"));
      this.updateProducts();
    });
  }

  // Build query string from checked filters, update URL, and fetch products via AJAX.
  updateProducts() {
    let queryParams = [];

    // Build category filter: join checked category ids with commas.
    const categories = [];
    $("input[name='id[]']:checked").each(function () {
      console.log(this);
      categories.push($(this).val());
    });
    if (categories.length > 0) {
      queryParams.push("id=" + categories.join(","));
    }

    // Process color checkboxes.
    const colors = [];
    $("input[name='color[]']:checked").each(function () {
      console.log($(this).val());
      console.log(this);
      colors.push($(this).val());
    });
    if (colors.length > 0) {
      queryParams.push("color=" + colors.join(","));
    }

    // Process brand checkboxes.
    const brands = [];
    $("input[name='brand[]']:checked").each(function () {
      brands.push($(this).val());
    });
    if (brands.length > 0) {
      queryParams.push("brand=" + brands.join(","));
    }

    // Construct the query string and new URL.
    const queryString = queryParams.join("&");
    const newUrl =
      window.location.protocol +
      "//" +
      window.location.host +
      window.location.pathname +
      (queryString ? "/?" + queryString : "");

    // Update the browser's URL without reloading the page.
    history.pushState(null, "", newUrl);

    // AJAX request to fetch the filtered products.
    $.ajax({
      url: newUrl,
      type: "GET",
      success: function (response) {
        const newProductHtml = $(response).find("#product-container").html();
        $("#product-container").html(newProductHtml);
      },
      error: function () {
        alert("An error occurred while loading products. Please try again.");
      },
    });
  }
}

// Instantiate the class once the document is ready.
$(document).ready(() => {
  new ProductFilter();
});
