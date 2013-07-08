<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Image_Delete implements Cavis\Core\Usecase\Image\Delete\Repository
{
    /**
     * Deletes image and all associated resources.
     *
     * Example:
     * $repository->delete(42);
     *
     * @param int $image_id The unique ID associated with the image
     *
     * @return void
     */
    public function delete($image_id)
    {
        DB::delete('images')->where('id', '=', $image_id)->execute();
    }
}
