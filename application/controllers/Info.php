<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Info Pages Controller
  |
  | this controller loads proper view file and displays it for each information pages (info/sth) of the website.
 */

class Info extends CI_Controller {

    // 'about us' page.
    public function about() {
        $this->actions->ie_block();
        $this->actions->initial_data($data, 'درباره');

        $this->load->view('header', $data);
        $this->load->view('about', $data);
        $this->load->view('footer', $data);
    }

    // 'user guide' page.
    public function guide() {
        $this->actions->ie_block();
        $this->actions->initial_data($data, 'راهنما');

        $this->load->view('header', $data);
        $this->load->view('guide', $data);
        $this->load->view('footer', $data);
    }

    // 'contact us' page.
    public function contact() {
        $this->actions->ie_block();
        $this->actions->initial_data($data, 'تماس با ما');

        $this->load->view('header', $data);
        $this->load->view('contact', $data);
        $this->load->view('footer', $data);
    }

}
