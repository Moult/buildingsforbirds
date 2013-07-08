<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Comment_Add implements Cavis\Core\Usecase\Comment\Add\Repository
{
    /**
     * Saves a comment message associated with an image id.
     *
     * Example:
     * $repository->save_comment('Hello', 42);
     *
     * @param string $message  The comment message
     * @param int    $image_id The unique ID associated with the image
     *
     * @return void
     */
    public function save_comment($message, $image_id)
    {
        DB::insert('comments', array('message', 'image'))
            ->values(array($message, $image_id))
            ->execute();
    }

    /**
     * Checks whether or not an image exists
     *
     * @param int $image_id The unique ID associated with the image
     *
     * @return bool TRUE is image exists, else FALSE
     */
    public function check_image_exists($image_id)
    {
        return (bool) DB::select('id')
            ->from('images')
            ->where('id', '=', $image_id)
            ->limit(1)
            ->execute()
            ->count();
    }
}
