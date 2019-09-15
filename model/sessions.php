<?php
class sessionControl {
    private $conn;
    private $last_request;
    private $curr_request;
    private $new_request;

    //I instantiated my session objects here because by keeping the numorous functions seperated 
    //I could have a clear understanding of what does what and help other developer's understand my code.
    public function rateLimitChecker() {
        if(!isset($_SESSION['last_times'])) {
            $_SESSION['last_times'] = Array();
        }
        if(isset($_SESSION['last_time'])) {
            //Explain the mathmathics... ok.
            $last = strtotime($_SESSION['last_time']);
            $curr = strtotime(date("Y-m-d h:i:s"));
            // below mathmatic represents that $sec equals the last time a request was made ($last) and this current request ($curr)
            // and if the last request minus this current request is less then or equal to one second then tell the users they have exceeded
            // the rate limit and die. 
            $sec = abs($last - $curr);
            if ($sec <= 1) {
                $reply = Array('Rate Limit Exceeded, no more then one request per sec.');
                echo json_encode($reply);
                die();      
            }
        }
        $_SESSION['last_time'] = time();
        array_push($_SESSION['last_times'], time());
        $_SESSION['last_time'] = date("Y-m-d h:i:s");
    } 
    public function Rate24HourCheck() {
        $this->last_request = Array();
        array_push($this->last_request, time());
        $count = 0;
        foreach($this->last_request as $item) {
            $this->curr_request = time();
            $count ++;
            if($this->new_request > ($this->curr_request - 86400) && $count < 1000) {
                echo json_encode(Array("Rate Limit Exceeded, no more then 1,000 in 24hrs"));
                die();
            }
        }
        if($this->new_request < ($this->curr_request - 86400)) {
            unset($this->last_request);
        }
        $this->last_request = time();
    }
    public function domainlock() {
        if($_SERVER['HTTP_HOST'] === "localhost"){
            // do nothing.
        } else {
            $reply = Array("Error: website does not support your domain");
            echo json_encode($reply);
            die();
        }
    }
    public function referrexists() {
        if(!isset($_SERVER['HTTP_REFERER'])) {
            $reply = Array("Error: no referer exsits.");
            echo json_encode($reply);
            die();
        } 
    }
}
?>