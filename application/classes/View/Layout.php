<?php
defined('SYSPATH') OR die('No direct script access.');

/**
 * Sets up partials, essentially a core file for KOstache.
 */
class View_Layout
{
    private $session;

    public function __construct()
    {
        $this->session = Session::instance();
    }

    /**
     * The base URL of the website.
     *
     * @return string
     */
    public function baseurl()
    {
        return URL::base();
    }

    /**
     * The current page that we are on
     *
     * @return string
     */
    public function currenturl()
    {
        return $this->request->uri();
    }

    /**
     * Checks whether or not a notification exists
     *
     * @return bool
     */
    public function has_notification()
    {
        return (bool) $this->session->get('notification', FALSE);
    }

    /**
     * Displays any notification set by the system
     *
     * @return string
     */
    public function notification()
    {
        return $this->session->get_once('notification');
    }
}
