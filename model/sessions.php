<?php
class sessionControl {
    private $conn;
    private $last_request;
    private $curr_request;
    private $new_request;

    //explination of code in README.txt file
    public function rateLimitChecker() {
        function less_than_a_sec($sec){
            return time() - $sec <= 1;
        }
        if(!isset($_SESSION['sec_rate_limit'])) {
            $_SESSION['sec_rate_limit'] = array();
        }
        array_push($_SESSION['sec_rate_limit'], time());

        $_SESSION['sec_rate_limit'] = array_filter($_SESSION['sec_rate_limit'], "less_than_a_sec");
        
        if(sizeof($_SESSION['sec_rate_limit']) >= 6) { //needs to be 6 request pr second
            echo json_encode("too many requests per second");
            die();
        }
    }
 
    // explination of rate limiter can be found in README.txt
    public function Rate24HourCheck() {
        function less_than_a_day($sec){
            return time() - $sec <= 86400;
        }
        if(!isset($_SESSION['day_rate_limit'])) {
            $_SESSION['day_rate_limit'] = array();
        }
        array_push($_SESSION['day_rate_limit'], time());

        $_SESSION['day_rate_limit'] = array_filter($_SESSION['day_rate_limit'], "less_than_a_day");
        
        if(sizeof($_SESSION['day_rate_limit']) >= 1000) {
            echo json_encode("too many requests per day");
            die();
        }
    }
    
    public function domainlock() {
        if($_SERVER['HTTP_HOST'] === "localhost"){
            // do nothing.
        } else {
            throw new Exception("this website does not support your domain");
            die();
        }
    }

    public function referrexists() {
        if(!isset($_SERVER['HTTP_REFERER'])) {
            throw new Exception("no referer exists");
            die();
        } 
    }
}
   /* future stuff 
   public function endSession() {
        session_start();
        session_unset();
        session_destroy();
        // need to have go to index.html
    } */
?>