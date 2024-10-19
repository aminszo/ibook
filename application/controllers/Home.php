<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Home Page Controller
  |
  | this controller shows home page to user.
  | home page displays only when the user is not logged in.
 */

class Home extends CI_Controller {

    public function index() {

        $this->actions->ie_block();

        $this->actions->need_logout();

        $this->actions->initial_data($data, 'مدیریت آنلاین بوکمارک');

        //load an additional stylesheet for this page into header.php.
        $data['additional_css'] = array('home');

        //load view files.
        $this->load->view('header', $data);
        $this->load->view('home', $data);
        $this->load->view('footer', $data);
    }

}
