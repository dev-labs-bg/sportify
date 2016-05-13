<?php

namespace Devlabs\App;

class ScoresUpdateController extends AbstractController
{
    /**
     * Default action method for rendering the scores update page logic
     *
     * @return view
     */
    public function index()
    {

        /**
         * Data array for keeping the variables which will be passed to the view
         */
        $data = array();

        $matches = new MatchCollection();
        $matchesList = $matches->getFinishedNotScored();

        $users = new UserCollection();
        $userList = $users->getAllConfirmed();

        foreach ($userList as $user) {
            unset($predictions);
            $predictions = new PredictionCollection();
            $predictionsList = $predictions->getFinishedNotScored($user);

            // iterate on all of the user's predictions
            foreach ($predictionsList as $prediction) {
                unset($match);
                $match = $matchesList[$prediction->matchId];


            }
        }

        header("Location: index.php");
    }
}
