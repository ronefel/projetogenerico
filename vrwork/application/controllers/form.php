<?php

class form {

    private $formtag;
    public $submitTxt = "Enviar";

    public function __construct($id = 'form', $action = '#', $method = 'post', $enctype = 'text/plain') {
        $this->formtag = "<form id=\"$id\" name=\"$id\" action=\"$action\" method=\"$method\" enctype=\"$enctype\" class=\"form-horizontal\">\n";
    }

    public function addToForm($args) {
        $this->formtag .= $args;
    }
    
    public function addCampo($campo) {
        $this->formtag .= $campo->getCampo();
    }

    public function buttonSubmit() {
        $this->formtag .= "<div class=\"form-group\">\n";
        $this->formtag .= "<div class=\"col-sm-offset-2 col-sm-10\">\n";
        $this->formtag .= "<button type=\"submit\" class=\"btn btn-default\">$this->submitTxt</button>\n";
        $this->formtag .= "</div>\n";
        $this->formtag .= "</div>\n";
    }

    public function toForm() {
        
        self::buttonSubmit();
        
        return $this->formtag .= "</form>";
    }

}
