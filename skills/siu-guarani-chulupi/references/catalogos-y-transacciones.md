# Catalogos y transacciones

## Catalogos

Los catalogos concentran SQL. Usarlos para consultas y persistencia de datos, manteniendo SQL fuera de controladores, vistas y Twig.

Punto de partida: `assets/templates/catalogo.php`.

Antes de crear catalogos o transacciones personalizados, resolver el directorio `src/pers/<personalizacion>/`. Si hay varias personalizaciones, preguntar cual usar; si la elegida no existe, crearla.

## Directivas

Las funciones de catalogo suelen requerir comentarios/directivas para que Chulupi las registre correctamente. Revisar el formato exacto en la version instalada.

Prestar atencion a:

- Nombre expuesto de la funcion.
- Parametros.
- Tipo de cache.
- Retorno esperado.

Valores de cache habituales:

| Valor | Uso esperado |
|---|---|
| `no` | No conservar resultado en cache. |
| `memoria` | Conservar temporalmente durante la ejecucion. |
| `sesion` | Conservar durante la sesion del usuario. |

No usar cache prolongada para datos que deben reflejar cambios inmediatamente.

## Registro

Cuando se agregan o cambian funciones de catalogo, registrar los cambios en `_info_catalogo.php` o ejecutar:

```bash
bin/guarani generar_catalogo
```

## Transacciones

Las transacciones orquestan reglas de negocio y llamadas a catalogos.

Punto de partida: `assets/templates/transaccion.php`.

Mantener controladores delgados: el controlador valida flujo y delega reglas en transacciones cuando corresponde.

## Checklist

- El SQL quedo en catalogos.
- Catalogos y transacciones quedaron bajo la personalizacion correcta.
- Las directivas de catalogo coinciden con la version instalada.
- Se definio cache solo si es seguro.
- Se regenero catalogo o se actualizo `_info_catalogo.php`.
- La transaccion encapsula reglas de negocio.
