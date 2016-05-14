<?php

namespace Devlabs\App;

/**
 * Class TournamentsController
 * @package Devlabs\App
 */
class TournamentsController extends AbstractController
{
    /**
     * Default action method for rendering the tournaments page logic
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
         * Create object for holding the tournaments data
         */
        $tournaments = new TournamentCollection();

        /**
         * Check if there is a POST tournaments array set,
         * and depending on the form submitted, join or leave tournaments
         */
        if (isset($_POST['tournaments'])) {
            if (FormHelper::isFormSubmitted('tournaments_join')) {
                $tournaments->join($user, $_POST['tournaments']);
            } else if (FormHelper::isFormSubmitted('tournaments_leave')) {
                $tournaments->leave($user, $_POST['tournaments']);
            }
        }

        /**
         * Store the joined and available tournaments data
         * in the data array which will be passed to the view
         */
        $data['tournaments_joined'] = $tournaments->getJoined($user);
        $data['tournaments_available'] = $tournaments->getAvailable($user);

        return new view($this->view, $data);
    }
}
