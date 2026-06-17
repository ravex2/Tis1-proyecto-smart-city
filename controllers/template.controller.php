<?php

class TemplateController {
    public function ctrtemplate($view = null) {
        include __DIR__ . "/../views/layout/header.php";        
        $file = $view ? $view : __DIR__ . "/../views/pages/auth/login.php";

        if (file_exists($file)) {
            include $file;
        } else {
            include __DIR__ . "/../views/pages/404.php";
        }
        include __DIR__ . "/../views/layout/footer.php";
    }
}

