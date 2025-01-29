<?php

namespace Src\Infraestrutura\Web\Servicos\Firebase;

use Kreait\Firebase\Factory;

class FirebaseConn
    {
    public static function conn(): Factory
        {
       
        //Credenciais do google clould firebase.
        $factory = (new Factory())->withServiceAccount('../../../config/indicador.json');

        return $factory;
        }

    }