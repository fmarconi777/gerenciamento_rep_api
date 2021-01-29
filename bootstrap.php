<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

define(HOST, 'localhost:3306');
define(BANCO, 'REPDataBase');
define(USUARIO, 'root');
define(SENHA, '123456');

define(DS, DIRECTORY_SEPARATOR);
define(DIR_APP, __DIR__);
define(DIR_PROJETO, 'gerenciamento_rep_api');

if (file_exists('autoload.php')){
    include 'autoload.php';
}else{
    echo 'Erro ao incluir bootstrap';exit;
}

?>