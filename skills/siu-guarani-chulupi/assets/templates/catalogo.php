<?php

namespace pers\__PERSONALIZACION__\modelo;

class __CATALOGO__ extends \siu\modelo\__CATALOGO_BASE__
{
    /**
     * Revisar las directivas requeridas por la version instalada antes de registrar.
     */
    public function __consulta__(array $parametros = array())
    {
        $sql = "
            SELECT
                columna
            FROM
                tabla
            WHERE
                condicion = :condicion
        ";

        return $this->consultar($sql, $parametros);
    }
}
