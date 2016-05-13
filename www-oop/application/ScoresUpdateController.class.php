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
         * Set the DB to manual commit mode and begin transaction
         */
        $GLOBALS['db']->startTransaction();

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

                // get the points from the prediction
                $predictionPoints = $prediction->calculatePoints($match);

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

        /**
         * End the transaction by committing and
         * set the DB to autocommit mode again
         */
        $GLOBALS['db']->endTransaction();

        header("Location: index.php");
    }
}
