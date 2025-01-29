<?php

namespace Src\Dominio\Negociacao\Servicos;

class ServicoCache {

    private static array $cache = [];

    public static function buscar(string $chave, callable $callback) {
        if (!isset(self::$cache[$chave])) {
            self::$cache[$chave] = $callback();
        }
        return self::$cache[$chave];
    }

}