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

        return new view($this->view, $data);
    }
}
