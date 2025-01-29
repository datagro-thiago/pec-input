<?php

namespace Src\Dominio\Arquivo\Servicos;

use Exception;

class ServicoArquivoCsv
    {

    public function buscarColunasCsv(string $caminhoColunas): array
        {
        $time = microtime();
        $mensagem = "";
        $status = 1;
        try {
            $colunasCsv = json_decode(file_get_contents($caminhoColunas), true, 512, JSON_THROW_ON_ERROR);
            return $colunasCsv;
            } catch (Exception $e) {
            return [
                "mensagem" => "Erro ao decodificar json: " . $e->getMessage()
            ];
            }
        }

    public function buscarDirCsv(string $caminhoDir): array
        {
        $dir = $caminhoDir;
        //Nao existe?? Crio...
        try {
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    throw new Exception("Erro ao criar diretorio para armazenar csv. ");
                    }
                }

            return ["dirCsv" => $dir];
            } catch (Exception $e) {
            return [
                "mensagem" => $e->getMessage()
            ];
            }


        }
    }