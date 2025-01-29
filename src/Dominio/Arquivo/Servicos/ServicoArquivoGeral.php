<?php

namespace Src\Dominio\Arquivo\Servicos;

use Exception;
use Shuchkin\SimpleXLSX;

class ServicoArquivoGeral {

    public static function deletarArquivo (string $caminho) 
    {
        $mensagem = "";
        $status = 1;
        $extensao = pathinfo($caminho, PATHINFO_EXTENSION);

        if (!file_exists($caminho)) {
            $mensagem = "Arquivo $extensao não encontrado: $caminho";
            $status = 0;
        }

        if (!unlink($caminho)) {
            $mensagem = "Erro ao excluir o arquivo $extensao: $caminho";
            $status = 0;
        }

        return [
            "mensagem" => $mensagem,
            "status" => $status
        ];
    }

    public static function validarArquivo (string $arquivo, string $tipo) {
        if (strtoupper($tipo) === "XLSX" || strtoupper($tipo) === "XLS") {
            if (!self::verificarSeXlsxTemDados($arquivo)) {
                throw new Exception("Arquivo vazio detectado. Envio rejeitado.");
            }    
        }
        
        if (strtoupper($tipo) === "JSON" ) {
            if (!self::verificarSeJsonTemDados($arquivo)) {
                throw new Exception("Arquivo vazio detectado. Envio rejeitado.");
            }    
        }

        if(filesize($arquivo) === 0) {
            throw new Exception("Arquivo vazio detectado, Envio rejeitado.");
        }

        if (strpos($arquivo, '~$') === 0) {
            throw new Exception("Arquivo temporário detectado. Envio rejeitado.");
        }
    }

    public static function verificarSeJsonTemDados($caminhoArquivo): bool {
        $resultado = file_get_contents($caminhoArquivo);
        if(empty($resultado)) {
            return false;
        }
        return true;
    }

    public static function verificarSeXlsxTemDados($caminhoArquivo):bool {
        $xls = SimpleXLSX::parseFile($caminhoArquivo);

        if (!$xls) {
            return false;
        }
    
        $rows = $xls->rows();

        // Verificar se há alguma linha e se a primeira linha não está vazia
        foreach ($rows as $row) {
            foreach ($row as $cell) {
                if (trim($cell) !== "") {
                    return true; // Se encontrar algum conteúdo, retorna true
                }
            }
        }
    
        return false; // Se todas as células estão vazias, retorna false
    }
}