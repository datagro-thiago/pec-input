<?php
namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Municipio;
class ServicoMunicipio
    {
        public static function buscarMunicipio(string $nomeMunicipio, ?string $uf = ''): ?array
            {
            $dadosFormatados = Municipio::formatarNomeSemEstado($nomeMunicipio);
            $nome = $dadosFormatados['nome'];
            $estado = $dadosFormatados['estado'] ?: $uf;
        
            $slug = Municipio::gerarSlug($nome);
            
            $resultado = Municipio::buscarPorSlug($slug, $estado)
                ?? Municipio::buscarPorEstado($estado)
                ?? Municipio::buscarPorNome($nome, $estado)
                ?? Municipio::buscarPorSimilaridade($nome, $estado);
                            
            return $resultado ?? ['id' => 0, 'estado' => null];
            }
    }