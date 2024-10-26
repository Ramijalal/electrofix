<?php

require_once '../dbh.inc.php';
require_once '../funcs.inc.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);

    $customerName = htmlspecialchars(trim($_POST['customerName']), ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars(trim($_POST['address']), ENT_QUOTES, 'UTF-8');
    $productId = filter_var(trim($_POST['productId']), FILTER_VALIDATE_INT);

    if (empty($customerName) || empty($address) || empty($productId)) {
        echo json_encode(['success' => false, 'message' => 'من فضلك املأ جميع المعلومات المطلوبة.']);
        exit;
    }

    $phoneNumber = trim($_POST['phoneNumber']);
    if (!preg_match('/^[0-9]{10,15}$/', $phoneNumber)) {
        echo json_encode(['success' => false, 'message' => 'رقم الهاتف غير صحيح.']);
        exit;
    }



    try {
        // Insert order into the database
        DB::insert('orders', [
            'product_id' => $productId,
            'customer_name' => $customerName,
            'address' => $address,
            'phone_number' => $phoneNumber,
            'order_date' => date('Y-m-d H:i:s'),
            'latest_update' => date('Y-m-d H:i:s'),
            'status' => 'Pending' // Default status
        ]);
    
        echo json_encode(['success' => true, 'message' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'failure ' . $e->getMessage()]);
    }
}
