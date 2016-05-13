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

        /**
         * Get a list of the finished matches
         * for which there are NOT SCORED predictions
         */
        $matches = new MatchCollection();
        $matchesList = $matches->getFinishedNotScored();

        // get a list of all active users
        $users = new UserCollection();
        $userList = $users->getAllConfirmed();

        // iterate for each user
        foreach ($userList as $user) {
            /**
             * Get the list of scores (tournament + points) for the user
             */
            unset($scores);
            unset($scoresList);
            $scores = new ScoreCollection();
            $scoresList = $scores->getByUser($user);

            /**
             * Get a list of NOT SCORED predictions by the user
             * for matches with final score set
             */
            unset($predictions);
            unset($predictionsList);
            $predictions = new PredictionCollection();
            $predictionsList = $predictions->getFinishedNotScored($user);

            // iterate on all of the user's predictions
            foreach ($predictionsList as $prediction) {
                /**
                 * Get corresponding match for the current prediction
                 */
                unset($match);
                $match = $matchesList[$prediction->matchId];

                // calculate the points from the prediction
                if (($match->homeGoals === $prediction->homeGoals) && ($match->awayGoals === $prediction->awayGoals)) {
                    $predictionPoints = POINTS_EXACT;
                } else if (
                    ($match->homeGoals > $match->awayGoals && $prediction->homeGoals > $prediction->awayGoals) ||
                    ($match->homeGoals < $match->awayGoals && $prediction->homeGoals < $prediction->awayGoals) ||
                    ($match->homeGoals === $match->awayGoals && $prediction->homeGoals === $prediction->awayGoals)
                ) {
                    $predictionPoints = POINTS_OUTCOME;
                } else {
                    $predictionPoints = 0;
                }

                /**
                 * Update the prediction in the DB
                 * by setting the points gained and the score_added flag to 1
                 */
                $prediction->setPoints($predictionPoints);

                /**
                 * If the predictions's gained points are more than 0 (zero)
                 * then update the user score for the prediction's tournament
                 */
                if ($predictionPoints > 0) {
                    unset($score);
                    $score = $scoresList[$match->tournamentId];
                    $score->updatePoints($predictionPoints);
                }
            }
        }

        header("Location: index.php");
    }
}
