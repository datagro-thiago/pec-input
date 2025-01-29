<?php

namespace Src\Aplicacao\Bucket\CasosDeUso;

use Src\Dominio\Bucket\Bucket;

class EnviarArquivoBucket
{

    public function executar(string $arquivo, string $nomeDestino): Bucket
    {
        $absoluteFilePath = realpath($arquivo);
        $bucket = new Bucket(
            getenv("BUCKET_NAME"),
            $absoluteFilePath,
            $nomeDestino,
        );

        return $bucket;
    }
}