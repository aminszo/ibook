<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Login Page Controller
  |
  | in this controller we will receive the information that user has submitted to login
  | form, if the username and password is correct, user will log in and redirect to
  | dashboard, otherwise we will display proper error(s) and display login form again.
 */

class Login extends CI_Controller {

    public function index() {

        $this->actions->ie_block();

        $this->actions->need_logout();

        $this->actions->initial_data($data, 'ورود', 1, 0);

        $this->actions->start_session();

        $data['just_logout'] = $this->actions->access_status('just_logout'); //set just_logout status (whether user is redirected from logout page or not).

        if ($this->input->post('form_status', TRUE)) { // if the form is submitted by user, process the form.
            $errors = 0;

            //receive submitted information.
            $username = $this->actions->receive_input('username');
            $password = $this->actions->receive_input('password');

            if (empty($username)) { //check whether the username field is empty. if it is empty, set an error and error message.
                $errors += 1;
                $login_errors['username'] = 'نام کاربری را وارد نکرده اید.';
            }
            if (empty($password)) { //check whether the password field is empty. if it is empty, set an error and error message.
                $errors += 1;
                $login_errors['password'] = 'کلمه عبور را وارد نکرده اید.';
            }

            if ($errors == 0) { //if we have no errors , continue.
                $user_info = $this->users->get_user_info('username', $username); //get user information from database.

                if (!isset($user_info)) { //check whether a user with this username is available or not.
                    $inactive_user = $this->users->get_inactive_user_info('username', $username); //get user information from 'inactive users' table.
                    if (isset($inactive_user)) {
                        $login_errors['username'] = 'این حساب کاربری هنوز فعال نشده است.<br>برای فعالسازی باید ایمیل خود را تایید کنید.';
                    } else {
                        $login_errors['username'] = 'نام کاربری اشتباه است.';
                    }
                } elseif (!password_verify($password, $user_info['password'])) { //check whether the password is correct or not.
                    //password_verify() verifies that $password matches hashed password that stored in database.
                    $login_errors['password'] = 'کلمه عبور اشتباه است.';
                } else { //if username and password is correct, login user(store user information in session), and redirect to dashboard.
                    $this->actions->login_user($user_info);
                    redirect(base_url() . 'dashboard');
                }
            }

            $data['login_errors'] = $login_errors; //store errors messages in $data to send them to view file.
        }

        //load view files.
        $this->load->view('header', $data);
        $this->load->view('login', $data);
        $this->load->view('footer', $data);
    }

}
