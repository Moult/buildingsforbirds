<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_Comment extends Controller_Core
{
    public function action_delete()
    {
        $config = Kohana::$config->load('admin');
        if ($this->request->param('password') !== $config->get('password'))
            return FALSE;

        $comment = new Cavis\Core\Data\Comment;
        $comment->id = $this->request->param('comment_id');
        $comment_delete = new Repository_Comment_Delete;

        $data = ['comment' => $comment];
        $repositories = ['comment_delete' => $comment_delete];

        $usecase = new Cavis\Core\Usecase\Comment\Delete($data, $repositories);
        $usecase->fetch()->interact();
    }

    public function action_vote()
    {
        $user = new Cavis\Core\Data\User;
        $comment = new Cavis\Core\Data\Comment;
        $comment_vote = new Repository_Comment_Vote;

        $user->ip = $_SERVER['REMOTE_ADDR'];
        $comment->id = $this->request->param('comment_id');

        $data = ['comment' => $comment, 'user' => $user];
        $repositories = ['comment_vote' => $comment_vote];

        $usecase = new Cavis\Core\Usecase\Comment\Vote($data, $repositories);
        try
        {
            $usecase->fetch()->interact();
            $this->view->status = 'success';
        }
        catch (Cavis\Core\Exception\Authorisation $e)
        {
            $this->view->status = 'fail';
        }
        $this->layout = 'plain';
    }
}
