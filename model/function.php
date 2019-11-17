<?php
class crudControl {
    private $conn;
    public function __construct() {
        try {
        // if the username and password to database is difference change the details below
        $this->conn = new PDO("mysql:host=localhost;dbname=symfony", 'root', '');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // DEBUG
        } catch(PDOException $e) {
            throw new PDOException("database connection failed");
        }
    }
    public function logRequest() {
        try {
            $serverAddr = $_SERVER['SERVER_ADDR'];
            $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $sql = "INSERT INTO requestlog(server_address, http_user_agent, request_method) 
            VALUES('". $serverAddr . "', '" . $httpUserAgent . "', '" . $requestMethod . "');";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        } catch (Exception $opps) {
            throw new Exception("request log did not work.");
        } catch (PDOException $e) {
            throw new PDOException("Error cannot log request");
        }
    }

    public function getAllposts() {
        try { 
            // Explaination of code can be found in README.txt document.
            $sql = "SELECT orders.order_name, posts.publish_date, posts.vote_num, users.firstname, users.lastname
            FROM posts
            RIGHT JOIN orders ON orders.id=posts.id
            LEFT JOIN users ON orders.id=users.id
            WHERE posts.publish_date IS NOT NULL;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (Exception $opps) {
            throw new Exception("get all posts didn't work.");
        }
    }

    public function getAllitems() {
        try {
            // Explaination of code can be found in README.txt document.
            $sql = "SELECT * FROM items";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (Exception $opps) {
            throw new Exception("get all items didn't work.");
        }
    }

    public function getAllOrders() {
        try { // use transaction to display item quantity and order items
            $sql = "SELECT orders.order_name, discounts.discount_code, users.firstname, users.lastname, users.email
            FROM posts
            RIGHT JOIN orders ON orders.id=posts.id
            RIGHT JOIN users ON orders.id=users.id
            LEFT JOIN discounts ON discounts.discount_code=orders.discount_code
            WHERE posts.publish_date IS NULL;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (Exception $opps) {
           throw new Exception("get all orders did not work.");
        } 
    }

    public function getAlldiscounts() {
        try {
            $sql = "SELECT discount_code, description, start_date, end_date FROM discounts;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (Exception $opps) {
            throw new Exception("get all discounts did not work.");
        }
    }

    public function getUserdetails() {
        try {
            $sql = "SELECT firstname, lastname, email FROM users WHERE email='hahabel@malekter.com';";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } catch (Exception $opps) {
           throw new Exception("get user details did not work.");
        }   
    }
    
    public function loginUser($email, $password) {
        try {
            $sql = "SELECT email, password, role FROM users;";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // DEBUG
            $rows = $stmt->fetch();
            if(password_verify($password, $rows['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['access'] = $rows['role'];
                $_SESSION['login'] = true;
                $reply = Array("login was successful values submitted:" => $_POST['email']);
                echo json_encode($reply); 
            } else {
                throw new Exception("Login was not successful, please try again.");
                die();
            }
        } catch(PDOException $e) {
            throw new PDOException($e);
            die();
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
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // DEBUG
        } catch (Exception $opps) {
            throw new Exception("Couldn't create user, please try again.");
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    } 

    public function createOrder($order_name, $discount_code, $user_id) {
        try {
            $sql = "INSERT INTO orders(order_name, discount_code, user_id)
            VALUES (:order_name, :user_id, :discount_code);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':order_name', $order_name);
            $stmt->bindValue(':discount_code', $discount_code);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
            $reply = Array("Your submitted values are:" => $_POST['order_name'], $_POST['discount_code']);
            echo json_encode($reply);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // DEBUG
        } catch (Exception $opps) {
            throw new Exception("Couldn't create order, please try again.");
           // throw new Exception($opps);
        } catch (PDOException $e) {
            throw new PDOException($e);
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
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // DEBUG 
        } catch (Exception $opps) {
            throw new Exception("Couldn't create post, please try again.");
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    public function updateUserDetails($firstname, $lastname, $email) {
        try {
            //need to make sql proper
            $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email 
            WHERE veid=39";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':firstname', $firstname);
            $stmt->bindValue(':lastname', $lastname);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $reply = Array("update successful values submitted:" => $_POST['firstname'], $_POST['lastname'], $_POST['email']);
            echo json_encode($reply);     
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // DEBUG      
        } catch (Exception $opps) {
            throw new Exception("Couldn't update user details, please try again.");
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }
    public function inputFilter($type, $value) {
        try {
            if($type == "number") {
                $int = $value;
                $int_min = 1;
                $int_max = 4294967295; // maximum unsigned int possible
                if(filter_var($int, FILTER_VALIDATE_INT, Array("options"=>Array("min_range"=>$int_min, "max_range"=>$int_max))) === false) {
                    throw new Exception("number is not valid");
                    die();
                } else {
                   return($int);
                }
            }
            if($type == "email") {
                $email = $value;
                if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    throw new Exception("email address is not valid.");
                    die();
                } else {
                   return($email);
                }
            } 
            if($type == "text") {
                $text = $value;
                $text_length = strlen($text);
                if($text_length > 20 || $text_length < 2) {
                    throw new Exception("text is not valid.");
                    die();
                } else {
                    return($text);
                }
            }
        } catch(Exception $opps) {
            throw new Exception($opps->getMessage());
        }
    }
}
?>