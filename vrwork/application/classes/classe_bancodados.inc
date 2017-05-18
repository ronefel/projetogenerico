<?php

/**
 * Classe Básica para acesso a Banco de dados
 * 	@abstract 
 */
class BancoDados {

    /**
     * @var string|localhost Endereço do servidor do banco de dados
     */
    protected $_Servidor = 'localhost';

    /**
     * @var integer|0 Porta de conexão no servidor
     */
    protected $_Porta = 5432;

    /**
     * @var string Nome do usuário de conexão 
     */
    protected $_Usuario = "postgres";

    /**
     * @var string Senha para conexão
     */
    protected $_Senha = "postgres";

    /**
     * @var string Nome do banco de dados
     */
    protected $_Banco = "vrwork";

    /**
     * @var resource|false Recurso contendo a conexão com o banco de dados
     */
    protected $_conn = false;

    /**
     * @var resource|false Conterá o recurso para o resultado da consulta 
     */
    protected $_dataset = false;

    /**
     * @var integer|-1 Conterá o número total de registros retornados 
     */
    protected $_numrows = -1;

    /**
     * @var array|false Conterá o registro atual recuperado de $_dataset
     */
    protected $_tupla = false;

    /**
     * @var integer|-i Posição atual de navegação no conjunto de dados 
     */
    protected $_posatual = -1;

    /**
     * @var string Conterá o último erro reportado 
     */
    protected $_ultimoerro = "";
    protected $_ind_case = CASE_UPPER;

    public function SetServidor($_servidor) {
        $this->_Servidor = $_servidor;
    }

    public function SetPorta($_porta) {
        $this->_Porta = $_porta;
    }

    public function SetUsuario($_usuario) {
        $this->_Usuario = $_usuario;
    }

    public function SetSenha($_senha) {
        $this->_Senha = $_senha;
    }

    public function SetBanco($_banco) {
        $this->_Banco = $_banco;
    }

    public function startTransaction() {
        $this->executaSQL('START TRANSACTION');
    }

    public function commit() {
        $this->executaSQL('COMMIT');
    }

    public function ROLLBACK() {
        $this->executaSQL('ROLLBACK');
    }

    /**
     * Responsável por verificar se o comando enviado
     * é do tipo SELECT e, caso seja, deve armazenar
     * em $_dataset o resultado, e calcular o 
     * número de registros.
     * 
     * @param String $_sql Instrução SQL
     * @param String $_res Recurso para o resultado da consulta
     * 
     */
    protected function isSELECT($_sql, $_res) {
        $this->_dataset = $_res;
        if (substr(trim(strtolower($_sql)), 0, 6) == 'select') {
            $this->SetNumRows();
        } else {
            $this->SetAffectedRows();
        }
    }

    public function setCase($_case = CASE_UPPER) {
        $this->_ind_case = in_array($_case, Array(CASE_LOWER, CASE_NATURAL, CASE_UPPER)) ? $_case : CASE_UPPER;
    }

    /**
     * 
     * @return type
     */
    public function getNumRows() {
        return $this->_numrows;
    }

    public function transforma() {
        $this->_tupla = $this->_tupla !== false && $this->_tupla !== null ? array_change_key_case($this->_tupla, $this->_ind_case) : $this->_tupla;
    }

    /**
     * Posiciona o ponteiro em um determinado registro de $_dataset
     * @param integer|0 $_pos Posição do registro
     * @return array Retorna seu conteúdo
     */
    public function navega($_pos = 0) {
        $this->_tupla = false;
        $this->navegainterno($_pos);
        $this->_posatual = $_pos;
        //$this->transforma();
        return $this->_tupla;
    }

    /**
     * Posiciona o ponteiro no primeiro registro de $_dataset
     * @return array Retorna seu conteúdo
     */
    public function primeiro() {
        return $this->navega();
    }

    /**
     * Retorna o próximo registro de $_dataset
     * @return array Retorna seu conteúdo
     */
    public function proximo() {
        $this->_tupla = false;
        $this->proximointerno();
        $this->_posatual++;
        //$this->transforma();
        return $this->_tupla;
    }

    /**
     * Retorna o registro anterior de $_dataset
     * @return array Retorna seu conteúdo
     */
    public function anterior() {
        return $this->navega($this->_posatual - 1);
    }

    /**
     * Retorna o último registro de $_dataset
     * @return array Retorna seu conteúdo
     */
    public function ultimo() {
        return $this->navega($this->_numrows - 1);
    }

    /**
     * Retorna o último registro recuperado de $_dataset
     * @return array Retorna seu conteúdo
     */
    public function getDadosAtual() {
        return $this->_tupla;
    }

    /**
     * Retorna o último erro armazenado e o tipo do banco de dados
     * @return string Último erro
     */
    public function getUltimoErro() {
        return "[{$this->_Tipo}]: {$this->_ultimoerro}";
    }

    public function converteDataToBD($_data) {
        // $_data Sempre no formato dd-mm-YYYY
        if (ereg("^([0-9]{1,2})-([0-9]{1,2})-([0-9]{4}).*$", $_data, $_arrdt) !== false) {
            return sprintf("'%04.4d-%02.2d-%02.2d'", $_arrdt[3], $_arrdt[2], $_arrdt[1]);
        }
        return false;
    }

    public function converteDataHoraToBD($_dtime) {
        // $_dtime Sempre no formato dd-mm-YYYY HH:MM:SS
        if (ereg("^([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})[[:space:]]([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).*$", $_dtime, $_arrdt) !== false) {
            return sprintf("'%04.4d-%02.2d-%02.2d %02.2d:%02.2d:%02.2d'", $_arrdt[3], $_arrdt[2], $_arrdt[1], $_arrdt[4], $_arrdt[5], $_arrdt[6]);
        }
        return false;
    }

    public function converteDataToHTML($_data) {
        // $_data Sempre no formato YYYY-MM-DD
        if (ereg("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}).*$", $_data, $_arrdt) !== false) {
            return sprintf("%02.2d-%02.2d-%04.4d", $_arrdt[3], $_arrdt[2], $_arrdt[1]);
        }
        return $_data; //false;
    }

    public function converteDataHoraToHTML($_dtime) {
        // $_dtime Sempre no formato YYYY-MM-DD HH:MM:SS
        if (ereg("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})[[:space:]]([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).*$", $_dtime, $_arrdt) !== false) {
            return sprintf("%02.2d-%02.2d-%04.4d %02.2d:%02.2d:%02.2d", $_arrdt[3], $_arrdt[2], $_arrdt[1], $_arrdt[4], $_arrdt[5], $_arrdt[6]);
        }
        return $_dtime; //false;
    }

    /**
     * Calcula o número de registros do select
     */
    protected function SetNumRows() {
        $this->_numrows = ($this->_dataset !== false ? pg_num_rows($this->_dataset) : 0);
    }

    public function Conectar() {
        $_strcon = "host={$this->_Servidor} ";
        $_strcon.= "dbname={$this->_Banco} ";
        $_strcon.= "user={$this->_Usuario} ";
        if ($this->_Senha != NULL && $this->_Senha != "") {
            $_strcon.= "password={$this->_Senha} ";
        }
        if (is_int($this->_Porta)) {
            $_strcon.= "port={$this->_Porta}";
        }
        if (($this->_conn = pg_connect($_strcon)) === false) {
            $_e = error_get_last();
            $this->_ultimoerro = "Erro na Conexão com o Banco de Dados : {$_e['message']}";
        }
        return $this->_conn;
    }

    public function executaSQL($_sql) {
        if ($this->_conn !== false) {
            if (($_res = pg_query($this->_conn, $_sql)) === false) {
                $this->_ultimoerro = "Erro ao Executar o comando {$_sql} : " . pg_last_error();
            } else {
                $this->isSELECT($_sql, $_res);
            }
            return $_res;
        } else {
            return false;
        }
    }

    /**
     * Posiciona o ponteiro em um determinado registro e armazena seu conteúdo
     * em $_tupla.
     * @param integer $_pos Posição do registro
     */
    protected function navegainterno($_pos) {
        if (pg_result_seek($this->_dataset, $_pos) !== false) {
            $this->_tupla = pg_fetch_assoc($this->_dataset);
        }
    }

    /**
     * Próximo registro de $_dataset
     */
    protected function proximointerno() {
        $this->_tupla = pg_fetch_assoc($this->_dataset);
    }

    public function escapeString($_str) {
        return pg_escape_string($this->_conn, $_str);
    }

    public function getLimit($_arr) {
        return " {$_arr['LIMIT']} OFFSET {$_arr['OFFSET']}";
    }

    public function setAffectedRows() {
        $this->_numrows = ($this->_dataset !== false ? pg_affected_rows($this->_dataset) : 0);
    }

}
