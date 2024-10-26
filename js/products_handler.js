
function render_product_card(products, $template , $container ) {
    products.forEach((product) => {
      let images = JSON.parse(product.images); 
      let $productCard = $template.clone().show();
      $productCard.find('.product-card').attr("data-id", product.id);
      $productCard.find('a[href="#"]').attr("data-product-id", product.id);
      $productCard.find(".card-img-top").attr("src",  images[0]); 
      $productCard.find(".card-title").text(product.title);
      // $productCard.find(".description").text(product.description);
      $productCard.find(".price").text(product.price);
      $productCard.find(".currency").text('درهم');
        console.log('rendered a product card once ') ;
    $container.append($productCard);
    });
  }

  function set_navigation(pages, pagination, currentpage) {
    pagination.empty();
    for (let i = 1; i <= pages; i++) {
      const pageItem = `
          <li class="page-item ${
            i === current_Products_Page ? "active bg-dark" : ""
          }">
              <a class="page-link " href="#" data-page="${i}">${i}</a>
          </li>
      `;
      pagination.append(pageItem);
    }
  }

  
  function open_product_page(event) {
    const clickedElement = event.currentTarget;
    const id = clickedElement.getAttribute("data-id");
    const url = `index.php?page=product&id=${id}`;
    console.log(id);
    window.open(url, '_blank');

  } 