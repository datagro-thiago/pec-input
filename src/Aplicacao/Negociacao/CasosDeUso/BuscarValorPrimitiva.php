<?php

namespace Src\Aplicacao\Negociacao\CasosDeUso;

use Src\Aplicacao\Cache\ServicoCache;

class BuscarValorPrimitiva
    {

    private ServicoCache $cache;
    public function __construct()
        {
        $this->cache = new ServicoCache();
        }
    public function executar(string $valor, callable $callback)
        {
        $c = $this->cache->buscar($valor, $callback);
        return $c;
        }
    }