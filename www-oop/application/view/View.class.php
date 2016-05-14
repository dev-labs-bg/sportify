<?php

namespace Devlabs\App;

/**
 * Class view
 */
class View
{
    /**
     * Property for keeping the page template to be loaded
     *
     * @var string
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

    /**
     * Static method for loading a template file
     * by replacing specific strings in it
     *
     * @param $view_file
     * @param $str_search
     * @param $str_replace
     * @return mixed
     */
    public static function loadTemplate($viewFile, $strSearch, $strReplace)
    {
        $filePath = VIEW_DIR . $viewFile;
        $viewData = file_get_contents($filePath);

        return str_replace($strSearch, $strReplace, $viewData);
    }
}
