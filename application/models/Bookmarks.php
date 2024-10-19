<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Bookmarks Model
  |
  | this model works with 'bookmarks' and 'category' tables of the database.
  | and this model is loaded automatically on all controllers (config/autoload.php).
 */

class Bookmarks extends CI_Model {

    function __construct() { // load database for all functions.
        parent::__construct();
        $this->load->database();
    }

    public function get_categories($user_id) { // get all categories of a user from database.
        $this->db->order_by('vertical_pos', 'ASC');
        $categories = $this->db->get_where('category', array('user_id' => $user_id));
        return $categories->result_array();
    }

    public function get_bookmarks_in_category($category_id) { // get all bookmarks of a category from database.
        $this->db->order_by('vertical_pos', 'ASC');
        $bookmarks = $this->db->get_where('bookmarks', array('category_id' => $category_id));
        return $bookmarks->result_array();
    }

    // get all categories except special one (for moving bookmarks when deleting a category)
    public function get_categories_except($user_id, $except_category_id) {
        $categories = $this->db->get_where('category', array('user_id' => $user_id, 'id !=' => $except_category_id));
        return $categories->result_array();
    }

    public function add_category($information) { // adds a new category with given information.
        return $this->db->insert('category', $information);
    }

    public function update_category($information, $id) { // updates a category with given information.
        return $this->db->update('category', $information, array('id' => $id));
    }

    // deletes a category. and when a category is deleted, vertical position of categories that are located on
    // the same side and bottom of the category, should be reduced, to maintain the order of the categories.
    public function delete_category($category_id) {
        $this->db->select('vertical_pos , horizontal_pos');
        $current_query = $this->db->get_where('category', array('id' => $category_id));
        $current = $current_query->row_array();

        $this->db->set('vertical_pos', 'vertical_pos-1', FALSE);
        $this->db->where(array('horizontal_pos' => $current['horizontal_pos'], 'vertical_pos > ' => $current['vertical_pos']));
        $this->db->update('category');

        return $this->db->delete('category', array('id' => $category_id));
    }

    // deletes all the bookmarks of a category.
    public function delete_bookmarks_of_category($category_id) {
        return $this->db->delete('bookmarks', array('category_id' => $category_id));
    }

    // get all bookmarks of origin category and append them to the target category.
    public function move_bookmarks_of_category($origin_category_id, $target_category_id) {
        $this->db->select('id');
        $this->db->order_by('vertical_pos', 'ASC');
        $bookmarks_query = $this->db->get_where('bookmarks', array('category_id' => $origin_category_id));
        $bookmarks = $bookmarks_query->result_array();

        $vertical_position = $this->set_bookmark_vertical_position($target_category_id);
        foreach ($bookmarks as $bookmark) {
            $this->db->update('bookmarks', array('category_id' => $target_category_id, 'vertical_pos' => $vertical_position), array('id' => $bookmark['id']));
            $vertical_position += 1;
        }

        return TRUE;
    }

    public function add_bookmark($information) { // adds a new bookmark with given information.
        return $this->db->insert('bookmarks', $information);
    }

    public function update_bookmark($information, $id) { // updates a bookmark with given information.
        return $this->db->update('bookmarks', $information, array('id' => $id));
    }

    // deletes a bookmark. and when a bookmark is deleted, vertical position of bookmarks that are located on
    // the same category and bottom of the bookmark, should be reduced, to maintain the order of the bookmarks.
    public function delete_bookmark($bookmark_id) {
        $this->db->select('vertical_pos , category_id');
        $current_query = $this->db->get_where('bookmarks', array('id' => $bookmark_id));
        $current = $current_query->row_array();

        // decrease the vertical position of categories, where their vertical_pos are 
        // smaller than current category(those that are above the current category).
        $this->db->set('vertical_pos', 'vertical_pos-1', FALSE);
        $this->db->where(array('category_id' => $current['category_id'], 'vertical_pos > ' => $current['vertical_pos']));
        $this->db->update('bookmarks');

        return $this->db->delete('bookmarks', array('id' => $bookmark_id));
    }

    public function get_insert_id() { // get id of last inserted row of database.
        return $this->db->insert_id();
    }

    // get vertical position of the last category in a side, and return +1 of it.
    public function set_category_vertical_position($user_id, $side) {
        $result_query = $this->db->get_where('category', array('user_id' => $user_id, 'horizontal_pos' => $side));
        $result = $result_query->result_array();
        return count($result) + 1;
    }

    // get vertical position of the last bookmark in a category, and return +1 of it.
    public function set_bookmark_vertical_position($category_id) {
        $result_query = $this->db->get_where('bookmarks', array('category_id' => $category_id));
        $result = $result_query->result_array();
        return count($result) + 1;
    }

    // move a bookmark up or down in a category.
    // receives a bookmark id and a direction(up or down) and depending on the direction, changes the vertical position
    // of the bookmark and its previous or next bookmark to to maintain the order of the bookmarks.
    public function move_bookmark($bookmark_id, $direction) {
        $this->db->select('vertical_pos , category_id');
        $current_query = $this->db->get_where('bookmarks', array('id' => $bookmark_id));
        $current = $current_query->row_array();

        if ($direction == 'up')
            $ST = array(1 => '+1', 2 => -1, 3 => '-1');
        elseif ($direction == 'down')
            $ST = array(1 => '-1', 2 => +1, 3 => '+1');

        $this->db->set('vertical_pos', "vertical_pos$ST[1]", FALSE);
        $this->db->where(array('category_id' => $current['category_id'], 'vertical_pos' => $current['vertical_pos'] + $ST[2]));
        $check1 = $this->db->update('bookmarks');

        $this->db->set('vertical_pos', "vertical_pos$ST[3]", FALSE);
        $this->db->where('id', $bookmark_id);
        $check2 = $this->db->update('bookmarks');

        ($check1 && $check2) ? $return = 1 : $return = 0;
        return $return;
    }

    // move a category up, down, left or right.
    // receives a category id and a direction(up or down or left or right) and depending on the direction, changes 
    // the vertical position and horizontal position of the categories to maintain the order of the categories.
    public function move_category($category_id, $direction) {
        $this->db->select('vertical_pos , horizontal_pos , user_id');
        $current_query = $this->db->get_where('category', array('id' => $category_id));
        $current = $current_query->row_array();

        if ($direction == 'up' || $direction == 'down') {

            if ($direction == 'up')
                $ST = array(1 => '+1', 2 => -1, 3 => '-1');
            elseif ($direction == 'down')
                $ST = array(1 => '-1', 2 => +1, 3 => '+1');

            $this->db->set('vertical_pos', "vertical_pos$ST[1]", FALSE);
            $this->db->where(array('user_id' => $current['user_id'], 'horizontal_pos' => $current['horizontal_pos'], 'vertical_pos' => $current['vertical_pos'] + $ST[2]));
            $check1 = $this->db->update('category');

            $this->db->set('vertical_pos', "vertical_pos$ST[3]", FALSE);
            $this->db->where('id', $category_id);
            $check2 = $this->db->update('category');
        } elseif ($direction == 'right' || $direction == 'left') {

            if ($direction == 'right')
                $ST = '1';
            elseif ($direction == 'left')
                $ST = '2';

            $this->db->set('vertical_pos', 'vertical_pos-1', FALSE);
            $this->db->where(array('user_id' => $current['user_id'], 'horizontal_pos' => $current['horizontal_pos'], 'vertical_pos > ' => $current['vertical_pos']));
            $check1 = $this->db->update('category');

            $this->db->set(array('vertical_pos' => $this->set_category_vertical_position($current['user_id'], $ST), 'horizontal_pos' => $ST));

            $this->db->where('id', $category_id);
            $check2 = $this->db->update('category');
        }

        ($check1 && $check2) ? $return = 1 : $return = 0;
        return $return;
    }

    // check if the given id is available in database and also the id is for the current user or not.
    // this function is used to validate a given id of a category or a bookmark in an ajax request.
    public function is_id_acceptable($table, $id, $current_user) {
        $result_query = $this->db->get_where($table, array('id' => $id));
        $result = $result_query->row_array();
        if (isset($result) && $result['user_id'] == $current_user['id'])
            return 1;
        else
            return 0;
    }

}
