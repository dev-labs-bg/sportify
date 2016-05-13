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

                if ( ($row['m_home_goals'] == $row['p_home_goals']) && ($row['m_away_goals'] == $row['p_away_goals']) ) {
                    $prediction_points = POINTS_EXACT;
                } else if ( ($row['m_home_goals'] > $row['m_away_goals'] && $row['p_home_goals'] > $row['p_away_goals'])
                    || ($row['m_home_goals'] < $row['m_away_goals'] && $row['p_home_goals'] < $row['p_away_goals'])
                    || ($row['m_home_goals'] == $row['m_away_goals'] && $row['p_home_goals'] == $row['p_away_goals']) ) {
                    $prediction_points = POINTS_OUTCOME;
                } else {
                    $prediction_points = 0;
                }


            }
        }

        header("Location: index.php");
    }
}
