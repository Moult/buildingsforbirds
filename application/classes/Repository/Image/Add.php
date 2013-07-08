<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Image_Add implements Cavis\Core\Usecase\Image\Add\Repository
{
    private $unique_filename;
    private $tmp_name;

    public function save_file(Cavis\Core\Data\File $file)
    {
        $this->unique_filename = time().'_'.$file->name;
        $this->tmp_name = $file->tmp_name;

        $config = Kohana::$config->load('upload');
        return Upload::save(array(
            'name' => $file->name,
            'type' => $file->mimetype,
            'tmp_name' => $file->tmp_name,
            'error' => $file->error_code,
            'size' => $file->filesize_in_bytes
        ), $this->unique_filename, $config->get('directory'));
    }

    public function save_generated_file($file_path)
    {
        $config = Kohana::$config->load('upload');
        $generated_suffix = substr($file_path, strlen($this->tmp_name));
        rename($file_path, $config->get('directory').$this->unique_filename.$generated_suffix);
    }

    public function save_image($submission_name, $submission_file)
    {
        list($insert_id, $number_of_affected_rows) =  DB::insert('images', array('name', 'file'))
            ->values(array($submission_name, $submission_file))
            ->execute();
        return $insert_id;
    }
}
