<?php 
header('Content-Type: text/json; charset=UTF-8');
include('function.php');
include('sessions.php');
session_start();
//Explaination of session in README.txt document
if(!isset($_SESSION['sessionControl'])) {
    $_SESSION['sessionControl'] = new sessionControl;
}
$sessionControl = new sessionControl;
$crudObj = new crudControl;
$crudObj->logRequest();
$sessionControl->referrexists();
$sessionControl->domainlock();
$sessionControl->rateLimitChecker();
$sessionControl->Rate24HourCheck();

if(isset($_GET['page'])) {
    switch($_GET['page']) {
        case 'getAllposts':
            $crudObj->getAllposts();
        break;
        case 'getAllOrders':
            $crudObj->getAllOrders();
        break;
        case 'getAlldiscounts':
            $crudObj->getAlldiscounts();
        break;
        case 'getUserdetails':
            $crudObj->getUserdetails();
        break;
        default:
            $reply = Array("Opps, something went wrong. 'Page=' not defined.");
        break;
    }
} else {
    $reply = Array("Opps, something went wrong. 'Page=' not set.");
}

if(isset($_POST['request'])) {
    if($_POST['request'] == 'createUser') {
        if (!empty([$_POST])) {
            $firstname = !empty($_POST['firstname'])? $crudObj->inputFilter(($_POST['firstname'])): null;
            $lastname = !empty($_POST['lastname'])? $crudObj->inputFilter(($_POST['lastname'])): null;
            $email = !empty($_POST['email'])? $crudObj->inputFilter(($_POST['email'])): null;
            $mypassword = !empty($_POST['password'])? $crudObj->inputFilter(($_POST['password'])): null;
            $password = password_hash($mypassword, PASSWORD_DEFAULT);
            $crudObj->createUser($firstname, $lastname, $email, $password);
        } else {
            $reply = Array("Opps, something went wrong. 'request=createUser' did not run.");
        }
    }

    if($_POST['request'] == 'createOrder') {
        if (!empty([$_POST])) {
            $order_name = !empty($_POST['order_name'])? $crudObj->inputFilter(($_POST['order_name'])): null;
            $discount_id = !empty($_POST['discount_id'])? $crudObj->inputFilter(($_POST['discount_id'])): null;
            $user_id = !empty($_POST['user_id'])? $crudObj->inputFilter(($_POST['user_id'])): null;
            $crudObj->createOrder($order_name, $discount_id, $user_id);
        } else {
            $reply = Array("Opps, something went wrong. 'request=createOrder' did not run.");
        }
    }

    if($_POST['request'] == 'createPost') {
        if (!empty([$_POST])) {
            $order_id = !empty($_POST['order_id'])? $crudObj->inputFilter(($_POST['order_id'])): null;
            $user_id = !empty($_POST['user_id'])? $crudObj->inputFilter(($_POST['user_id'])): null;
            $crudObj->createPost($order_id, $user_id);
        } else {
            $reply = Array("Opps, something went wrong. 'request=createPost' did not run.");
        }
    } 

    if($_POST['request'] == 'updateUserDetails') {
        if (!empty([$_POST])) {
            $firstname = !empty($_POST['firstname'])? $crudObj->inputFilter(($_POST['firstname'])): null;
            $lastname = !empty($_POST['lastname'])? $crudObj->inputFilter(($_POST['lastname'])): null;
            $email = !empty($_POST['email'])? $crudObj->inputFilter(($_POST['email'])): null;
            $crudObj->updateUserDetails($firstname, $lastname, $email);
        } else {
            $reply = Array("Opps, something went wrong. 'request=updateUserDetails' did not run.");
        }
    }
}
?>