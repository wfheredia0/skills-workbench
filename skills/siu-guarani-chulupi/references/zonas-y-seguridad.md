# Zonas y seguridad

## Zonas

Una zona agrupa operaciones relacionadas y permite navegar entre ellas con parametros protegidos y derechos controlados.

Antes de modificar una operacion en zona:

- Identificar la zona.
- Identificar las operaciones que participan.
- Revisar como se generan enlaces entre operaciones.
- Revisar acciones de lectura y escritura.

## `rs_consulta` y `rs_arreglo`

Para navegacion entre operaciones, usar `rs_consulta` cuando la guia de la version lo indique. Evitar pasar parametros sensibles sin proteccion.

`rs_arreglo` no debe reemplazar automaticamente a `rs_consulta` para navegacion protegida si la documentacion de la version exige `rs_consulta`.

## Acciones protegidas

No proteger solo la accion inicial. Revisar tambien acciones que:

- Guardan datos.
- Eliminan datos.
- Procesan formularios.
- Ejecutan AJAX.
- Cambian estado.

## Enlaces

Generar enlaces con los mecanismos de derechos del framework para no saltar verificaciones de acceso.

## Checklist

- La operacion esta incorporada a la zona correcta.
- Los parametros de navegacion estan protegidos.
- Las acciones de escritura tienen control de derechos.
- Los enlaces se generan con helpers o patrones del framework.
- Las llamadas AJAX no exponen acciones sin proteccion.
