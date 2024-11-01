const DEFAULT_TAG = 'new';
const DEFAULT_LIMIT = 5;

let box_template;
let featured_product_template;
let container;

function initialize_featured_box(containerSelector, color , tag = DEFAULT_TAG, limit = DEFAULT_LIMIT ) {
    let container = $(containerSelector);

    // const $slider = container.find('.featured-box-slider');

    load_featuredbox(container, tag, limit ,color);
}

// Load featured box
function load_featuredbox($container, tag, limit, color) {
    fetch_featured_template($container,color)
        .then(() => load_featured_products(tag, limit, $container))
        .catch(handleError);
}

// Load products
function load_featured_products(tag, limit, $container) {
    makeAjaxRequest(
        "includes/seller/get_products_featured.inc.php",
        "GET",
        { tag, limit },
        (data) => load_featured_products_success_callback(data, $container),
        load_featured_products_error_callback
    );
}

// Success callback for loading products
function load_featured_products_success_callback(data, $container) {
    if (!data.products || data.products.length === 0) {
        handleError("No products found.");
        return;
    }

    box_template.find('.box-title').text(data.title || 'Featured Products');
    box_template.find('.box-under-title').text(data.undertitle || 'Browse our selected items');
    // render_featured_product_card(data.products);
    render_featured_product_card(data.products,$container);
  
}

// Error callback for loading products
function load_featured_products_error_callback(xhr, status, error) {
    handleError("Error fetching featured products: ", status, error);
}

// Render product cards
function render_featured_product_card(products, $container) {
   
        const $slider = $container.find('.featured-box-slider').empty();
        console.log("render product called once");

        products.forEach(product => {
            const $card = create_featured_product_card(product);
            $slider.append($card);
        });
        set_slider($container);
}

// Create product card element
function create_featured_product_card(product) {
    const $card = featured_product_template.clone();
    $card.find(".featured-card-title").text(product.title);
    $card.find(".price-featured").text(product.price + " درهم");

    const images = product.images && product.images.length > 0 ? JSON.parse(product.images) : [product.image];
    $card.find(".featured-img-container img").attr("src", images[0]); // Set main image

    return $card;
}

// Set up slider
function set_slider($container) {
    const slider = $container.find('.featured-box-slider');


    console.log('set Slider call  ');
    slider.slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        pauseOnHover: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 2000,
        prevArrow: '<div class="slider-prev"></div>',
        nextArrow: '<div class="slider-next"></div>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    dots: true
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
        ]
    });
}

// Fetch template
function fetch_featured_template($container,color) {
    return $.get("layouts/featuredbox.layout.html").then(data => {
        box_template = $(data);
        box_template.css("background-color", color);  // Set your desired color
        
        
        $container.empty().append(box_template);
        featured_product_template = box_template.find('.featured-product-template').clone().removeAttr("id");
    }).fail((jqXHR, textStatus, errorThrown) => {
        handleError("Error fetching the featured box template:", textStatus, errorThrown);
    });
}

// Handle errors
function handleError(message, status = '', error = '') {
    console.error(message, status, error);
    container.html("<p>Failed to load data. Please check your connection or try again later.</p>");
}

// Initialize on load
