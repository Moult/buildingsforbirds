<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_Image extends Controller_Core
{
    public function action_browse()
    {
        if ($this->request->method() == HTTP_Request::POST)
        {
            $post = $this->request->post();
            $image = new Cavis\Core\Data\Image;
            $file = new Cavis\Core\Data\File;
            $image->name = $post['name'];
            $file->name = $_FILES['building']['name'];
            $file->tmp_name = $_FILES['building']['tmp_name'];
            $file->mimetype = $_FILES['building']['type'];
            $file->filesize_in_bytes = $_FILES['building']['size'];
            $file->error_code = $_FILES['building']['error'];
            $image->file = $file;
            $image_add = new Repository_Image_Add;
            $photoshopper = new Tool_Photoshopper;
            $validator = new Tool_Validator;

            $data = [ 'image' => $image ];
            $repositories = [ 'image_add' => $image_add ];
            $tools = [ 'photoshopper' => $photoshopper, 'validator' => $validator ];

            $usecase = new Cavis\Core\Usecase\Image\Add($data, $repositories, $tools);
            $session = Session::instance();
            try
            {
                $result = $usecase->fetch()->interact();
                $session->set('notification', 'Thanks! Your building has been added successfully.');

                HTTP::redirect(Route::get('view')->uri(array(
                    'image_id' => $result
                )));
            }
            catch (Cavis\Core\Exception\Validation $e)
            {
                if (in_array('name', $e->get_errors()))
                    return $session->set('notification', 'Submission failed. You need to fill up a building name.');
                else
                    return $session->set('notification', 'Submission failed. Please check you are uploading a valid png or jpg file less than 1MB.');
            }
        }

        $repositories = ['image_browse' => new Repository_Image_Browse];
        $usecase = new Cavis\Core\Usecase\Image\Browse($repositories);
        $this->view->images = $usecase->fetch()->interact();
    }

    public function action_vote()
    {
        $user = new Cavis\Core\Data\User;
        $image = new Cavis\Core\Data\Image;
        $image_vote = new Repository_Image_Vote;

        $user->ip = $_SERVER['REMOTE_ADDR'];
        $image->id = $this->request->param('image_id');

        $data = ['image' => $image, 'user' => $user];
        $repositories = ['image_vote' => $image_vote];

        $usecase = new Cavis\Core\Usecase\Image\Vote($data, $repositories);
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

    public function action_delete()
    {
        $config = Kohana::$config->load('admin');
        if ($this->request->param('password') !== $config->get('password'))
            return FALSE;

        $image = new Cavis\Core\Data\Image;
        $image->id = $this->request->param('image_id');
        $image_delete = new Repository_Image_Delete;

        $data = ['image' => $image];
        $repositories = ['image_delete' => $image_delete];

        $usecase = new Cavis\Core\Usecase\Image\Delete($data, $repositories);
        $usecase->fetch()->interact();
    }

    public function action_view()
    {
        if ($this->request->method() === HTTP_Request::POST)
        {
            $post = $this->request->post();
            $comment = new Cavis\Core\Data\Comment;
            $image = new Cavis\Core\Data\Image;
            $image->id = $this->request->param('image_id');
            $comment->message = $post['message'];
            $comment->image = $image;
            $comment_add = new Repository_Comment_Add;
            $validator = new Tool_Validator;

            $data = ['comment' => $comment];
            $repositories = ['comment_add' => $comment_add];
            $tools = ['validator' => $validator];
            $usecase = new Cavis\Core\Usecase\Comment\Add($data, $repositories, $tools);
            $usecase->fetch()->interact();
        }

        $image = new Cavis\Core\Data\Image;
        $image->id = $this->request->param('image_id');
        $image_view = new Repository_Image_View;

        $data = ['image' => $image];
        $repositories = ['image_view' => $image_view];

        $usecase = new Cavis\Core\Usecase\Image\View($data, $repositories);

        $this->view->image = $usecase->fetch()->interact();
    }
}
