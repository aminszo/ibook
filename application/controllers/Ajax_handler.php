<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Ajax_hanlder Controller
  |
  | this controller contains functions for handling ajax requests that are sent by the dashboard page to
  | add, modify or delete bookmarks or categories. each function receives some parameters from input and check
  | them by check_ajax_parameters() function, an if they had correct and acceptable values, perform a certain task.
  | and sends 'true status' and the 'result' on success, and 'false status' on failure.
  | the output of this controller will be in the json format, so that it can be easily used in javascript.
 */

class Ajax_handler extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // check the authentificatin, and if it's not correct, redirect to home. by doing this, only ajax requests 
        // can access these functions. and users cannot access this controller by typing it's url in address bar.
        $auth = $this->input->post('authentication', TRUE);
        if (!isset($auth) || $auth !== "#_asz_#")
            redirect(base_url() . 'bookmark');

        header('Content-type: application/json'); // set the content type of all functions to json.

        set_time_limit(1); // set maximum execution time to 10 seconds for all functions.
    }

    // add a new category and send 'html content' of category.
    public function add_category() {

        $name = $this->actions->receive_input('name');
        $side = $this->actions->receive_input('side');
        $color = $this->actions->receive_input('color');

        $this->actions->check_ajax_parameters(array($name => 'category_title', $side => 'category_side', $color => 'category_color'));

        $current_user = $this->actions->get_current_user();

        $information = array(
            'user_id' => $current_user['id'],
            'color_id' => $color,
            'name' => $name,
            'horizontal_pos' => $side,
            'vertical_pos' => $this->bookmarks->set_category_vertical_position($current_user['id'], $side),
        );

        if ($this->bookmarks->add_category($information)) {

            $category_id = $this->bookmarks->get_insert_id();

            $color = $this->actions->color_code_to_name($color);
            $side = ($side == 1) ? "right" : "left";

            $elements = "
            <div class='group $side' category_id='$category_id'>
                <div class='group-header' color_name='$color'>
                    <div class='head-box'><i class='far fa-folder'></i><span>$name</span>
                        <button class='show-options'></button>
                        <div class='action-buttons'>
                            <button class='act-button delete'></button>
                            <button class='act-button edit'></button>
                            <button class='act-button moveup'></button>
                            <button class='act-button movedown'></button>
                            <button class='act-button moveleft'></button>
                            <button class='act-button moveright'></button>
                        </div>
                    </div>
                </div>
                <div class='group-content'></div>
                <div class='group_footer'>
                    <button class='button-1 small add-bookmark'>
                        <span>افرودن بوکمارک</span><i class='far fa-plus-octagon'></i>
                    </button>
                </div>
            </div> ";

            $json['status'] = true;
            $json['text'] = $elements;
        } else {
            $json['status'] = false;
        }

        echo json_encode($json);
    }

    // modify a category.
    public function edit_category() {

        $category_id = $this->actions->receive_input('category_id');
        $color_id = $this->actions->receive_input('color');
        $title = $this->actions->receive_input('title');

        $this->actions->check_ajax_parameters(array($title => 'category_title', $category_id => 'category_id', $color_id => 'category_color'));

        $information = array('color_id' => $color_id, 'name' => $title);

        if ($this->bookmarks->update_category($information, $category_id)) {
            $json['status'] = true;
            $json['color'] = $this->actions->color_code_to_name($color_id);
            $json['title'] = $title;
        } else
            $json['status'] = false;

        echo json_encode($json);
    }

    // deletes a category, and removes its bookmarks or transfers to another category depending on the user's choice.
    public function delete_category() {

        $category_id = $this->actions->receive_input('category_id');
        $delete = $this->actions->receive_input('delete');
        $moveto = $this->actions->receive_input('moveto');

        $this->actions->check_ajax_parameters(array($delete => 'delete_option', $category_id => 'category_id', $moveto => 'moveto_option'));

        if ($delete == '1') {
            if ($this->bookmarks->delete_category($category_id) && $this->bookmarks->delete_bookmarks_of_category($category_id)) {
                $json['status'] = true;
            } else
                $json['status'] = false;
        } else {
            if ($this->bookmarks->delete_category($category_id) && $this->bookmarks->move_bookmarks_of_category($category_id, $moveto)) {
                $json['status'] = true;
            } else
                $json['status'] = false;
        }

        echo json_encode($json);
    }

    // sends a list of all categories of a user, except one of them. this function is used
    // when user wants to delete a category, and move its bookmarks to another category.
    public function get_except_categories() {

        $category_id = $this->actions->receive_input('category_id');

        $this->actions->check_ajax_parameters(array($category_id => 'category_id'));

        $current_user = $this->actions->get_current_user();

        $categories = $this->bookmarks->get_categories_except($current_user['id'], $category_id);
        $categories_list = array();

        if ($categories) {
            for ($i = 0; $i < count($categories); $i++) {
                $categories_list[$i]['id'] = $categories[$i]['id'];
                $categories_list[$i]['name'] = $categories[$i]['name'];
            }
            $json['status'] = true;
            $json['categories'] = $categories_list;
        } else
            $json['status'] = false;

        echo json_encode($json);
    }

    // get the page title of the given url, using preg_match().
    // this function is used to auto fill title field when adding a new bookmark.
    public function get_title() {

        set_time_limit(1);
        
        $link = prep_url($this->actions->receive_input('link'));

        $this->actions->check_ajax_parameters(array($link => 'bookmark_link'));

        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            $json['status'] = false;
        } else {
            $content = file_get_contents($link);
            if (strlen($content) > 0) {
                $content = trim(preg_replace('/\s+/', ' ', $content));
                preg_match("/\<title\>(.*)\<\/title\>/i", $content, $title);

                if (isset($title[1])) {
                    $json['status'] = true;
                    $json['text'] = mb_substr($title[1], 0, 90);
                } else
                    $json['status'] = false;
            }
        }

        echo json_encode($json);
    }

    // add a new bookmark and send 'html content' of bookmark.
    public function add_bookmark() {

        $category_id = $this->actions->receive_input('category_id');
        $link = prep_url($this->actions->receive_input('link'));
        $title = $this->actions->receive_input('title');

        $this->actions->check_ajax_parameters(array($category_id => 'category_id', $link => 'bookmark_link', $title => 'bookmark_title'));

        $current_user = $this->actions->get_current_user();

        $information = array(
            'link' => $link,
            'title' => $title,
            'category_id' => $category_id,
            'user_id' => $current_user['id'],
            'vertical_pos' => $this->bookmarks->set_bookmark_vertical_position($category_id),
        );


        if ($this->bookmarks->add_bookmark($information)) {

            $bookmark_id = $this->bookmarks->get_insert_id();

            $elements = "
            <div class='link-item' link-id='$bookmark_id'>
                <img src='" . base_url() . "assets/image/sample-favicon.png' alt='favicon' class='favicon-pic'><a class='anchor' target='_blank' href='$link'>$title</a>\n
                <button class='show-options'></button>
                <div class='action-buttons'>
                    <button class='act-button delete'></button>
                    <button class='act-button edit'></button>
                    <button class='act-button moveup'></button>
                    <button class='act-button movedown'></button>
                </div>
            </div> ";

            $json['status'] = true;
            $json['text'] = $elements;
        } else
            $json['status'] = false;

        echo json_encode($json);
    }

    // modify a bookmark.
    public function edit_bookmark() {

        $bookmark_id = $this->actions->receive_input('bookmark_id');
        $link = prep_url($this->actions->receive_input('link'));
        $title = $this->actions->receive_input('title');

        $this->actions->check_ajax_parameters(array($bookmark_id => 'bookmark_id', $link => 'bookmark_link', $title => 'bookmark_title'));

        $information = array('link' => $link, 'title' => $title);

        if ($this->bookmarks->update_bookmark($information, $bookmark_id)) {
            $json['status'] = true;
            $json['link'] = $link;
            $json['title'] = $title;
        } else
            $json['status'] = false;

        echo json_encode($json);
    }

    // deletes a bookmark.
    public function delete_bookmark() {

        $bookmark_id = $this->actions->receive_input('bookmark_id');

        $this->actions->check_ajax_parameters(array($bookmark_id => 'bookmark_id'));

        if ($this->bookmarks->delete_bookmark($bookmark_id)) {
            $json['status'] = true;
        } else
            $json['status'] = false;

        echo json_encode($json);
    }

    // moves a bookmark to the given direction (up or down).
    public function move_bookmark() {

        $bookmark_id = $this->actions->receive_input('bookmark_id');
        $direction = $this->actions->receive_input('direction');

        $this->actions->check_ajax_parameters(array($bookmark_id => 'bookmark_id'));

        if ($this->bookmarks->move_bookmark($bookmark_id, $direction)) {
            $json['status'] = true;
        } else
            $json['status'] = false;

        echo json_encode($json);
    }

    // moves a category to the given direction (up, down, right, or left).
    public function move_category() {
        $category_id = $this->actions->receive_input('category_id');
        $direction = $this->actions->receive_input('direction');

        $this->actions->check_ajax_parameters(array($category_id => 'category_id'));

        if ($this->bookmarks->move_category($category_id, $direction)) {
            $json['status'] = true;
        } else
            $json['status'] = false;

        echo json_encode($json);
    }

}
