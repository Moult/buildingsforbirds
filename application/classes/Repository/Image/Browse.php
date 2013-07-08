<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Image_Browse implements Cavis\Core\Usecase\Image\Browse\Repository
{
    /**
     * Gets a preview view of all the latest submitted images
     *
     * Example:
     * $images = $repository->get_snapshot_of_latest_images();
     * $latest_submission_comments = $images[0]->comments;
     * $third_most_popular_comment = $latest_submission_comments[2];
     *
     * @return Array of Cavis\Core\Data\Image, with up to three most popular
     * comments shown
     */
    public function get_snapshot_of_latest_images()
    {
        $sql = <<<EOT
select t1.id, t1.name, t1.file, t1.votes, t2.comments
from (
    select i.id, i.name, i.file, count(iv.ip) as votes
    from images as i
    left join imagevotes as iv
    on i.id=iv.image
    group by i.id
) as t1 left join (
    select image, group_concat(message separator '|') as comments
    from (
        select image, message, votes,
            @num := if(@image = image, @num + 1, 1) as row_number,
            @image := image as dummy
        from (
            select c.message, c.image, count(cv.ip) as votes
            from comments as c, commentvotes as cv
            where c.id = cv.comment
            group by cv.comment
        ) as x
        group by image, message, votes
        having row_number <= 3
        order by votes desc
    ) as y group by image
) as t2
on t1.id=t2.image
order by t1.votes desc
EOT;

        DB::query(NULL, 'set @num := 0, @image := \'\'')->execute();
        $query = DB::query(Database::SELECT, $sql)->execute();
        $image_array = array();
        foreach ($query as $row)
        {
            $image = new Cavis\Core\Data\Image;
            $image->id = $row['id'];
            $image->name = $row['name'];
            $image->file = $row['file'];
            $image->number_of_votes = $row['votes'];

            $comment_array = array();
            if ( ! empty($row['comments']))
            {
                $comments = explode('|', $row['comments']);
                foreach ($comments as $comment)
                {
                    $data = new Cavis\Core\Data\Comment;
                    $data->message = $comment;
                    $comment_array[] = $data;
                }
            }

            $image->comments = $comment_array;
            $image_array[] = $image;
        }
        return $image_array;
    }
}
