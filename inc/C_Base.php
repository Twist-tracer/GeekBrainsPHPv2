<?php
include_once "C_Controller.php";

class C_Base extends C_Controller {

    protected $title;
    protected $content;

    public function __construct() {
        $this->title = "MyTitle";
        $this->content = "MyArticle";
    }

    public function render() {
       $page = $this->Template("../theme/main.php", array(
           "title" => $this->title,
           "content" => $this->content
       ));

       echo $page;
    }

}

?>