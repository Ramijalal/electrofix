<?php

require_once '../funcs.inc.php';
require_once '../dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $tag = isset($_GET['tag']) ? $_GET['tag'] : '';


    if (!empty($tag)) {
        try {

            $products = DB::query("SELECT p.* 
                                    FROM products p
                                    JOIN tagged_products tp ON p.id = tp.product_id
                                    JOIN tags t ON tp.tag_id = t.id
                                    WHERE t.name = %s", $tag);

            // Check if products were found
            if (!$products) {
                echo json_encode([
                    'success' => 'false',
                    'message' => 'No products found for this tag.',
                ]);
                exit();
            }
            echo json_encode([
                'success' => 'true',
                'data' => $products,
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => 'false',
                'message' => $e->getMessage(),
            ]);
            exit();
        }
    } else {
        echo json_encode([
            'success' => 'false',
            'message' => 'Tag parameter is required.',
        ]);
        exit();
    }
} else {
    echo json_encode([
        'success' => 'error',
        'message' => 'Invalid request method. Please use GET.',
    ]);
    exit();

}
