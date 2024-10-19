<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Dashboard Page Controller
  |
  | the dashboard is the main page of this website.
  | in this controller we get the stored user bookmarks from the database, categorize and sort them in an array, and
  | send it to dashboard view. all other works(like change, add and delete categories and bookmarks) will be done
  | on the dashboard view page by javascript and ajax (and 'ajax_handler' controller for handling ajax requests).
 */

class Dashboard extends CI_Controller {

    public function index() {

        $this->actions->ie_block();

        $this->actions->need_login();

        $this->actions->initial_data($data, 'داشبورد');

        $bookmarks = array(); //initial $bookmark array.

        $current_user = $this->actions->get_current_user(); //get current user information.

        $categories = $this->bookmarks->get_categories($current_user['id']); //get stored categories of current user.

        for ($i = 0; $i < count($categories); $i++) { //get information of each category and store them in $bookmarks.
            $bookmarks[$i]['id'] = $categories[$i]['id'];
            $bookmarks[$i]['color'] = $this->actions->color_code_to_name($categories[$i]['color_id']);
            $bookmarks[$i]['name'] = $categories[$i]['name'];
            $bookmarks[$i]['side'] = $categories[$i]['horizontal_pos'];
            $bookmarks[$i]['level'] = $categories[$i]['vertical_pos'];

            $category_bookmarks = $this->bookmarks->get_bookmarks_in_category($categories[$i]['id']);

            for ($j = 0; $j < count($category_bookmarks); $j++) { //get bookmarks of each category and store them in $bookmark.
                $bookmarks[$i]['links'][$j]['id'] = $category_bookmarks[$j]['id'];
                $bookmarks[$i]['links'][$j]['category_id'] = $category_bookmarks[$j]['category_id'];
                $bookmarks[$i]['links'][$j]['link'] = $category_bookmarks[$j]['link'];
                $bookmarks[$i]['links'][$j]['title'] = $category_bookmarks[$j]['title'];
                $bookmarks[$i]['links'][$j]['note'] = $category_bookmarks[$j]['note'];
                $bookmarks[$i]['links'][$j]['icon'] = $category_bookmarks[$j]['icon'];
                $bookmarks[$i]['links'][$j]['level'] = $category_bookmarks[$j]['vertical_pos'];
            }
        }

        //put $bookmarks in $data to send it to the dashboard view.
        $data['bookmarks'] = $bookmarks;

        //load an additional stylesheet for this page into header.php.
        $data['additional_css'] = array('dashboard');

        //load view files.
        $this->load->view('header', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('footer', $data);
    }

}
