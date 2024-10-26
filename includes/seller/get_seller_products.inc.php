<?php

require_once '../funcs.inc.php';
require_once '../dbh.inc.php';
//try_session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $filter_defaults = [
        'order_by' => 'new',
        'searchQuery' => null,
        'page' => 1,
        'items_per_page' => 10,
        'user' => '0',
        'cat' =>null
    ];


    $filter = getFilterParams($filter_defaults);

    $user = $filter_defaults['user'] == '0' ? null : fetch_user_info();
    //  $user_id = $user['id'];
    $user_id = $filter_defaults['user'] == '0' ? null : $user['id'];

    $product_id = POST('product_id', 'no');
    if ($product_id == null) {
        echo json_encode(GET_PRODUCTS($user_id, $filter));
        exit();
    }

    GET_SINGLE_PRODUCT($user_id, $product_id);






}


function getFilterParams($defaults = [])
{
    $params = [];

    foreach ($defaults as $key => $default) {
        $params[$key] = isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    return $params;
}




function GET_PRODUCTS($user_id, $filter)
{
    $params = [];
    $sql = "SELECT * FROM products where 1=1 ";
    $count_sql = "SELECT COUNT(*) AS total from products where 1=1 ";

    if ($user_id != null) {
        $sql .= " AND owner_id = %s ";
        $count_sql .= " AND owner_id = %s ";

        $params[] = $user_id;
    }
    if ( $filter['cat'] != null) {
        $sql .= " AND category_id = %s ";
        $count_sql .= " AND owner_id = %s ";

        $params[] = $filter['cat'];
    }

    $orderClause = 'ORDER BY created_at ASC'; // Default to ordering by old (ASC)
    if (isset($filter['order_by']) && $filter['order_by'] === 'new') {
        $orderClause = 'ORDER BY created_at DESC';
    }

    if (isset($filter['searchQuery']) && $filter['searchQuery'] != '') {
        $sql .= " AND (products.title LIKE %s OR products.description LIKE %s)";
        $count_sql .= " AND (products.title LIKE %s OR products.description LIKE %s)";
        $params[] = "%" . $filter['searchQuery'] . "%";
        $params[] = "%" . $filter['searchQuery'] . "%";
    }

    // call function process_products 
    $page = isset($filter['page']) ? (int) $filter['page'] : 1;
    $page = $page <= 0 ? 1 : $page;
    $items_per_page = isset($filter['items_per_page']) ? (int) $filter['items_per_page'] : 4;
    $offset = ($page - 1) * $items_per_page;


    $sql .= " " . $orderClause;
    $sql .= " LIMIT " . $items_per_page . " OFFSET " . $offset;

    try {
        $user_products = DB::query($sql, ...$params);
        if (!isset($user_products)) {
            return ['success' => false, 'error' => 'no products found'];
        }

        // Get total number of items for pagination

        $total_items = DB::queryFirstField($count_sql, ...$params);
        $pages = ceil($total_items / $items_per_page);
        return [
            'success' => true,
            'message' => $user_products,
            'total_items' => $total_items
            ,
            'pages' => $pages
            ,
            'filters' => $filter
        ];
        // return ['success' => true, 'message' => $order_items ];
    } catch (Exception $th) {
        return ['success' => false, 'err or ssss' => $th->getMessage()];
    }
}


function GET_SINGLE_PRODUCT($user_id, $product_id)
{

    $user_condition = $user_id == null? " "  : " AND owner_id = %s ";
    try {
        $query = 'SELECT * FROM products WHERE  id = %s ' . $user_condition;
        
        $product = DB::queryFirstRow('SELECT * FROM products WHERE  id = %s ' , $product_id,);
        if (!$product) {
            echo json_encode(['success' => false, 'error' => 'Product not found or you are not authorized', 
            'message' => "query "  . $query . 'product id ' . $product_id  ]);
            exit();
        }

        echo json_encode(['success' => true, 'message' => $product]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }

}