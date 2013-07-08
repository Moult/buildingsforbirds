<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Image_Vote implements Cavis\Core\Usecase\Image\Vote\Repository
{
    /**
     * Checks whether or not a vote already exists.
     *
     * Example:
     * $repository->has_existing_vote('127.0.0.1', 42);
     *
     * @param string $ip         The ip address of the voter
     * @param int    $comment_id The unique ID associated with the comment
     *
     * @return bool TRUE if vote exists, else FALSE
     */
    public function has_existing_vote($ip, $image_id)
    {
        return (bool) DB::select('ip')
            ->from('imagevotes')
            ->where('ip', '=', $ip)
            ->where('image', '=', $image_id)
            ->execute()
            ->count();
    }

    /**
     * Saves a vote record in the database.
     *
     * Example:
     * $repository->has_existing_vote('127.0.0.1', 42);
     *
     * @param string $ip         The ip address of the voter
     * @param int    $comment_id The unique ID associated with the comment
     *
     * @return void
     */
    public function save_vote($ip, $image_id)
    {
        DB::insert('imagevotes', array('ip', 'image'))
            ->values(array($ip, $image_id))
            ->execute();
    }
}
