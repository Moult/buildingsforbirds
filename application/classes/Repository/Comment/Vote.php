<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Comment_Vote implements Cavis\Core\Usecase\Comment\Vote\Repository
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
    public function has_existing_vote($ip, $comment_id)
    {
        return (bool) DB::select('ip')
            ->from('commentvotes')
            ->where('ip', '=', $ip)
            ->where('comment', '=', $comment_id)
            ->limit(1)
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
    public function save_vote($ip, $comment_id)
    {
        DB::insert('commentvotes', array('ip', 'comment'))
            ->values(array($ip, $comment_id))
            ->execute();
    }
}
