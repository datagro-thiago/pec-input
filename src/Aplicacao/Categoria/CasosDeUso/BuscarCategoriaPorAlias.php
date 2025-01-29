<?php 

namespace Src\Aplicacao\Categoria\CasosDeUso;

use Exception;
use Src\Aplicacao\Categoria\CasosDeUso\CriarCategoria;
use Src\Dominio\Negociacao\Servicos\ServicoCategoria;

class BuscarCategoriaPorAlias {

    private ServicoCategoria $servicoCategoria;
    private CriarCategoria $criarCategoria;
    
    public function __construct() {
        $this->servicoCategoria = new ServicoCategoria();
        $this->criarCategoria = new CriarCategoria();
    }

    public function executar(string $alias): int
    {   
        $time = microtime();

        try {
            $id = 0;
            $formatarNome = $this->servicoCategoria->formatarNome($alias);
    
            $categoria = $this->servicoCategoria->buscarCategoriaPorAlias($formatarNome);
    
            if (empty($categoria) && !empty($alias)) {
                $id = $this->criarCategoria->executar($alias);
            }
    
            if (!empty($categoria)) {
                $id = $categoria->getId();
            }
            
            return $id ;
        } catch (Exception  $e) {
            return ["mensagem" => $e->getMessage()];
        }

    }
}