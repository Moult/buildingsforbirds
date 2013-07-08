<?php
defined('SYSPATH') OR die('No direct script access.');

/**
 * Shows site index / homepage.
 *
 * The homepage allows the user to browse the list of popular images.
 */
class View_Image_Browse extends View_Layout
{
    public function images()
    {
        $array = array();
        foreach ($this->images as $image)
        {
            $info = array();
            $info['id'] = $image->id;
            $info['name'] = $image->name;
            $info['votes'] = $image->number_of_votes;
            $info['has_comments'] = (bool) count($image->comments);
            $info['file'] = $this->baseurl().'uploads/'.basename($image->file).'.thumb.png';
            foreach ($image->comments as $comment)
            {
                $message = substr($comment->message, 0, 30);
                $message .= (strlen($comment->message) > 30) ? '...' : '';
                $info['comments'][]['comment'] = $message;
            }
            $array[] = $info;
        }
        return $array;
    }
}
