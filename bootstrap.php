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

define(SECRET_KEY, '2dd811df-6531-4cab-8de8-0390ddd1ef58');
define(SERVIDOR, 'localhost:8080');
define(APPS, 'http://localhost:3000');

if (file_exists('autoload.php')){
    include 'autoload.php';
}else{
    echo 'Erro ao incluir bootstrap';exit;
}
