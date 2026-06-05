# Fuentes oficiales

## Criterio

Priorizar la documentacion oficial de la version instalada de SIU-Guarani sobre transcripciones, tutoriales historicos o ejemplos de versiones anteriores.

El manual fuente de este repositorio fue contrastado con documentacion oficial de SIU-Guarani 3.23.0. Si la instalacion del usuario usa otra version, verificar los detalles contra esa version.

## Paginas oficiales usadas como base

- `personalizaciones/personalizacion_chulupi`
- `personalizaciones/creacion_operacion_3w`
- `personalizaciones/operaciones_en_zonas`
- `personalizaciones/personalizacion_de_acceso`
- `personalizaciones/personalizacion_scripts_composer`
- `personalizaciones/personalizacion_parametros_configuracion`
- `personalizaciones/personalizacion_comandos_consola`
- `NotasTecnicas/personalizaciones/modelo`

## Cautelas

Cuando exista tension entre una capacitacion y una guia oficial especifica, usar la regla mas precisa y actual.

Ejemplo: para accesos, preferir personalizacion minima mediante merge cuando la version lo soporta, en lugar de copiar completo `acc_Alumno.php`.

Las rutas de integracion con Gestion o repositorios externos pueden variar por version. No documentar rutas absolutas como regla universal sin verificarlas en la instalacion real.

## Checklist

- Se explicito la version asumida cuando el detalle depende de version.
- Se reviso la instalacion real antes de afirmar rutas o wrappers concretos.
- Se priorizo documentacion oficial de la version instalada.
