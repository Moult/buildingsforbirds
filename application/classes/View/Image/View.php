<?php
defined('SYSPATH') OR die('No direct script access.');

/**
 * Shows single image
 */
class View_Image_View extends View_Layout
{
    /**
     * The name of the building currently being viewed
     *
     * @return string
     */
    public function building_name()
    {
        return $this->image->name;
    }

    /**
     * The file path of the image
     *
     * @return string
     */
    public function file()
    {
        return substr($this->image->file, strlen(DOCROOT));
    }

    /**
     * The number of votes of the image
     *
     * @return int
     */
    public function votes()
    {
        return $this->image->number_of_votes;
    }

    public function has_comments()
    {
        return (bool) count($this->image->comments);
    }

    public function comments()
    {
        $array = array();
        foreach ($this->image->comments as $comment)
        {
            $array[] = array(
                'id' => $comment->id,
                'comment' => $comment->message
            );
        }
        return $array;
    }

    public function id()
    {
        return $this->image->id;
    }
}
