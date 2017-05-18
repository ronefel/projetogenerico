<?php

class campo {

    protected $_input = "";
    protected $_label = "";
    protected $_type = "";
    protected $_id = "";
    protected $_name = "";
    protected $_value = "";
    protected $_placeholder = "";
    protected $_readonly = FALSE;
    protected $_checked = FALSE;
    protected $_disabled = FALSE;
    protected $_multiple = FALSE;
    protected $_options = null;

    public function __construct($label = "", $type = "text", $id = "", $name = "") {

        $this->_label = $label;
        $this->_type = $type;
        $this->_id = $id;
        if ($name !== "") {

            $this->_name = $name1;
        } else {
            $this->_name = $id;
        }
    }

    public function getCampo() {

        if ($this->_type === "text" || $this->_type === "email" || $this->_type === "password" || $this->_type === "hidden") {
            $this->_input = "<div class=\"form-group\">\n";
            $this->_input .= "<label for=\"{$this->_id}\" class=\"col-sm-2 control-label\">{$this->_label}:</label>\n";
            $this->_input .= "<div class=\"col-sm-10\">\n";
            $this->_input .= "<input type=\"{$this->_type}\" class=\"form-control\" id=\"{$this->_id}\" name=\"{$this->_name}\" value=\"{$this->getValue()}\" placeholder=\"{$this->_placeholder}\" ";
            $this->getReadonly();
            $this->_input .= ">\n";
            $this->_input .= "</div>\n";
            $this->_input .= "</div>\n";
        }

        if ($this->_type === "checkbox") {

            $this->_input = "<div class=\"form-group\">\n";
            $this->_input .= "<label for=\"{$this->_id}\" class=\"col-sm-2 control-label\">{$this->_label}:</label>\n";
            $this->_input .= "<div class=\"col-sm-10\">\n";
            $this->_input .= "<div class=\"checkbox\">\n";
            $this->_input .= "<input type=\"{$this->_type}\" id=\"{$this->_id}\" name=\"{$this->_name}\" value=\"{$this->getValue()}\" style=\"margin-left: 0px;\" ";
            $this->getChecked();
            $this->getDisabled();
            $this->_input .= ">\n";
            $this->_input .= "</div>\n";
            $this->_input .= "</div>\n";
            $this->_input .= "</div>\n";
        }

        if ($this->_type === "radio") {

            $this->_input = "<div class=\"form-group\">\n";
            $this->_input .= "<label for=\"{$this->_id}\" class=\"col-sm-2 control-label\">{$this->_label}:</label>\n";
            $this->_input .= "<div class=\"col-sm-10\">\n";
            $this->_input .= "<div class=\"radio\">\n";
            $this->_input .= "<input type=\"{$this->_type}\" id=\"{$this->_id}\" name=\"{$this->_name}\" value=\"{$this->getValue()}\" style=\"margin-left: 0px;\" ";
            $this->getChecked();
            $this->getDisabled();
            $this->_input .= ">\n";
            $this->_input .= "</div>\n";
            $this->_input .= "</div>\n";
            $this->_input .= "</div>\n";
        }

        if ($this->_type === "select") {

            $this->_input = "<div class=\"form-group\">\n";
            $this->_input .= "<label for=\"{$this->_id}\" class=\"col-sm-2 control-label\">{$this->_label}:</label>\n";
            $this->_input .= "<div class=\"col-sm-10\">\n";
            $this->_input .= "<select id=\"{$this->_id}\" name=\"{$this->_name}\" class=\"form-control\"";
            $this->getMultiple();
            $this->getDisabled();
            $this->getReadonly();
            $this->_input .= ">\n";
            $this->getOptions();
            $this->_input .= "</select>\n";
            $this->_input .= "</div>\n";
            $this->_input .= "</div>\n";
        }

        return $this->_input;
    }

    public function setReadonly($readonly = TRUE) {

        $this->_readonly = $readonly;
    }

    public function getReadonly() {

        if ($this->_readonly) {

            $this->_input .= "readonly ";
        }
    }

    public function setChecked($checked = TRUE) {

        $this->_checked = $checked;
    }

    public function getChecked() {

        if ($this->_checked) {

            $this->_input .= "checked ";
        }
    }

    public function setDisabled($disabled = TRUE) {

        $this->_disabled = $disabled;
    }

    public function getDisabled() {

        if ($this->_disabled) {

            $this->_input .= "disabled ";
        }
    }

    public function setPlaceholder($args) {

        $this->_placeholder = $args;
    }

    public function setMultiple($multiple = TRUE) {
        $this->_multiple = $ $multiple;
    }

    public function getMultiple() {

        if ($this->_multiple) {

            $this->_input .= "multiple ";
        }
    }

    public function setValue($args) {
        $this->_value = $args;
    }

    public function getValue() {
        return $this->_value;
    }

    public function addOption($option) {

        $this->_options = $option;
    }

    public function getOptions() {

        $tagOptions = "";
        foreach ($this->_options as $option) {
            $tagOptions .= "<option value=\"{$option['valor']}\"";
            if(isset($option['selected'])){
                $tagOptions .= "selected"; 
            }
            $tagOptions .= ">{$option['label']}";
            $tagOptions .= "</option>\n";
        }
        
        $this->_input .= $tagOptions;
    }

}
