<?php

require_once '../funcs.inc.php';
require_once '../dbh.inc.php';
//require_once '../auth.inc.php';
//try_session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // checking authorization 

    /*
    if(!$loggedin ||  $user_role !== 'seller'){
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit();
    }
    */

    try {

        $productData = [
            'title' => POST('title', ''),
            'description' => POST('description', ''),
            'price' => POST('price', ''),
            'owner_id' => '1',
            'id' => POST('id', ''),
            'category_id' => POST('category', '')
            
        ];
        $image_paths = [];

        if (is_empty($productData['title'], $productData['price'], $productData['description'])) {
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            exit();
        }

        // Handling multiple image files
        if (isset($_FILES['productImages']) && !empty($_FILES['productImages']['name'][0])) {
            $files = $_FILES['productImages'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $image = [
                        'name' => $files['name'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'size' => $files['size'][$i],
                        'error' => $files['error'][$i]
                    ];
                    // Get image path and add it to the array
                    $image_paths[] = get_image_path($image);
                }
            }
        }




        // If updating the product
        if (isset($productData['id']) && $productData['id'] !== "") {
            $existingProduct = DB::queryFirstRow(
                "SELECT * FROM products WHERE id = %s AND owner_id=%s"
                ,
                $productData['id'],
                $productData['owner_id']
            );

            // Update product image if a new image was uploaded
            if (empty($image_paths)) {
                $productData['images'] = $existingProduct['images'];
            } else {
                $productData['images'] = json_encode($image_paths); // Convert image paths to JSON
            }

            update_product($productData);

        } else {
            // Handle new product addition with images
            if (!empty($image_paths)) {
                $productData['images'] = json_encode($image_paths); // Convert image paths to JSON
                $newProductId = create_product($productData);
                // Insert into tagged_products after creating the product
                if (isset($_POST['tags']) && $_POST['tags'] !== '') {
                    $tags = $_POST['tags'];
                    foreach ($tags as $tag) {
                        echo json_encode(['tags' => $tag , 'product id ' =>$newProductId]); //
                        $tag = trim($tag);
                        $tagId = DB::queryFirstField("SELECT id FROM tags WHERE name = %s", $tag);

                        if ($tagId) {
                            DB::insert('tagged_products', [
                                'product_id' => $newProductId, // Use the new product ID
                                'tag_id' => $tagId
                            ]);
                        }
                       
                    }
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'No images provided']);
                exit();
            }

        }


    } catch (Exception $th) {
        echo json_encode(['success' => false, 'error' => $th->getMessage()]);
    }


}


function create_product($productData)
{
    DB::insert('products', $productData);
    $product_id = DB::insertId();

    if (!$product_id) {
        echo json_encode(['success' => false, 'error' => 'Failed to create the product.']);
        exit();
    }

    //echo json_encode(['success' => true, 'message' => 'Product created successfully.']);
    return $product_id;
}



function update_product($productData)
{

    $result = DB::update('products', [
        'title' => $productData['title'],
        'description' => $productData['description'],
        'price' => $productData['price'],
        'images' => $productData['images'],
        'category_id' => $productData['category_id']
    ], "id = %s AND owner_id = %s", $productData['id'], $productData['owner_id']);



    if ($result === false) {
        // Database error occurred
        echo json_encode(['success' => false, 'error' => 'Failed to update the product due to a database error.']);
        exit();

    } elseif ($result === 0) {
        // No rows affected (likely because the data was identical)
        echo json_encode(['success' => true, 'info' => 'No changes made as the data is identical to existing records.']);
        exit();

        // Update was successful and changes were made
    }
    echo json_encode(['success' => true, 'message' => 'Product updated successfully.']);
    exit();


}


function get_image_path($image)
{


    // Define the target directory and file path
    $target_dir = "../../uploads/";
    $file_name = basename($image["name"]);
    $file_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name); // Sanitize file name
    $target_file = $target_dir . $file_name;
    $image_path = "uploads/" . $file_name;

    // Validate file type (example for image files)
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_types)) {
        echo json_encode(['success' => false, 'error' => 'Invalid file type.']);
        exit();
    }

    // Validate file size (example for max 5MB)
    if ($image["size"] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'error' => 'File size exceeds the maximum limit of 5MB.']);
        exit();
    }

    // Ensure the target directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Move the uploaded file
    if (!move_uploaded_file($image["tmp_name"], $target_file)) {
        echo json_encode(['success' => false, 'error' => 'Failed to upload image.']);
        exit();
    }
    return $image_path;

}