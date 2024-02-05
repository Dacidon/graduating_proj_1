<?php
namespace Core;

class View {
    private $templatePath = '';
    private $data = [];

    public function __construct()
    {   
        $this->templatePath = PROJECT_ROOT_DIR . DIRECTORY_SEPARATOR . 'app/View';
    }

    public function render($tpl, $data = []): string {
        $this->data += $data;
        ob_start();
        include $this->templatePath . DIRECTORY_SEPARATOR . $tpl;
        return ob_get_clean();
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}