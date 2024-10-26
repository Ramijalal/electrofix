let filterproducts = {};
let current_Products_Page = 1;
let productsPerPage = 4;
let search_string = "";
let products_order_by = "new";
let product_template = $("#product-template").clone().removeAttr("id");
let activeTab = "products";

$(document).ready(function () {
  
  load_products();

  // toggle sidebar on click of hamburger icon
  $(document).on("click", ".toggle-btn", function () {
    $("#sidebar").toggleClass("expand");
  });

  //handle side panel navigation on click
  $(document).on("click", "#tab", function (e) {
    e.preventDefault();
    panel_navigation_controll($(this));
  });

  //add product button onclick
  $(".add-button").on("click", function () {

    var form = document.getElementById("addProductForm");
    form.querySelector("#formMode").value = null;
    form.reset();
    var addProductModal = new bootstrap.Modal(
      document.getElementById("addProductModal"),
      {}
    );
    modal_clearImagePreviews();

    addProductModal.show();
  });

  $("input[name='order_filter']").change(function () {
    products_order_by = $(this).val() == "asc" ? "new" : "old";
    load_products();
  });

  $("#searchQuery").on("input", function () {
    console.log(activeTab);
    search_string = $(this).val();
    if (activeTab === "orders") {
      //get_order_items(ordersFilter);
    } else if (activeTab === "products") {
      load_products();
    }
  });

  $("#pagination").on("click", ".page-link", function (event) {
    event.preventDefault();
    const page = $(this).data("page");

    console.log(page);
    if (page !== current_Products_Page) {
      current_Products_Page = page;
      filterproducts.page = current_Products_Page;
      load_products(filterproducts);
    }
  });

  $(document).on("click", ".delete_btn", function (event) {
    var productId = $(this).data("id");
    console.log("delete_btn clicked id = " + productId);
    delete_button_clicked(productId);
  });



 


});
function delete_button_clicked(productId) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#228B22",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it !",
    cancelButtonText: "Nop",
  }).then((result) => {
    if (result.isConfirmed) {
      // Proceed with the deletion
      delete_product(productId);
    }
  });
}
function delete_product(productId) {
  makeAjaxRequest(
    "includes/seller/delete_seller_product.inc.php",
    "POST",
    { id: productId },
    delete_product_success_callback
  );
}
function delete_product_success_callback(data) {
  console.log(data);
  load_products();
}

function productFormSubmit(event) {
  event.preventDefault();
  var form = document.getElementById("addProductForm");
  var formData = new FormData(form); // Access the first element

  var productId = formData.get("id"); // Use 'id' to match the name attribute

  current_Products_Page = 1;
  makeAjaxRequest(
    "includes/seller/add_seller_product.inc.php",
    "POST",
    formData,
    add_product_success_callback,
    function (xhr, status, error) {
      console.error("Error occurred:", status, error); // Log error to console
    }
  );
}

function add_product_success_callback(data) {
  console.log(data);
  load_products();
  var myModal = bootstrap.Modal.getInstance(
    document.getElementById("addProductModal")
  );
  var form = document.querySelector("#addProductForm");
  if (myModal) {


    myModal.hide();
    form.reset();
  }
}

function load_products(filter) {
  console.log('load func called once ');
  console.log(get_product_filter());
  makeAjaxRequest(
    "includes/seller/get_seller_products.inc.php",
    "POST",
    get_product_filter(),
    load_products_success_callback,
    true
  );
}
function get_product_filter() {
  let filter = {};
  filter.searchQuery = search_string;
  filter.page = current_Products_Page;
  filter.items_per_page = productsPerPage;
  filter.order_by = products_order_by;
  filter.user = '0';
  console.log("page is equal to " + filter.page);

  return filter;
}
function load_products_success_callback(data) {
  console.log(data.message);
  var $template = product_template.show();

  $("#product-list").empty();
  render_product_card(data.message, $template,);
  let navigation = $("#products-container").find("#pagination");
  set_navigation(data.pages, navigation, current_Products_Page);
}

//product content handeling section ---------------------

function set_navigation(pages, pagination, currentpage) {
  pagination.empty();
  for (let i = 1; i <= pages; i++) {
    const pageItem = `
            <li class="page-item ${i === current_Products_Page ? "active bg-dark" : ""
      }">
                <a class="page-link " href="#" data-page="${i}">${i}</a>
            </li>
        `;
    pagination.append(pageItem);
  }
}
function render_product_card(products, $template) {
  products.forEach((product) => {
    let images = JSON.parse(product.images);
    let $productCard = $template.clone();

    $productCard.attr("data-product-id", product.id);
    $productCard.find(".delete_btn").attr("data-id", product.id);
    $productCard.find('a[href="#"]').attr("data-product-id", product.id);

    // Check if product.images is an array and get the first image

    $productCard.find(".card-img-top").attr("src", images[0]);
    $productCard.find(".card-title").text(product.title);
    $productCard.find(".description").text(product.description);
    $productCard.find(".price").text("$" + product.price);

    $("#product-list").append($productCard);
  });
}


function edit_product(event) {
  event.preventDefault();
  var productId = $(event.currentTarget).data("product-id");
  makeAjaxRequest(
    "includes/seller/get_seller_products.inc.php",
    "POST",
    { product_id: productId },
    get_product_success_callback,
    true
  );
}

function get_product_success_callback(data) {
  console.log(data.message);
  populate_edit_product_form(data.message);
}

// function populate_edit_product_form(product) {
//   var addProductModal = new bootstrap.Modal(
//     document.getElementById("addProductModal"),
//     {}
//   );
//   var form = document.getElementById("addProductForm");
//   form.querySelector("#image").required = false;
//   form.querySelector("#formMode").value = product.id;
//   form.querySelector("#title").value = product.title || "";
//   form.querySelector("#price").value = product.price || "";
//   form.querySelector("#description").value = product.description || "";

//   addProductModal.show();
// }

function populate_edit_product_form(product) {
  var addProductModal = new bootstrap.Modal(
    document.getElementById("addProductModal"),
    {}
  );
  var form = document.getElementById("addProductForm");

  // Populate text inputs
  form.querySelector("#formMode").value = product.id;
  form.querySelector("#title").value = product.title || "";
  form.querySelector("#price").value = product.price || "";
  form.querySelector("#description").value = product.description || "";

  // Clear any existing image previews
  modal_clearImagePreviews();

  // Handle existing product images (assuming product.images is an array of image URLs)
  if (product.images && product.images.length > 0) {
    let images = JSON.parse(product.images);
    images.forEach((imageUrl) => {
      const template = document
        .getElementById("imageTemplate")
        .cloneNode(true);
      template.classList.remove("d-none"); // Make the template visible
      const img = template.querySelector("img");
      img.src = imageUrl; // Set image source to the URL of the existing image

      // Attach remove functionality (if needed)
      const removeBtn = template.querySelector(".remove-btn");
      removeBtn.onclick = function () {
        document.getElementById("imagePreview").removeChild(template);
        // Optional: Remove image URL from product.images array or track removed images
      };

      document.getElementById("imagePreview").appendChild(template);
    });
  }

  // Show the modal
  addProductModal.show();
}
