<?php
abstract class C_Controller {

    protected $params;

    public function Request($action, $params) {
        $this->params = $params;
        $this->Before();
        $this->$action();
        $this->Render();
    }

    protected  function IsGet($getVar = false) {
        if($getVar) {
            return isset($_GET[$getVar]);
        }
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    protected  function IsPost($postVar = false) {
        if($postVar) {
            return isset($_POST[$postVar]);
        }
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }

    protected function Template($file, $params = array()) {
        foreach($params as $k => $v) {
            $$k = $v;
        }

        ob_start();
        include $file;
        return ob_get_clean();
    }

    abstract protected function Before();
    abstract protected function Render();
}
?>