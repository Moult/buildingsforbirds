<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Image_View implements Cavis\Core\Usecase\Image\View\Repository
{
    public function get_all_image_data($image_id)
    {
        $query = DB::select('images.name', 'images.file', array(DB::expr('COUNT(imagevotes.ip)'), 'votes'))->from('images')->where('id', '=', $image_id)
            ->join('imagevotes', 'LEFT')->on('images.id', '=', 'imagevotes.image')
            ->group_by('images.id')
            ->limit(1)->execute();
        $image = new Cavis\Core\Data\Image;
        $image->id = $image_id;
        $image->name = $query->get('name');
        $image->file = $query->get('file');
        $image->number_of_votes = $query->get('votes');

        $comments = DB::select('comments.message', 'comments.id', array(DB::expr('COUNT(commentvotes.ip)'), 'votes'))->from('comments')->where('image', '=', $image_id)
            ->join('commentvotes', 'LEFT')->on('comments.id', '=', 'commentvotes.comment')
            ->group_by('comments.id')
            ->order_by('votes', 'DESC')
            ->execute();
        $comment_array = array();
        foreach ($comments as $comment)
        {
            $data = new Cavis\Core\Data\Comment;
            $data->id = $comment['id'];
            $data->message = $comment['message'];
            $comment_array[] = $data;
        }

        $image->comments = $comment_array;
        return $image;
    }
}
