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
        //SET @num := 0, @image := '';
        $sql = <<<EOT
SELECT `t1`.`id`, `t1`.`name`, `t1`.`file`, `t1`.`votes`, `t2`.`comments`
FROM (

SELECT `i`.`id`, `i`.`name`, `i`.`file`, COUNT(`iv`.`ip`) AS `votes`
FROM `images` AS `i` LEFT JOIN `imagevotes` AS `iv`
ON `i`.`id`=`iv`.`image` GROUP BY `i`.`id`

) AS t1 LEFT JOIN (

SELECT image, GROUP_CONCAT(message SEPARATOR '|') AS comments FROM (
SELECT image, message, votes,
      @num := if(@image = image, @num + 1, 1) AS row_number,
      @image := image as dummy
FROM (
SELECT `c`.`image`, `c`.`message`, COUNT(`cv`.`ip`) AS `votes` FROM `comments` AS `c`, `commentvotes` AS `cv` WHERE `c`.`id`=`cv`.`comment` GROUP BY `cv`.`comment`
) AS x GROUP BY image, message, votes
HAVING row_number <= 3
ORDER BY votes DESC
) AS z GROUP BY image

) AS t2
ON `t1`.`id`=`t2`.`image`
ORDER BY `t1`.`votes` DESC
EOT;

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
