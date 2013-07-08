<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_Static extends Controller_Core
{
    /**
     * Autoloads view templates which don't have a specific route setup
     *
     * @return void
     */
    public function action_loader()
    {
        $template_path = $this->request->param('template_path');
        if (Kohana::find_file('templates', $template_path, 'mustache'))
        {
            $this->view = new View_Layout;
            $this->template = $template_path;
        }
    }
}
