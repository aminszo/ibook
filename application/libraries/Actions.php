<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Actions Library
  |
  | the action library contains various functions for different tasks. and its functions are used in almost
  | all controllers of this website. and so this library is loaded automatically on all controllers (config/autoload.php).
 */

class Actions {

    protected $CI;

    function __construct() {
        $this->CI = & get_instance(); // get an instance of CodeIgniter object to use its methods.
    }

    public function start_session() { // if session is not started yet, start session.
        if (!isset($_SESSION))
            session_start();
    }

    public function check_login() { // check whether the user is logged in or not.
        if (isset($_SESSION['_Logged_In'])) {
            $user_info = $this->CI->users->get_user_info('username', $_SESSION['_username']);
            if (isset($user_info))
                return TRUE;
        }

        return FALSE;
    }

    //this function specifies that access to a page requires logging in,
    //and if the user is not logged in, it will be redirected to the login page.
    public function need_login() {
        if (!$this->check_login())
            redirect(base_url() . 'login');
    }

    //this function specifies that access to a page needs to be logged out
    //and if the user is logged in, it will be redirected to the dashboard page.
    public function need_logout() {
        if ($this->check_login())
            redirect(base_url() . 'dashboard');
    }

    public function login_user($user_info) { //login user. store user information in session.
        $this->CI->session->set_userdata(array(
            '_id' => $user_info['id'],
            '_username' => $user_info['username'],
            '_firstname' => $user_info['firstname'],
            '_lastname' => $user_info['lastname'],
            '_email' => $user_info['email'],
            '_Logged_In' => TRUE,
        ));
    }

    public function get_current_user() { //get current user information from session data and return it as aoutput of the function.
        return array(
            'id' => $_SESSION['_id'],
            'username' => $_SESSION['_username'],
            'firstname' => $_SESSION['_firstname'],
            'lastname' => $_SESSION['_lastname'],
            'email' => $_SESSION['_email'],
        );
    }

    public function check_unique_user($identity_element, $identity_value) { //check whether a user with given specifications is available on database or not.
        $userinfo = $this->CI->users->get_user_info($identity_element, $identity_value); //check active users table.
        $inactive_user = $this->CI->users->get_inactive_user_info($identity_element, $identity_value); //check inactive users table.
        if (isset($userinfo) || isset($inactive_user))
            return FALSE;
        else
            return TRUE;
    }

    public function receive_input($input) { //receive the given field from $post in a safe way.
        if (isset($_POST[$input]) && !empty($_POST[$input]))
            return htmlspecialchars(trim($_POST[$input]));

        return NULL;
    }

    function check_length($string, $min, $max) { //Checks whether the given string is in the given range of min and max or not.
        if ($max < (strlen($string)))
            return 1;
        elseif ($min > (strlen($string)))
            return -1;
        else
            return 0;
    }

    public function send_access_status($accessCode, $value = TRUE) { //set a status flag in session.
        $this->CI->session->set_userdata(array($accessCode => $value));
    }

    // check if a status flag exist in session, remove it, and return value of the flag. otherwise return false.
    public function access_status($accessCode) {
        if (isset($_SESSION[$accessCode])) {
            $accessStatusValue = $_SESSION[$accessCode];
            unset($_SESSION[$accessCode]);
            return $accessStatusValue;
        } else
            return False;
    }

    // store necessary information in $data, to send them to view files.
    // title of the page | login status (is user logged in or not) | current user information.
    public function initial_data(&$data, $title = '', $loginStatus = 1, $currentUser = 1) {
        $data['title'] = $title;

        if ($loginStatus)
            $data['login_status'] = $this->check_login();

        if ($currentUser && $this->check_login())
            $data['current_user'] = $this->get_current_user();
    }

    public function color_code_to_name($code) { //get a color code and return the name of color.
        $colors = array(
            '1' => 'white', '2' => 'black', '3' => 'blue',
            '4' => 'red', '5' => 'orange', '6' => 'green',
            '7' => 'pink', '8' => 'yellow', '9' => 'purple',
        );
        return $colors[$code];
    }

    // generate a random hash string. this function is used for creating a email activation link.
    public function get_random_hash($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $hash = sha1($randomString);
        return $hash;
    }

    // check if the user's browser is 'internet explorer' or 'microsoft edge', return true. otherwise return false.
    public function is_IE() {
        $useragent = htmlspecialchars(trim($_SERVER['HTTP_USER_AGENT']));

        if (preg_match("/MSIE/i", $useragent) || preg_match("/EDGE/i", $useragent) || stripos($useragent, 'Trident/7.0') != false || preg_match("/rv:11.0/i", $useragent))
            return true;

        return false;
    }

    public function ie_block() { //if is_IE(), redirect to 'IE Block Page', and block access to the site.
        if ($this->is_IE()) {
            redirect(base_url() . 'ie_block');
            die();
        }
    }

    // validate the given value based on the given rule(parameter).
    public function check_the_parameter($value, $param) {
        $current_user = $this->get_current_user();
        switch ($param) {
            case 'category_title':
                if (mb_strlen($value) < 1 || mb_strlen($value) > 45)
                    return FALSE;
                break;

            case 'category_side':
                if (!in_array($value, array('1', '2')))
                    return FALSE;
                break;

            case 'category_color':
                if (!in_array($value, array('1', '2', '3', '4', '5', '6', '7', '8', '9')))
                    return FALSE;
                break;

            case 'category_id':
                if (!$this->CI->bookmarks->is_id_acceptable('category', $value, $current_user))
                    return FALSE;
                break;

            case 'bookmark_link':
                if (strlen($value) < 1 || strlen($value) > 400)
                    return FALSE;
                break;

            case 'bookmark_title':
                if (mb_strlen($value) < 1 || mb_strlen($value) > 100)
                    return FALSE;
                break;

            case 'bookmark_id':
                if (!$this->CI->bookmarks->is_id_acceptable('bookmarks', $value, $current_user))
                    return FALSE;
                break;

            case 'delete_option':
                if (!in_array($value, array('1', '2')))
                    return FALSE;
                break;

            case 'moveto_option':
                $categories = $this->CI->bookmarks->get_categories($current_user['id']);
                $categories_list[0] = 0;
                for ($i = 0; $i < count($categories); $i++) {
                    $categories_list[$i + 1] = $categories[$i]['id'];
                }
                if (!in_array($value, $categories_list))
                    return FALSE;
                break;

            default:
                return FALSE;
        }
        return TRUE;
    }

    // validate a list of items using check_the_parameter() function. and if all items were correct return true, otherwise, return false.
    public function check_input_values($fields) {
        foreach ($fields as $field => $parameter) {
            if (!$this->check_the_parameter($field, $parameter))
                return FALSE;
        }
        return TRUE;
    }

    // check all parameters that are sent with an ajax request, and if we had a wrong (unacceptable) value, send an "Not Acceptabl" error and finish the request.
    public function check_ajax_parameters($fields_array) {
        if (!$this->check_input_values($fields_array)) {
            http_response_code(406);
            die("");
        }
    }

    // send an activation email to the inactive user's email and return success or failure status.
    public function send_account_activation_email() {
        if (!isset($_SESSION['temp_user']))
            return 'failure';

        $temp_user = $_SESSION['temp_user'];
        $user_info = $this->CI->users->get_inactive_user_info('id', $temp_user['id']);

        if (!isset($user_info))
            return 'failure';

        $user_info['_password'] = $temp_user['pass'];

        $this->CI->load->library('email');
        $this->CI->email->from('noreply@ibooka.ir', 'ibooka');
        $this->CI->email->to($user_info['email']);

        $data['user_info'] = $user_info;
        $message = $this->CI->load->view('emails/activation', $data, true);

        $this->CI->email->subject('فعالسازی حساب کاربری - آی بوکا');
        $this->CI->email->message($message);

        if ($this->CI->email->send())
            return 'success';
        else
            return 'failure';
    }

}
