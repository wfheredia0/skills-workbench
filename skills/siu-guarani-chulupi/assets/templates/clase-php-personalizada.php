<?php

namespace pers\__PERSONALIZACION__\__RUTA_NAMESPACE__;

use siu\__RUTA_NAMESPACE__\__CLASE_ORIGINAL__ as clase_original;

class __CLASE_PERSONALIZADA__ extends clase_original
{
    public function __METODO_A_SOBRESCRIBIR__()
    {
        $resultado = parent::__METODO_A_SOBRESCRIBIR__();

        // Ajustar solo el comportamiento necesario.

        return $resultado;
    }
}
