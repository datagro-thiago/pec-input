<?php

use Src\Infraestrutura\Jobs\NegociacoesApp\BuscarNegociacoesApp;
use Src\Infraestrutura\Web\Config\Init;

require_once '../../../vendor/autoload.php';
define('ENV', '/var/www/html/devx/csv-input');

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(ENV);
$dotenv->load();

define('LOGS', '/var/www/html/devx/csv-input/src/Infraestrutura/Logs/arquivo_original/');
define('CAMINHO_LOGS', '/var/www/html/devx/' . getenv('ROOT_LOGS'));
define('COLUNAS', "/var/www/html/devx/csv-input/config/negocio_csv.json");
define('CSV', "/var/www/html/devx" . getenv('CSV'));





$init = new Init();
$job = new BuscarNegociacoesApp();
$job->executar();
