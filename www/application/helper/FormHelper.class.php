<?php

namespace Devlabs\App;

/**
 * Class FormHelper
 * @package Devlabs\App
 */
class FormHelper
{
    /**
     * Get the previous value of a POST item
     *
     * @param $item
     * @return string
     */
    public static function formPrevValue($item)
    {
        if (!empty($_POST[$item])) {
            return htmlspecialchars($_POST[$item]);
        }

        return '';
    }

    /**
     * Check if form with a given form_name has been submitted via POST
     *
     * @param $form_name
     * @return bool
     */
    public static function isFormSubmitted($form_name)
    {
        return isset($_POST['form_name']) && $_POST['form_name'] === $form_name;
    }
}
