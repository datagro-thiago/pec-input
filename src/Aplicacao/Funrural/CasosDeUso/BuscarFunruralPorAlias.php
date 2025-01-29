<?php

namespace Src\Aplicacao\Funrural\CasosDeUso;

use Exception;
use Src\Aplicacao\Funrural\CasosDeUso\CriarFunrural;
use Src\Dominio\Negociacao\Servicos\ServicoFunrural;

class BuscarFunruralPorAlias
    {
    private ServicoFunrural $servicoFunrural;
    private CriarFunrural $criarFunrural;

    public function __construct()
        {
        $this->servicoFunrural = new ServicoFunrural();
        $this->criarFunrural = new CriarFunrural();
        }

    public function executar(string $alias): int|array
        {
        $time = microtime();
        try {
            $id = 0;
            $formatarNome = $this->servicoFunrural->formatarNome($alias);
            $funrural = $this->servicoFunrural->buscarFunruralPorAlias($formatarNome);

            if (empty($funrural) && !empty($alias)) {
                $id = $this->criarFunrural->executar($alias);
                }

            if (!empty($funrural)) {
                $id = $funrural->getId();
                }

            return $id;
            } catch (Exception $e) {
            return ["mensagem" => $e->getMessage()];
            }

        }
    }