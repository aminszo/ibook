<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | SignUp Page Controller
  |
  | in this controller we will receive the value of the fields of 'signup' form, check them one by one and if they had the
  | correct conditions, sign user up and send a verification email to user's email, otherwise, display proper error message(s).
  | after a successful signup, when user clicks on verification link of its email, we check the link and if it is correct, we
  | activate user account, otherwise, display proper error message.
  |
  | ATTENTION: email verificattion step is temporary disabled and skipped in user signup process. Because of email sending problems.
  |            it means we dont verify submitted email address and immediately register user with submitted email.
 */

class Signup extends CI_Controller {

    public function index() {

        $this->actions->ie_block();

        $this->actions->need_logout();

        $this->actions->initial_data($data, 'ثبت نام', 1, 0);

        // initial temporary values to fill the field of signup form.
        $temp_values = array('username' => null, 'email' => null, 'firstname' => null, 'lastname' => null);

        if ($this->actions->receive_input('form_status')) { // if the form is submitted by user, process the form.
            $errors = 0;

            //check username field value.
            if (($item = $this->actions->receive_input('username')) === NULL) {
                $errors = +1;
                $signup_errors['username'] = 'نام کاربری را وارد کنید.';
            } elseif (!preg_match("/^[0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_]+$/", $item)) {
                $errors = +1;
                $signup_errors['username'] = 'نام کاربری فقط میتواند شامل حروف لاتین ، اعداد و "_" باشد.';
            } elseif ($this->actions->check_length($item, 4, 15) !== 0) {
                $errors = +1;
                $signup_errors['username'] = 'نام کاربری باید بین 4 تا 15 کاراکتر باشد.';
            } elseif (!$this->actions->check_unique_user('username', $item)) {
                $errors = +1;
                $signup_errors['username'] = 'این نام کاربری قبلا ثبت شده است.';
            } else {
                $username = $temp_values['username'] = $item;
            }

            //check password field value.
            if (($item = $this->actions->receive_input('password')) === NULL) {
                $errors = +1;
                $signup_errors['password'] = 'کلمه عبور را وارد کنید.';
            } elseif (!preg_match("/^[0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@#$^()_]+$/", $item)) {
                $errors = +1;
                $signup_errors['password'] = 'کلمه عبور فقط میتواند شامل حروف لاتین ، اعداد و کاراکتر های خاص(~!@#$^()_) باشد.';
            } elseif ($this->actions->check_length($item, 6, 15) !== 0) {
                $errors = +1;
                $signup_errors['password'] = 'کلمه عبور باید بین 6 تا 15 کاراکتر باشد.';
            } elseif (($item2 = $this->actions->receive_input('re_password')) !== $item) {
                $errors = +1;
                $signup_errors['password'] = 'کلمه عبور و تکرار آن با هم منطبق نیستند.';
            } else {
                $password = $item;
            }

            //check email field value.
            if (($item = $this->actions->receive_input('email')) === NULL) {
                $errors = +1;
                $signup_errors['email'] = 'نشانی ایمیل را وارد کنید.';
            } elseif (!filter_var($item, FILTER_VALIDATE_EMAIL)) {
                $errors = +1;
                $signup_errors['email'] = 'ایمیل وارد شده صحیح نیست.';
            } elseif ($this->actions->check_length($item, 0, 60) !== 0) {
                $errors = +1;
                $signup_errors['email'] = 'ایمیل نباید بیشتر از 60 کاراکتر باشد';
            } elseif (!$this->actions->check_unique_user('email', $item)) {
                $errors = +1;
                $signup_errors['email'] = 'کاربری با این ایمیل قبلا ثبت شده است.';
            } else {
                $email = $temp_values['email'] = $item;
            }

            //check firstname field value.
            if (($item = $this->actions->receive_input('firstname')) === NULL) {
                $errors = +1;
                $signup_errors['firstname'] = 'نام کوچک را وارد کنید.';
            } elseif (!preg_match("/^[ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی ]+$/", $item)) {
                $errors = +1;
                $signup_errors['firstname'] = 'نام کوچک باید فارسی باشد.';
            } elseif ($this->actions->check_length($item, 0, 30) !== 0) {
                $errors = +1;
                $signup_errors['firstname'] = 'نام کوچک نباید بیشتر از 30 کاراکتر باشد.';
            } else {
                $firstname = $temp_values['firstname'] = $item;
            }

            //check lastname field value.
            if (($item = $this->actions->receive_input('lastname')) === NULL) {
                $errors = +1;
                $signup_errors['lastname'] = 'نام خانوادگی را وارد کنید.';
            } elseif (!preg_match("/^[ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی ]+$/", $item)) {
                $errors = +1;
                $signup_errors['lastname'] = 'نام خانوادگی باید فارسی باشد.';
            } elseif ($this->actions->check_length($item, 0, 30) !== 0) {
                $errors = +1;
                $signup_errors['lastname'] = 'نام خانوادگی نباید بیشتر از 30 کاراکتر باشد.';
            } else {
                $lastname = $temp_values['lastname'] = $item;
            }

            if ($errors == 0) { //if we have no errors , continue.
                $user_information = array(
                    'username' => $username,
                    // we hash password for increase security.
                    'password' => password_hash($password, PASSWORD_BCRYPT, array('cost' => 6)),
                    'email' => $email,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    // a random hash, for use in varification email.
                    'hash' => $this->actions->get_random_hash(),
                    'hash_date' => time(),
                );

                if ($this->users->register_new_user($user_information)) { // if the user registration was successful...
                    $this->actions->start_session();
                    $this->actions->send_access_status('just_signup');

                    $user_info = $this->users->get_inactive_user_info('username', $user_information['username']);
                    // store current inactive user information to session, for sending verificatio email.
                    $_SESSION['temp_user'] = array('id' => $user_info['id'], 'username' => $user_info['username'], 'pass' => $password);

                    redirect('signup/second_step');
                } else
                    $signup_errors['insert_fail'] = 'خطایی هنگام ثبت اطلاعات رخ داده است. لطفا دوباره تلاش کنید.';
            }
            $data['signup_errors'] = $signup_errors; //store signup_errors in $data.
        }

        $data['temp_values'] = $temp_values; //store temp_values in $data.
        //load view files.
        $this->load->view('header', $data);
        $this->load->view('signup', $data);
        $this->load->view('footer', $data);
    }

    public function second_step() {

        $this->actions->ie_block();

        $this->actions->need_logout();

        // if 'just_signup' flag doesn't exist, or temp_user information doesn't exist, redirect to home.
        if (!$this->actions->access_status('just_signup') || !isset($_SESSION['temp_user']))
            redirect(base_url() . 'home');

        $this->actions->initial_data($data, 'ثبت نام');

        // ***** ATTENTION: email varification step is temporary disabled and skipped. *****
        /*
          // send verification email to user.
          $result = $this->actions->send_account_activation_email();

          if ($result == 'success') { //if email was successfully sent, redirect to success page.
          $this->actions->send_access_status('email_send_success');
          redirect('signup/success');
          } else { //if email was failed...
          }
         */

        // TEMPORARY PART - activate and login the new inactive user, and remove temp_user from session.
        $temp_user = $_SESSION['temp_user'];
        unset($_SESSION['temp_user']);

        $this->users->activate_new_user($temp_user['id']);

        $this->actions->send_access_status('signup_completed');

        $user_information = $this->users->get_user_info('username', $temp_user['username']);

        $this->actions->login_user($user_information);

        redirect(base_url() . 'signup/complete');
        // END OF "TEMPORARY PART"
    }

    public function success() {

        $this->actions->ie_block();

        $this->actions->need_logout();

        // if 'just_signup' flag doesn't exist, redirect to home.
        if (!$this->actions->access_status('email_send_success'))
            redirect(base_url() . 'home');

        $this->actions->initial_data($data, 'ثبت نام');

        // set status to 'success', to display success message in 'signup_status' view.
        $data['status'] = 'success';

        $this->load->view('header', $data);
        $this->load->view('signup_status', $data);
        $this->load->view('footer', $data);

        $this->users->delete_old_inactive_users(); // delete old inactive users, every time that a new user signs up.
    }

    public function verify() {

        $this->actions->ie_block();

        $this->actions->need_logout();

        // extract information from the link (using GET).
        $id = $this->input->get('i', true);
        $username = $this->input->get('u', true);
        $hash = $this->input->get('h', true);

        // if we haven't information that we need, redirect to home.
        if (empty($id) || empty($username) || empty($hash))
            redirect(base_url() . "home");

        $user_info = $this->users->get_inactive_user_info('id', $id); // get inactive user's information from database.

        if (isset($user_info) && $user_info['username'] === $username && $user_info['hash'] === $hash) { // if id and username and hash are match...
            if ((time() - $user_info['hash_date']) > (12 * 60 * 60)) { // if the link generating time is longer than 12 hours...
                $this->actions->send_access_status('signup_verify_error');
                $this->actions->send_access_status('verify_error', 'لینک منقضی شده است.');
                redirect(base_url() . 'signup/error');
            } else {
                if ($this->users->activate_new_user($user_info['id'])) { //if everything is correct, activate and login user.
                    $this->actions->send_access_status('signup_completed');
                    $user_information = $this->users->get_user_info('username', $user_info['username']);
                    $this->actions->login_user($user_information);
                    redirect(base_url() . 'signup/complete');
                } else { // if activating has failed in database...
                    $this->actions->send_access_status('signup_verify_error');
                    $this->actions->send_access_status('verify_error', 'خطایی در تایید ایمیل شما رخ داده است. لظفا مجددا روی لینک فعالسازی در ایمیل خود کلیک کنید.');
                    redirect(base_url() . 'signup/error');
                }
            }
        } else {
            $this->actions->send_access_status('signup_verify_error');
            $this->actions->send_access_status('verify_error', 'لینک نامعتبر است');
            redirect(base_url() . 'signup/error');
        }
    }

    public function complete() {

        $this->actions->ie_block();

        $this->actions->need_login();

        // if 'signup_completed' flag doesn't exist, redirect to home.
        if (!$this->actions->access_status('signup_completed'))
            redirect(base_url() . 'home');

        $this->actions->initial_data($data, 'خوش آمدید');

        // set status to 'finish', to display finish message in 'signup_status' view.
        $data['status'] = 'finish';

        $this->load->view('header', $data);
        $this->load->view('signup_status', $data);
        $this->load->view('footer', $data);
    }

    public function error() {

        $this->actions->ie_block();

        $this->actions->need_logout();

        // if 'signup_verify_error' flag doesn't exist, redirect to home.
        if (!$this->actions->access_status('signup_verify_error'))
            redirect(base_url() . 'home');

        $this->actions->initial_data($data, 'خطا');

        // store the given verify error in $data and also set status to 'error', to display the error message in 'signup_status' view.
        $data['verify_error'] = $this->actions->access_status('verify_error');
        $data['status'] = 'error';

        $this->load->view('header', $data);
        $this->load->view('signup_status', $data);
        $this->load->view('footer', $data);
    }

}
