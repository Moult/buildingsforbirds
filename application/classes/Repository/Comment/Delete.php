<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Comment_Delete implements Cavis\Core\Usecase\Comment\Delete\Repository
{
    /**
     * Deletes a comment
     *
     * @param int $comment_id The unique ID associated with the comment
     *
     * @return void
     */
    public function delete_comment($comment_id)
    {
        DB::delete('comments')
            ->where('id', '=', $comment_id)
            ->execute();
    }
}
