<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Logout Controller
  |
  | click on 'logout' button in any page, will redirect user to this controller. and here we will
  | logout user(delete user information from session) and redirect user to login page.
  |
  | this controller doesn't have a view file.
 */

class Logout extends CI_Controller {

    public function index() {

        $this->actions->ie_block();

        $this->actions->need_login();

        session_unset(); // remove user information from session.

        $this->actions->send_access_status('just_logout'); // set just_logout status in session(for showing a message in login page).

        redirect(base_url() . 'login'); //redirect to login page.
    }

}
