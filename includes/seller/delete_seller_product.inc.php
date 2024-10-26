<?php

require_once '../dbh.inc.php';
require_once '../funcs.inc.php';
//require_once '../auth.inc.php';
//try_session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // if (!$loggedin) {
    //     echo json_encode(['success' => false , 'error' =>'you are not authorized for this action']);
    //     exit();
    // }

    $productid = POST('id','no');

    if($productid == null){
        echo json_encode(['success' => false, 'error' => 'no product ID in the request']);
        exit();
    }

    try {
        // $user = fetch_user_info();
        // $userid = $user['id'];
        $delete =  DB::delete('products', 'id = %s', $productid );
    
        if(!$delete){
            echo json_encode(['success' => false, 'error' => 'product not found or you are not authorized']);
            exit();
        }
        echo json_encode(['success' => true, 'message' => 'Product successfully deleted']);
        exit();
        
    } catch (Exception $th) {
        echo json_encode(['success' => false, 'message' => $th->getMessage()]);
    }


}