<?php

namespace Src\Infraestrutura\Logs;

use Exception;

class Logger
    {
    private static $arquivo;
    public static function criarArquivo(): void
        {
		$dateNow = date('Y-m-d');
        self::$arquivo = __DIR__ . "/recepcao/" . "recepcao-$dateNow" . "-logs.txt";
        $diretorio = dirname(self::$arquivo);

        if (!is_dir($diretorio)) {
            if (!mkdir($diretorio, 0777, true)) {
				echo 'log error';
			}
            }

        if (!file_exists(self::$arquivo)) {
            if (false === file_put_contents(self::$arquivo, "")) {
				echo 'log error';
			}
            }
        }

    public static function log(string $mensagem): void
        {
        if (!isset(self::$arquivo)) {
			echo 'log error';
            }

        $datahora = date('Y-m-d H:i:s');
        // Tenta escrever no arquivo, e caso falhe, lança uma exceção
        if (!file_put_contents(self::$arquivo, "[$datahora] $mensagem" . PHP_EOL, FILE_APPEND)) {
			echo 'log error';
			}
        }

    public static function clear(): void
        {
        if (isset(self::$arquivo)) {
            // Limpa o conteúdo do arquivo
            if (false === file_put_contents(self::$arquivo, "")) {
				echo 'log error';
			}
            }
        }
    }