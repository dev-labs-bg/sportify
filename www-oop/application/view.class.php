<?php

namespace devlabs\app;

/**
 * Class view
 */
class view
{
    /**
     * Property for keeping the page template to be loaded
     *
     * @var
     */
    private $page;

    /**
     * Property for keeping the data to be passed to the page template
     *
     * @var array
     */
    private $data = array();

    /**
     * Set the $page and $data properties on instantiation
     *
     * view constructor.
     * @param $page
     * @param array $data
     */
    public function __construct($page, $data = array())
    {
        $this->page = $page;
        $this->data = $data;
    }

    /**
     * Load the view as indicated by the $page property
     * while passing the data stored in the $data property
     */
    public function load()
    {
        if ( $this->data ) {
            extract($this->data);
        }

        include VIEW_DIR . "header.php";
        include VIEW_DIR . "{$this->page}.view.php";
        include VIEW_DIR . "footer.php";
    }
}
