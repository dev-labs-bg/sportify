<?php

namespace Devlabs\SportifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $footballAPI = $this->get('app.data_updates.fetchers.football_data');
        $footballAPI->setApiToken('896fa7a2adc1473ba474c6eb4e66cb4c');
        $data = $footballAPI->fetchFixturesByTournamentAndMatchDay(426,1);
//        $data = $footballAPI->fetchTeamsByTournament(426)->teams[17];
        var_dump($data); die;

        return $this->redirectToRoute('standings_index');
    }
}
