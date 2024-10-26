<?php
require_once 'dbh.inc.php';

function check_var(...$var)
{
    foreach ($var as $v) {
        if (!isset($v) || empty($v)) {
            return false;
        }
    }
    return true;
}

function is_empty(...$var){
    foreach ($var as $v) {
        if (empty($v)) {
            return true;
        }
    }
    return false ;
}
function fetch_user_info($id = null)
{
    try {
        if (isset($id)) {
            $user = DB::queryFirstRow("SELECT * FROM users WHERE id = %s", $id);
            if ($user) {
                throw new Exception('User not found');
            }
            return $user;
        }
        $hash = $_SESSION['session_hash'];

        $session = DB::queryFirstRow("SELECT * FROM sessions WHERE session_hash = %s ", $hash);
        if (!$session) {
            throw new Exception('Session not found');
        }
        $id = $session['user_id'];
        $user = DB::queryFirstRow("SELECT * FROM users WHERE id = %s", $id);

        if (!$user) {
            throw new Exception('User not found');
        }
        return $user;

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }

}

function check_logged_in(){
    if(!isset($_SESSION['session_hash']) || empty($_SESSION['session_hash'])){
       return false;
    }
    return true ; 
}

function user_is_seller(){
    if(!check_logged_in()){
        return false;
    }
    $user = fetch_user_info();
    if($user['role']!= 'seller'){
        return false;
    }
    return true;
}
function user_is_admin(){

    return true;
}

function get_user_role(){
    if(!check_logged_in()){
        return null;
    }

    $user = fetch_user_info();
    
    return $user['role'];
}



function try_session_start() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

//----------------------------------------------------------------- GET Functions

function GET($var, $type, $default = null) {

    if (!isset($_GET[$var])) {
        return $default;
    }

    $varValue = $_GET[$var];
    return validate($varValue, $type, $default);
}
function POST($var, $type, $default = null) {

    
    if (!isset($_POST[$var])) {
        return $default;
    }

    $varValue = $_POST[$var];
    return validate($varValue, $type, $default);
}
function validate($var, $type, $default) {
    switch ($type) {
        case 'email':
            return filter_var($var, FILTER_VALIDATE_EMAIL) ? $var : $default;

        case 'password':
            return password_hash($var, PASSWORD_DEFAULT);

        case 'date':
            $date = DateTime::createFromFormat('Y-m-d', $var);
            return $date && $date->format('Y-m-d') === $var ? $var : $default;

        case 'phone':
            return preg_match('/^[0-9]{10,15}$/', $var) ? $var : $default;

        default:
            return $var;
    }
}
//-------------------------------------------------------------------------




function fetch_product_info($id){
    if(!isset($id)){
        //echo json encoded success false and message  product id not set 
        echo json_encode(['success' => false, 'message' =>'product id not set']);
        return null;
    }
    try {
        $product = DB::queryFirstRow('SELECT * FROM products  WHERE id = %s ', $id);
        if(!$product){
            echo json_encode(['success' => 'false','message' => 'product not found in database fetch_product_info() failed']);
            return null;
        }
        return $product;

    } catch (Exception $e) {        
        echo json_encode(['success'=> 'false','message'=> $e->getMessage()]);
    }

    return null;
}
