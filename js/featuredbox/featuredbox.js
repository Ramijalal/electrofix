const DEFAULT_TAG = 'new';
const DEFAULT_LIMIT = 5;

let box_template;
let featured_product_template;
let container;

// Document ready
//  $(document).ready(() => {
//    initialize_featured_box('#featuredbox_container', DEFAULT_TAG, DEFAULT_LIMIT);
// });

function initialize_featured_box(containerSelector, tag = DEFAULT_TAG, limit = DEFAULT_LIMIT) {
     container = $(containerSelector);
    load_featuredbox(container, tag, limit);
}
// Load featured box
function load_featuredbox($container, tag, limit) {
    
    fetch_featured_template($container)
        .then(() => load_featured_products(tag, limit))
        .catch(handleError);
}

// Load products
function load_featured_products(tag, limit) {
    console.log('limit ' + limit);
    makeAjaxRequest(
        "includes/seller/get_products_featured.inc.php",
        "GET",
        { tag, limit },
        load_featured_products_success_callback,
        load_featured_products_error_callback
    );
}

// Success callback for loading products
function load_featured_products_success_callback(data) {
    console.log('load products success');
    console.log(data.products);
    box_template.find('#title').text(data.title);
    box_template.find('#under-title').text(data.undertitle);
    render_featured_product_card(data.products);
 

}

// Error callback for loading products
function load_featured_products_error_callback(xhr, status, error) {
    handleError("Error fetching featured products: ", status, error);
}

// Render product cards
function render_featured_product_card(products) {
    const $slider =  container.find('.featured-box-slider').empty();
    
    products.forEach(product => {
        const $card = create_featured_product_card(product);
        $slider.append($card);
        //  $('.featured-box-slider').slick('setPosition');

    });
    set_slider();
}

// Create product card element
function create_featured_product_card(product) {
    const $card = featured_product_template.clone();

    $card.find(".featured-card-title").text(product.title);
    $card.find(".price-featured").text(product.price + " درهم");
    const images = product.images.length > 0 ? JSON.parse(product.images) : [product.image];
    $card.find(".featured-img-container img").attr("src", images[0]); // Set main image
    return $card;
}

// Set up slider
function set_slider() {
    //  const $slider = $('.featured-box-slider');
    const $slider = container.find('.featured-box-slider');
    if ($slider.hasClass('slick-initialized')) {
        $slider.slick('unslick');
    }
    $slider.slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        cssEase: 'ease-in-out',
        autoplay: true,
        autoplaySpeed: 10000,
        prevArrow: '<div class="slider-prev"></div>',
        nextArrow: '<div class="slider-next"></div>',
        responsive: [
            {
                breakpoint: 1024, // When the screen is less than 1024px
                settings: {
                    slidesToShow: 4, // Show 3 slides
                    slidesToScroll: 1,
                    dots: true // Show dots
                }
            },
            {
                breakpoint: 768, // When the screen is less than 768px
                settings: {
                    slidesToShow: 2, // Show 2 slides
                    slidesToScroll: 1,

                }
            },

        ]
    });


}

// Fetch template
function fetch_featured_template($container) {
    return $.get("layouts/featuredbox.layout.html").then(data => {
        console.log("Fetched Template:", data);
        box_template = $(data);
        $container.append(box_template);
        featured_product_template = box_template.find('#featured-product-template').clone().removeAttr("id");
    }).fail((jqXHR, textStatus, errorThrown) => {
        handleError("Error fetching the featured box template:", textStatus, errorThrown);
    });
}

// Handle errors
function handleError(message, status = '', error = '') {
    console.error(message, status, error);
    alert("There was an error loading the data. Please try again later.");
    container.html("<p>Failed to load data. Please check your connection or try again later.</p>");
}


$(window).on('load', function () {

    // set_slider();
});