<?php

namespace LayoutComponents\Controller;


class Admin extends \Cockpit\AuthController {

    public function index() {

        $components = [];

        if ($file = $this->app->path('#storage:components.php')) {
            $components = require $file;
        } else if ($file = $this->app->path('#storage:components.json')) {
            $content = @file_get_contents($file);

            $json = json_decode($content, true);

            if (!$json) {
                $json = [];
            }

            $components = new \ArrayObject($json);
        }

        return $this->render('layoutcomponents:views/index.php', compact('components'));
    }

    public function store() {
        $components = $this->param('components');

        if ($file = $this->app->path('#storage:components.php')) {
            $export = var_export($components, true);

            if (!$this->app->helper('fs')->write("#storage:components.php", "<?php\n return {$export};")) {
                return false;
            }
            
            return true;
        } else {
            if ($components) {
                $this->helper('fs')->write('#storage:components.json', json_encode($components, JSON_PRETTY_PRINT));
                return true;
            } else if(is_array($components) && empty($components)) {
                $this->helper('fs')->delete('#storage:components.json');
                return true;
            }
        }

        return false;
    }


}
