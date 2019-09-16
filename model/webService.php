<?php 
header('Content-Type: text/json; charset=UTF-8');
session_start();

try {
    include('function.php');
    include('sessions.php');

    //Explaination of session in README.txt document
    if(!isset($_SESSION['sessionControl'])) {
        $_SESSION['sessionControl'] = new sessionControl;
    }
    $crudObj = new crudControl;
    $sessionControl = new sessionControl;
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
                throw new Exception("something went wrong.");
            break;
        }
    } else {
        throw new Exception("something went wrong.");
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
                throw new Exception("something went wrong, create user didn't run.");
            }
        }

        if($_POST['request'] == 'createOrder') {
            if (!empty([$_POST])) {
                $order_name = !empty($_POST['order_name'])? $crudObj->inputFilter(($_POST['order_name'])): null;
                $discount_id = !empty($_POST['discount_id'])? $crudObj->inputFilter(($_POST['discount_id'])): null;
                $user_id = !empty($_POST['user_id'])? $crudObj->inputFilter(($_POST['user_id'])): null;
                $crudObj->createOrder($order_name, $discount_id, $user_id);
            } else {
                throw new Exception("something went wrong, create order didn't run.");
            }
        }

        if($_POST['request'] == 'createPost') {
            if (!empty([$_POST])) {
                $order_id = !empty($_POST['order_id'])? $crudObj->inputFilter(($_POST['order_id'])): null;
                $user_id = !empty($_POST['user_id'])? $crudObj->inputFilter(($_POST['user_id'])): null;
                $crudObj->createPost($order_id, $user_id);
            } else {
                throw new Exception("something went wrong, create post didn't run.");
            }
        } 

        if($_POST['request'] == 'updateUserDetails') {
            if (!empty([$_POST])) {
                $firstname = !empty($_POST['firstname'])? $crudObj->inputFilter(($_POST['firstname'])): null;
                $lastname = !empty($_POST['lastname'])? $crudObj->inputFilter(($_POST['lastname'])): null;
                $email = !empty($_POST['email'])? $crudObj->inputFilter(($_POST['email'])): null;
                $crudObj->updateUserDetails($firstname, $lastname, $email);
            } else {
                throw new Exception("something went wrong, update user details didn't run.");
            }
        }
    }
} catch(Exception $error) {
    echo json_encode(Array("opps"=>"something went wrong."));
}
?>