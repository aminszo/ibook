<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Users Model
  |
  | this model works with 'users' and 'inactive_users' tables of the database.
  | and this model is loaded automatically on all controllers (config/autoload.php).
 */

class Users extends CI_Model {

    function __construct() { // load database for all functions.
        parent::__construct();
        $this->load->database();
    }

    public function get_user_info($identity_element, $identity_value) { // get all information of a user with a identity parameter.
        $user_info = $this->db->get_where('users', array($identity_element => $identity_value));
        return $user_info->row_array();
    }

    public function get_inactive_user_info($identity_element, $identity_value) { // get all information of a inactive user with a identity parameter.
        $user_info = $this->db->get_where('inactive_users', array($identity_element => $identity_value));
        return $user_info->row_array();
    }

    public function register_new_user($information) { // records the information of a new user in the inactive_users table of database.
        return $this->db->insert('inactive_users', $information);
    }

    // receives id of an inactive user account, and activate it(move information of that user from 'inactive_users' table to
    // 'users' table), and delete user account information from 'inactive_users' table.
    public function activate_new_user($inactive_user_id) {
        $inactive_user = $this->get_inactive_user_info('id', $inactive_user_id);
        $information = array(
            'username' => $inactive_user['username'],
            'password' => $inactive_user['password'],
            'email' => $inactive_user['email'],
            'firstname' => $inactive_user['firstname'],
            'lastname' => $inactive_user['lastname'],
            'signup_date' => time(),
        );
        $this->db->delete('inactive_users', array('id' => $inactive_user['id']));
        return $this->db->insert('users', $information);
    }

    public function update_user($information, $id) { // updates a user account by the given information.
        return $this->db->update('users', $information, array('id' => $id));
    }

    public function delete_old_inactive_users() { // delete user accounts that have been inactive for more than one week.
        $oneWeek = time() - (7 * 24 * 60 * 60);
        $this->db->delete('inactive_users', array('hash_date < ' => $oneWeek));
    }

}
