<?php

class html {

    protected $_html;
    protected $_head;
    protected $_body;
    protected $_headtag = Array();
    protected $_scripttag = Array();
    protected $_url;

    public function __construct() {

        $this->_url = __caminhoAplicacao__;

        $this->_headtag[] = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
        $this->_headtag[] = "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">";
        $this->_headtag[] = "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
        $this->_headtag[] = "<title>VR Framework</title>";

        $this->_headtag[] = "<link href=\"{$this->_url}public/plugin/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\">";

        $this->_scripttag[] = "<script type=\"text/javascript\" src=\"{$this->_url}public/plugin/jquery-3.2.1/jquery-3.2.1.min.js\"></script>";
        $this->_scripttag[] = "<script type=\"text/javascript\" src=\"{$this->_url}public/plugin/bootstrap-3.3.7-dist/js/bootstrap.min.js\"></script>";

        $this->_head = "<head>\n";
        
        $this->_body = "<body>\n";
        $this->_body .= "<div class=\"container\">\n";
    }

    public function getHtml() {

        $this->_html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">\n";
        $this->_html .= "<html>\n";
        $this->_html .= "{$this->getHead()}\n";
        $this->_html .= "{$this->getBody()}\n";
        $this->_html .= "</html>";
        
        return $this->_html;
    }

    public function addBody($args) {
        $this->_body .= $args. "\n";
    }

    public function getBody() {

        foreach ($this->_scripttag as $tag) {
            $this->_body .= "{$tag}\n";
        }

        $this->_body .= "</div>\n";
        $this->_body .= "</body>";
        
        return $this->_body;
    }
    
    public function addHead($args) {
        $this->_headtag[] = $args;
    }

    public function getHead() {

        foreach ($this->_headtag as $tag) {

            $this->_head .= "{$tag}\n";
        }

        $this->_head .= "</head>";
        
        return $this->_head;
    }

    public function toHtml() {

        return $this->getHtml();
    }

}
