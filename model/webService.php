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
            case 'getAllitems':
                $crudObj->getAllitems();
            break;
            default:
                throw new Exception("something went wrong, couldn't find function.");
            break;
        }
    } else {
       // do nothing.
    }

    if(isset($_POST['request'])) {
        if($_POST['request'] == 'loginUser') {
            if (!empty([$_POST])) {
                $email = !empty($_POST['email'])? $crudObj->inputFilter("email", ($_POST['email'])): null;
                $password = !empty($_POST['password'])? $crudObj->inputFilter("password", ($_POST['password'])): null;
                $crudObj->loginUser($email, $password);
            } else {
                throw new Exception("something went wrong, login failed.");
            }
        }

        if($_POST['request'] == 'createUser') {
            if (!empty([$_POST])) {
                $firstname = !empty($_POST['firstname'])? $crudObj->inputFilter("text", ($_POST['firstname'])): null;
                $lastname = !empty($_POST['lastname'])? $crudObj->inputFilter("text", ($_POST['lastname'])): null;
                $email = !empty($_POST['email'])? $crudObj->inputFilter("email", ($_POST['email'])): null;
                $mypassword = !empty($_POST['password'])? $crudObj->inputFilter("password", ($_POST['password'])): null;
                $password = password_hash($mypassword, PASSWORD_DEFAULT);
                $crudObj->createUser($firstname, $lastname, $email, $password);
            } else {
                throw new Exception("something went wrong, create user was empty");
            }
        }

        if($_POST['request'] == 'createOrder') {
            if (!empty([$_POST])) {
                $order_name = !empty($_POST['order_name'])? $crudObj->inputFilter("text", ($_POST['order_name'])): null;
                $discount_code = !empty($_POST['discount_code'])? $crudObj->inputFilter("number", ($_POST['discount_code'])): null;
                $user_id = !empty($_POST['user_id'])? $crudObj->inputFilter("number", ($_POST['user_id'])): null;
                $crudObj->createOrder($order_name, $discount_code, $user_id);
            } else {
                throw new Exception("something went wrong, create order didn't run.");
            }
        }

        if($_POST['request'] == 'OrderItems') {
            if (!empty([$_POST])) {
                $item_id = !empty($_POST['item_id'])? $crudObj->inputFilter("number", ($_POST['item_id'])): null;
                $order_id = !empty($_POST['order_id'])? $crudObj->inputFilter("number", ($_POST['order_id'])): null;
                $quantity = !empty($_POST['quantity'])? $crudObj->inputFilter("number", ($_POST['quantity'])): null;
                $crudObj->createOrder($item_id, $order_id, $quantity);
            } else {
                throw new Exception("something went wrong, create order didn't run.");
            }
        }


        if($_POST['request'] == 'createPost') {
            if (!empty([$_POST])) {
                $order_id = !empty($_POST['order_id'])? $crudObj->inputFilter("number", ($_POST['order_id'])): null;
                $user_id = !empty($_POST['user_id'])? $crudObj->inputFilter("number", ($_POST['user_id'])): null;
                $crudObj->createPost($order_id, $user_id);
            } else {
                throw new Exception("something went wrong, create post didn't run.");
            }
        } 

        if($_POST['request'] == 'updateUserDetails') {
            if (!empty([$_POST])) {
                $firstname = !empty($_POST['firstname'])? $crudObj->inputFilter("text", ($_POST['firstname'])): null;
                $lastname = !empty($_POST['lastname'])? $crudObj->inputFilter("text", ($_POST['lastname'])): null;
                $email = !empty($_POST['email'])? $crudObj->inputFilter("email", ($_POST['email'])): null;
                $crudObj->updateUserDetails($firstname, $lastname, $email);
            } else {
                throw new Exception("To update details you need to insert values");
            }
        }
    } else {
       
    }
} catch(Exception $opps) {
    echo json_encode(Array("Message"=>$opps->getMessage()));
} catch(PDOException $e) {
    echo json_encode(Array("Error"=>$e->getMessage()));
}
?>