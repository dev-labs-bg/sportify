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
        if (FormHelper::isFormSubmitted('profile_change')) {
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['password_confirm'];

            $user->changeFirstName($firstName);
            $user->changeLastName($lastName);

            if (empty($password) && empty($password_confirm)) {
                $status_message = 'You have successfully changed your profile details.';
            } else if (UserAuthHelper::validatePasswordData($password, $passwordConfirm, $status_message)) {
                $user->changePassword($password);
            }

            // load the updated user data
            $user->loadByEmail($_SESSION['email']);

            // Store the status message in the data array which will be passed to the view
            $data['status_message'] = $status_message;
        }

        // Store the user data in the data array which will be passed to the view
        $data['user'] = $user;

        return new view($this->view, $data);
    }
}
