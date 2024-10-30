<?php

require_once '../funcs.inc.php';
require_once '../dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $tag = GET('tag','new');
    $limit =$_GET['limit'];
    
    if (!empty($tag)) {
        try {
          
            $tag_row= DB::queryFirstRow("SELECT * from tags where name = %s " , $tag);

            if(!$tag_row){
                echo json_encode([
                    'success' => 'false',
                    'message' => 'Tag not found in database fetch products by tag failed',
                ]);
                exit();

            }

              $products = DB::query("SELECT p.* 
                                FROM products p
                                JOIN tagged_products tp ON p.id = tp.product_id
                                JOIN tags t ON tp.tag_id = t.id
                                WHERE t.name = %s  
                                LIMIT $limit
                                ", $tag); // Use %d for integer


            // Check if products were found
            if (!$products) {
                echo json_encode([
                    'success' => 'false',
                    'message' => 'No products found for this tag.'
                ],JSON_PRETTY_PRINT);
                exit();
            }


            
            echo json_encode([
                'success' => 'true',
                'products' => $products
           ,'title' => $tag_row['title_ar'], 'undertitle' =>$tag_row['under_title_ar'] , 'limit' => $limit ],JSON_PRETTY_PRINT);



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
