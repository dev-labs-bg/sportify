<?php

namespace Devlabs\App;

/**
 * Class HistoryController
 * @package Devlabs\App
 */
class ProfileController extends AbstractController
{
    /**
     * Default action method for rendering the profile page logic
     *
     * @return view
     */
    public function index()
    {

        /**
         * Data array for keeping the variables which will be passed to the view
         */
        $data = array();

        /**
         * Load the data for the current user into an object
         */
        $user = new User();
        $user->loadByEmail($_SESSION['email']);

        /**
         * Check if 'form_name' with value 'profile_change' has been submitted
         */
        if (SysHelper::isFormSubmitted('profile_change')) {
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['password_confirm'];

            change_userdata($email, $first_name, $last_name);

            if ( empty($password) && empty($password_confirm) ) {
                $status_message = 'You have successfully changed your profile details.';
            } else if ( validate_password($password, $password_confirm, $status_message) ){
                change_password($email, $password);
            }

            $data['status_message'] = $status_message;
        }

        return new view($this->view, $data);
    }
}
