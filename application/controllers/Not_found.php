<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Not_Found Page Controller
  |
  | when an invalid url is requested on this site, "NotFound(404) Page" will be loaded.
  | this controller is set to override defualt "404 Page" of codeigniter (in config/routes.php).
 */

class Not_found extends CI_Controller {

    public function index() {

        $this->actions->ie_block();

        $this->actions->initial_data($data, 'صفحه پیدا نشد', 1, 0);

        $this->load->view('header', $data);
        $this->load->view('not_found', $data);
        $this->load->view('footer', $data);
    }

}
