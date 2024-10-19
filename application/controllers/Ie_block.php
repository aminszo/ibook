<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | IE_Block Page Controller
  |
  | this controller checks the user's browser, if the browser is Internet Explorer or Microsoft Edge, it will
  | display an error message and block the access to the entire site.
  |
  | we know that blocking access for IE and Edge is not a good way. but since new web technologies are not well supported
  | by these two browsers, we have to block them to prevent errors or defects on this site.
  |
 */

class Ie_block extends CI_Controller {

    public function index() {

        if (!$this->actions->is_IE()) { // if the browser is not ie or edge, redirect to an invalid page to display 404 error.
            redirect(base_url() . 'ie-block');
        } else { //if is_IE() returns true, load ie_block view page.
            $this->load->view('ie_block');
        }
    }

}
