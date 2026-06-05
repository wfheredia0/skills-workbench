# Reglas generales

## Principio central

No modificar `src/siu/`. Toda personalizacion debe vivir bajo:

```text
src/pers/<nombre_personalizacion>/
```

Para personalizar un archivo del core, recrear la misma ruta dentro de la personalizacion.

```text
src/siu/operaciones/acceso/login/pagelet_login.php
src/pers/mi_universidad/operaciones/acceso/login/pagelet_login.php
```

Copiar solo los archivos necesarios. No copiar carpetas completas del core si el cambio afecta un solo archivo.

## Directorio de personalizacion

Antes de iniciar una personalizacion o crear una operacion nueva, resolver siempre el directorio de trabajo:

```text
src/pers/<nombre_personalizacion>/
```

Procedimiento:

1. Revisar si existe `src/pers/`.
2. Listar las personalizaciones existentes dentro de `src/pers/`.
3. Si existe una sola y coincide con el acceso objetivo, usarla.
4. Si existen varias y el usuario no indico una, preguntar en cual trabajar.
5. Si el usuario indica una personalizacion nueva, crear `src/pers/<nombre_personalizacion>/` antes de agregar archivos.
6. Verificar que `instalacion/config.php` active esa personalizacion para el punto de acceso correspondiente.

No asumir el nombre de la personalizacion por el nombre de la institucion, facultad, branch o ambiente. El directorio elegido afecta namespaces, rutas espejo y cascada.

## Activacion

La activacion suele definirse en `instalacion/config.php`.

```php
'usar_personalizaciones' => true,
```

Asociar el punto de acceso con la personalizacion:

```php
'accesos' => array(
    'des01' => array(
        'personalizacion' => 'mi_universidad',
    ),
),
```

## Cascada

Chulupi permite varias personalizaciones por punto de acceso:

```php
'personalizacion' => array('pers_institucion', 'pers_facultad_economicas'),
```

La personalizacion mas a la derecha tiene mayor prioridad. En el ejemplo, el orden de resolucion es:

1. Core SIU.
2. `pers_institucion`.
3. `pers_facultad_economicas`.

Usar pocos niveles para mantener trazabilidad.

## Busqueda

Antes de editar:

- Ubicar el archivo original en `src/siu/`.
- Resolver el directorio objetivo `src/pers/<personalizacion>/`.
- Revisar si ya existe una version en `src/pers/`.
- Inspeccionar namespaces, aliases, herencia y convenciones locales.
- Revisar el access o menu que expone la operacion.

La URL de una operacion puede orientar la busqueda dentro de `src/siu/operaciones/`, pero no asumir equivalencia perfecta: el acceso puede mapear rutas explicitamente.

## Mantenimiento

Comandos frecuentes:

```bash
bin/guarani generar_catalogo
bin/guarani limpiar_cache
```

Si los cambios no aparecen, limpiar cache y verificar permisos en `instalacion/cache`.

Despues de actualizar SIU-Guarani:

1. Comparar cada archivo personalizado contra el core actualizado.
2. Revisar especialmente Twig y JavaScript, porque suelen copiarse completos.
3. Regenerar catalogo si hubo cambios de modelo.
4. Limpiar cache.
5. Probar operaciones criticas.

## Checklist

- `src/siu/` queda intacto.
- Se eligio o creo explicitamente `src/pers/<personalizacion>/`.
- La ruta en `src/pers/<personalizacion>/` refleja la ruta original.
- La personalizacion esta activa para el acceso correcto.
- La cascada, si existe, tiene el orden esperado.
- Se copio lo minimo necesario.
- Se limpiaron caches o se indico hacerlo cuando corresponde.
