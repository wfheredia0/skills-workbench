# Operaciones nuevas

## Requisitos

Crear operaciones nuevas dentro de:

```text
src/pers/<personalizacion>/operaciones/<ruta_operacion>/
```

Confirmar primero:

- Nombre de la personalizacion activa.
- Directorio objetivo `src/pers/<personalizacion>/`; si no existe, crearlo.
- Perfil o acceso donde se publicara.
- Si la operacion pertenece a una zona.
- Si necesita modelo propio.

Si hay varias personalizaciones en `src/pers/`, preguntar cual usar antes de crear la operacion. No crear operaciones nuevas en una personalizacion por inferencia.

## Estructura base

Una operacion simple puede incluir:

```text
operaciones/<ruta_operacion>/
  controlador.php
  vista.php
  templates/default.twig
  pagelet_<nombre>.php
  default.css
  pagelet_<nombre>.js
```

Los nombres exactos dependen de la operacion y convenciones de la version instalada.

## Controlador minimo

Implementar primero `accion__index()` y hacer que la vista reciba datos ya preparados. Evitar poner logica compleja en Twig.

## Vista y pagelets

Usar pagelets para preparar bloques de UI y datos de template. Revisar pagelets existentes en operaciones cercanas antes de crear nuevos patrones.

## Alta en menu

Agregar la operacion en el access correspondiente con una declaracion minima. Ver `references/accesos.md`.

## Modelo

Si la operacion necesita consultas o escritura, crear catalogos y transacciones con los patrones de `references/catalogos-y-transacciones.md`.

## Checklist

- La operacion vive en `src/pers/<personalizacion>/operaciones/`.
- El directorio `src/pers/<personalizacion>/` fue elegido o creado explicitamente.
- El controlador tiene accion inicial.
- Vista, Twig y pagelets siguen patrones locales.
- El access publica la operacion.
- Se reviso zona/seguridad si corresponde.
- Se limpio cache si la operacion no aparece.
