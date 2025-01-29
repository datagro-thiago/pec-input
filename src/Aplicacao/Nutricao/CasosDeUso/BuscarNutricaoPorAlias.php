<?php 

namespace Src\Aplicacao\Nutricao\CasosDeUso;

use Exception;
use Src\Aplicacao\Nutricao\CasosDeUso\CriarNutricao;
use Src\Dominio\Negociacao\Servicos\ServicoNutricao;

class BuscarNutricaoPorAlias {

    private ServicoNutricao $servicoNutricao;
    private CriarNutricao $criarNutricao;
    
    public function __construct() {
        $this->servicoNutricao = new ServicoNutricao;
        $this->criarNutricao = new CriarNutricao();
    }

    public function executar(string $alias): int | array
    {
        try {
            $time = microtime();
            $id = 0;
            $formatarNome = $this->servicoNutricao->formatarNome($alias);
    
            $categoria = $this->servicoNutricao->buscarNutricaoPorAlias($formatarNome);
                
            if (empty($categoria) && !empty($alias)) {
                $id = $this->criarNutricao->executar($alias);
            }
    
            if (!empty($categoria)) {
                $id = $categoria->getId();
            }
            
            return $id ;
        } catch (Exception $e) {
            return ["mensagem" => $e->getMessage()];
        }
 
    }
}