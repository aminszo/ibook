<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | Account Page Controller
  |
  | in account page, user can edit account information.
  | in this controller we will receive the information that user has submitted to 'edit account' form, check them one by one and if
  | they had the correct conditions, we update user profile, and display success message, otherwise, display proper error message.
 */

class Account extends CI_Controller {

    public function index() {

        $this->actions->ie_block();

        $this->actions->need_login();

        $this->actions->initial_data($data, 'ویرایش حساب');

        $current_user = $this->actions->get_current_user(); //get current user information.

        if ($this->input->post('form_status', TRUE)) { //if the form is submitted by user, process the form.
            //initial errors and logs variables.
            $errors = 0;
            $edit_errors = array();
            $edit_logs = array();

            //check passsword field value.
            if (($item = $this->actions->receive_input('password')) === NULL) { //if password field is empty do nothing and skip.
            } else {
                $user_info1 = $this->users->get_user_info('id', $current_user['id']);
                $item1 = $this->actions->receive_input('old_password');
                if (!password_verify($item1, $user_info1['password'])) {
                    $errors = +1;
                    $edit_errors['password'] = 'کلمه عبور فعلی صحیح نیست.';
                } elseif (!preg_match("/^[0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@#$^()_]+$/", $item)) {
                    $errors = +1;
                    $edit_errors['password'] = 'پسورد فقط میتواند شامل حروف لاتین ، اعداد و کاراکتر های خاص(~!@#$^()_) باشد.';
                } elseif ($this->actions->check_length($item, 6, 15) !== 0) {
                    $errors = +1;
                    $edit_errors['password'] = 'پسورد باید بین 6 تا 15 کاراکتر باشد.';
                } elseif (($item2 = $this->actions->receive_input('re_password')) !== $item) {
                    $errors = +1;
                    $edit_errors['password'] = 'پسورد و تکرار آن با هم منطبق نیستند.';
                } else {
                    $user_information['password'] = password_hash($item, PASSWORD_BCRYPT, array('cost' => 6));
                }
            }

            //check firstname field value.
            if (($item = $this->actions->receive_input('firstname')) === NULL) {
                $errors = +1;
                $edit_errors['firstname'] = 'نام کوچک را وارد کنید.';
            } elseif ($item === $current_user['firstname']) {
                
            } elseif (!preg_match("/^[ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی ]+$/", $item)) {
                $errors = +1;
                $edit_errors['firstname'] = 'نام کوچک باید فارسی باشد.';
            } elseif ($this->actions->check_length($item, 0, 30) !== 0) {
                $errors = +1;
                $edit_errors['firstname'] = 'نام کوچک نباید بیشتر از 30 کاراکتر باشد.';
            } else {
                $user_information['firstname'] = $item;
            }

            //check lastname field value.
            if (($item = $this->actions->receive_input('lastname')) === NULL) {
                $errors = +1;
                $edit_errors['lastname'] = 'نام خانوادگی را وارد کنید.';
            } elseif ($item === $current_user['lastname']) {
                
            } elseif (!preg_match("/^[ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی ]+$/", $item)) {
                $errors = +1;
                $edit_errors['lastname'] = 'نام خانوادگی باید فارسی باشد.';
            } elseif ($this->actions->check_length($item, 0, 30) !== 0) {
                $errors = +1;
                $edit_errors['lastname'] = 'نام خانوادگی نباید بیشتر از 30 کاراکتر باشد.';
            } else {
                $user_information['lastname'] = $item;
            }

            if ($errors == 0) { //if we have no errors , continue.
                if (!isset($user_information)) { //if no changes have been made, display a message and skip.
                    $edit_logs['no_change'] = array('message' => 'تغییری ثبت نشده است.', 'class' => 'warning');
                } else { //if we have change(s)...
                    if ($this->users->update_user($user_information, $current_user['id'])) {
                        $user_info = $this->users->get_user_info('username', $current_user['username']);
                        $this->actions->login_user($user_info);
                        $edit_logs['change_success'] = array('message' => 'تغییرات با موفقیت ثبت شد.', 'class' => '');
                    } else {
                        $edit_logs['insert_fail'] = array('message' => 'خطایی هنگام ثبت اطلاعات رخ داده است. لطفا دوباره تلاش کنید.', 'class' => 'warning');
                    }
                }
                $data['edit_logs'] = $edit_logs; //store edit_logs in $data.
            } else {
                $data['edit_errors'] = $edit_errors; //store edit_errors in $data.
            }
        }

        $data['current_user'] = $this->actions->get_current_user(); //store current user information in $data.
        //load view files.
        $this->load->view('header', $data);
        $this->load->view('edit_account', $data);
        $this->load->view('footer', $data);
    }

}
