<?php

namespace Src\Aplicacao\Arquivos\ArquivoLog\CasosDeUso;

use DateTime;
use Exception;
use Src\Dominio\Arquivo\Servicos\ServicoArquivoLog;

class SalvarArquivoLogLocalmente {
    
    
    public function __construct() {
    }
    public function executar (string $remetente) {
        $log = ServicoArquivoLog::criarArquivoLog($remetente);

        $logDir = $log->getDir() . "/log" . "/" . date("Y/m");
        
        try {
            if (!file_exists($logDir)) {
                if(!mkdir($logDir, 0777, true)) {
                    throw new Exception("Erro ao criar diretorio.");
                }
            }
            $logFileName = $log->getRemetente() . date("Y-m-h"). ".in.log"; 
            $logFilePath = $logDir . "/" . $logFileName;

            $fp = fopen($logFilePath, "a");

            if ($remetente === "app")
            { 
                fwrite($fp, "EXECUTADO EM: " . date('Y-m-d H:i:s') . "\n");
                fwrite($fp, "USUÁRIO: " . exec('whoami') . "\n");
                fclose($fp);                

                return ["logDir" => $logFilePath];
            }

            fwrite($fp, "POST:\n" . var_export($_POST, true) . "\n");
            fwrite($fp, "FILES:\n" . var_export($_FILES, true) . "\n");
            fclose($fp);
            
            return ["logDir" => $logFilePath];
        } catch (Exception $e) {
            return ["mensagem" => $e->getMessage()];
        }
    }
}