<?php

//pega todo o caminho da aplicação apartir do servidor
$url = $_SERVER["REQUEST_URI"];

define("__caminhoAplicacao__", "/projetogenerico/vrwork/");

//retiro a parte que não interessa
$url = str_replace(__caminhoAplicacao__, "", $url);

//retira todas as '/' e monta um array 
//$op[0] é o nome do controle
//$op[1] é o metodo do controle
//$op[2] é o parâmetro
$op = explode("/", $url);

//verifica se foi passado algum nome de controle por parâmetro
if ($op[0] != "" && is_file("../application/controllers/" . $op[0] . ".php")) {
    include ("../application/controllers/" . $op[0] . ".php");
    $obj = new $op[0] ();
    //se não for passado chama o controle padrão
} else {
    include ("../application/controllers/hello.php");
    $obj = new Hello();
}

//verifica se foi passado algum método por parâmetro
//se não foi passado chama a index
if (isset($op[1]) && is_file("../application/controllers/" . $op[0] . ".php")) {
    $obj->$op[1]();
} else {
    $obj->index();
}