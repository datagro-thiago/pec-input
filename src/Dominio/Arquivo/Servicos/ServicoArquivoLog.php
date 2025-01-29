<?php
namespace Src\Dominio\Arquivo\Servicos;
use Src\Dominio\Arquivo\Entidades\ArquivoLog;

class ServicoArquivoLog {

    public static function criarArquivoLog(string $remetente): ArquivoLog {
        $arqLog = new ArquivoLog (
            "log",
            CAMINHO_LOGS,
            $remetente,
        );

        return $arqLog;

    }
}