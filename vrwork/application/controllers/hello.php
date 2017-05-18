<?php

require_once '../application/classes/classe_bridge.php';

class Hello extends bridge {

    public function index() {

        $form = new form();

        $email = new campo("E-mail", "email", "email");
        $email->setPlaceholder("E-mail");
        $email->setReadonly();
        $form->addCampo($email);

        $senha = new campo("Senha", "password", "senha");
        $form->addCampo($senha);

        $checkbox = new campo("Lembrar de mim", "checkbox", "lembrar");
        $checkbox->setChecked();
        $form->addCampo($checkbox);

        $radio1 = new campo("Rádio button 01", "radio", "radios");
        $radio1->setChecked();
        $form->addCampo($radio1);

        $radio2 = new campo("Rádio button 02", "radio", "radios");
        $form->addCampo($radio2);

        $radio3 = new campo("Rádio button 03", "radio", "radios");
        $radio3->setDisabled();
        $form->addCampo($radio3);

        $select = new campo("Selecione", "select", "programa");
        $select->addOption($this->getListaArquivos(Array('valor' => '[menu]', 'label' => '[MENU]')));
        $form->addCampo($select);

        $form->submitTxt = "Entrar";

        $html = new html();
        $html->addHead("<script src=\"../teste/teste.js\"></script>");
        $html->addBody("<h1>VRWork</h1>");
        $html->addBody($form->toForm());
        $html->addBody("<h6><small>Todos os direitos reservados.</small></h6>");
        echo $html->toHtml();
    }

    protected function getListaArquivos($_fixo = null, $_diretorio = "../application/controllers/", $_ext = ".php", $_sufixo = "", $_inclui_diretorio = false) {
        
        $_dir = dir($_diretorio);
        $_arquivos = Array();
        while (($_l = $_dir->read()) !== FALSE) {
            
            if (preg_match("/$_ext/", $_l, $_f) !== 0) {
                
                    $_arquivos[] = $_l;
            }
        }
        $_dir->close();
        ksort($_arquivos);
        $_opcoes = Array();
        if ($_fixo !== null) {
            
            $_opcoes[] = $_fixo;
        }
        foreach ($_arquivos as $_arq) {
            
            $_arq = str_replace($_ext, "", $_arq);
            
            $_opcoes[] = ['label' => "{$_sufixo}{$_arq}", 'valor' => ($_inclui_diretorio === true ? $_diretorio : "") . $_arq];
        }

        return $_opcoes;
    }

}
