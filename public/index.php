<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/csv-input/vendor/autoload.php';
define('CAMINHO_LOGS', $_SERVER['DOCUMENT_ROOT'] . getenv('ROOT_LOGS'));

ini_set('upload_max_filesize', '15M');
ini_set('post_max_size', '15M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

use Src\Infraestrutura\Web\Config\Input;

// include $_SERVER ['DOCUMENT_ROOT'] . "/csv-input/src/infraestrutura/web/rotas/" . "Input.php";

$dotenv = Dotenv\Dotenv::createUnsafeImmutable($_SERVER['DOCUMENT_ROOT'] . '/csv-input');
$dotenv->load();



$input = new Input();
$response = $input->configurar();
$response->send();