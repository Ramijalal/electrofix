<?php



$htmlcontent = file_get_contents('pages/shop.html');

// Define accessible pages for each role


$universal_pages = ['shop', 'product', 'cart', 'contact', 'categories'];


$page = $_GET['page'] ?? 'shop';


$htmlcontent = getPageContent($page);

function getPageContent($page)
{
    global $universal_pages;

    if (in_array($page, $universal_pages)) {
        return file_get_contents(filename: "pages/$page.html");
    } else if ($page = 'dashboard') {
        return file_get_contents(filename: "pages/seller/dashboard.html");
    }
    return 'Error 404 Page Not Found!!!' . $page;
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />

    <!-- multi select library for the dashboard tag selection  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <!-- sweet alert library  href -->
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <!-- featured box stylesheet  -->
    <link rel="stylesheet" href="css/fearured_box/featured_box.css">

    <!-- carousel library href -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <title>Document</title>


</head>

<body>


    <?php include 'layouts/header.php'; ?>
    <?php echo $htmlcontent; ?>







    <script src="js/funcs.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- multi select library for the dashboard tag selection -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


<script>

</script>

</body>

</html>