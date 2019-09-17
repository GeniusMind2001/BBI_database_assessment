<?php
class crudControl {
    private $conn;
    public function __construct() {
        // if the username and password to database before commencing please chage the details below
        $this->conn = new PDO("mysql:host=localhost;dbname=bigbellyicecream", 'root', '');
    }
    public function logRequest() {
        try {
            $serverAddr = $_SERVER['SERVER_ADDR'];
            $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
            $requestTime = $_SERVER['REQUEST_TIME'];
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $sql = "INSERT INTO requestlog(server_addr, http_user_agent, request_time, request_method) 
            VALUES('". $serverAddr . "', '" . $httpUserAgent . "', '" . $requestTime . "', '" . $requestMethod . "');";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        } catch (PDOException $error) {
            // $error = Array("Opps, something went wrong: " => $error);
            // echo json_encode($error);
            throw("log request did not work.");
        }
    }
    public function getAllposts() {
        try {
            // Explaination of code can be found in README.txt document.
            $sql = "SELECT posts.vote_num, posts.publish_date, users.firstname, users.lastname, orders.order_name
            FROM posts
            JOIN users ON posts.user_id = users.user_id
            JOIN orders ON orders.order_id = posts.order_id
            WHERE publish_date > CURRENT_DATE - 7
            ORDER BY posts.vote_num DESC;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (PDOException $err) {
           // $reply = Array("something went wrong." => $err);
            throw("get all posts didn't work.");
        }
    }

    public function getAllOrders() {
        try { // use transaction to display item quantity and order items
            $sql = "SELECT order_name, discount_id
            FROM orders
            WHERE user_id=".$_GET['user_id'].";";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (PDOException $err) {
           // $reply = Array("something went wrong." => $err);
           throw("get all orders did not work.");
        } 
    }

    public function getAlldiscounts() {
        try {
            //need to make sql proper
            $sql = "SELECT discount_code, description, start_date, end_date FROM discounts;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (PDOException $err) {
            // $reply = Array("something went wrong." => $err);
            throw("get all discounts did not work.");
        }
    }

    public function getUserdetails() {
        try {
            // need to display orders and discounts
            $sql = "SELECT users.firstname, users.lastname, users.email
            FROM users
            WHERE users.user_id =". $_GET['user_id'] .";";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (PDOException $err) {
           // $reply = Array("something went wrong." => $err);
           throw("get user details did not work.");
        }   
    } 

    public function createUser($firstname, $lastname, $email, $password) {
        try {
            $sql = "INSERT INTO users(firstname, lastname, email, password) 
            VALUES (:firstname, :lastname, :email, :password);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':firstname', $firstname);
            $stmt->bindValue(':lastname', $lastname);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->execute();
            $reply = Array("registration successful values submitted:" => $_POST['firstname'], $_POST['lastname'], $_POST['email']);
            echo json_encode($reply); 
        }
        catch (PDOException $err) {
            // $reply = Array("something went wrong." => $err);
            throw("get create user did not work.");
        }
    }

    public function createOrder($order_name, $discount_id, $user_id) {
        try {
            $sql = "INSERT INTO orders(order_name, user_id, discount_id)
            VALUES (:order_name, :user_id, :discount_id);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_name', $order_name);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':discount_id', $discount_id);
            $stmt->execute();
            $reply = Array("Your submitted values are:" => $_POST['order_name'], $_POST['discount_id']);
            echo json_encode($reply);
          // header("../view/pages/orderPage.html")
        } catch (PDOException $err) {
            //  $reply = Array("something went wrong." => $err);
            throw("get create order did not work.");
        }
    }
 
    public function createPost($order_id, $user_id) {
        try {
            $sql = "INSERT INTO posts(order_id, user_id)
            VALUES(:order_id, :user_id);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_id', $order_id);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
            $reply = Array("your post was successful, order posted was:" => $_POST['order_id']);
            echo json_encode($reply); 
        } catch (PDOException $err) {
            // $reply = Array("something went wrong." => $err);
            throw("get create posts did not work.");
        }
    }

    public function updateUserDetails($firstname, $lastname, $email) {
        try {
            //need to make sql proper
            $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email 
            WHERE user_id=40";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':firstname', $firstname);
            $stmt->bindValue(':lastname', $lastname);
            $stmt->bindValue(':email', $email);
            $stmt->execute(); 
            $reply = Array("update successful values submitted:" => $_POST['firstname'], $_POST['lastname'], $_POST['email']);
            echo json_encode($reply);           
        } catch (PDOException $err) {
            // $reply = Array("something went wrong." => $err);
            throw("get update user details did not work.");
        }
    }
    public function inputFilter($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
?>