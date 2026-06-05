---
name: siu-guarani-chulupi
description: Guia especializada para personalizaciones de Chulupi en SIU-Guarani 3.x, Autogestion 3W y Preinscripcion. Use when Codex needs to crear, modificar, revisar o diagnosticar personalizaciones en src/pers, activar personalizaciones, resolver cascadas, ajustar namespaces, personalizar PHP, mensajes, CSS, JavaScript, Twig, imagenes, accesos, menus, parametros, catalogos, transacciones, zonas, seguridad, comandos de consola, Composer u operaciones nuevas.
---

# SIU-Guarani Chulupi

## Proposito

Usar esta skill para crear, modificar, revisar o diagnosticar personalizaciones de Chulupi en SIU-Guarani 3.x, especialmente Autogestion 3W y Preinscripcion.

La regla central es mantener intacto el core del SIU y trabajar con cambios minimos, trazables y versionados bajo `src/pers/<personalizacion>/`.

Este archivo debe alcanzar para operar incluso si el agente solo puede leer `SKILL.md`. Los archivos de `references/` y `assets/templates/` son ampliaciones utiles, no requisitos absolutos.

## Reglas Obligatorias

1. No modificar `src/siu/`.
2. Resolver primero el directorio `src/pers/<personalizacion>/`.
3. Si hay varias personalizaciones y el usuario no indico una, preguntar cual usar.
4. Si la personalizacion elegida no existe, crear `src/pers/<personalizacion>/` antes de agregar archivos.
5. Copiar o crear solo los archivos necesarios.
6. Reutilizar patrones del proyecto real antes de usar templates.
7. No aplicar cambios directamente en produccion.
8. Recomendar trabajar siempre con versionado, preferentemente Git.
9. Si un detalle depende de version, explicitar la version asumida y verificar contra la instalacion real.

## Workflow

1. Clasificar la tarea: archivo existente, acceso/menu, operacion nueva, modelo, seguridad/zona, mantenimiento o revision.
2. Si el agente puede leer recursos adicionales, cargar solo las referencias indicadas en "Mapa De Casos".
3. Inspeccionar el proyecto instalado. Preferir `rg` para ubicar operaciones, clases, access files, Twig, JS, CSS, catalogos, transacciones y zonas.
4. Resolver `src/pers/<personalizacion>/`.
5. Localizar el archivo o patron original en `src/siu/`.
6. Crear la ruta espejo bajo la personalizacion elegida.
7. Implementar el cambio minimo.
8. Ejecutar o recomendar comandos de mantenimiento si corresponde.
9. Revisar el checklist final.

## Uso De Recursos

Si el agente puede leer archivos ademas de `SKILL.md`, usar estos recursos de forma selectiva:

| Caso | Referencias |
|---|---|
| Reglas generales, activacion, cascada, cache | `references/reglas-generales.md` |
| PHP, mensajes, CSS, JS, Twig, imagenes | `references/tipos-de-archivo.md` |
| Access files, menus, visibilidad | `references/accesos.md` |
| Operaciones nuevas de 3W | `references/operaciones-nuevas.md` |
| Catalogos, SQL, transacciones | `references/catalogos-y-transacciones.md` |
| Zonas, derechos, parametros protegidos | `references/zonas-y-seguridad.md` |
| Versiones, fuentes oficiales, cautelas | `references/fuentes-oficiales.md` |

Usar templates solo como punto de partida, despues de revisar los patrones locales:

| Necesidad | Template |
|---|---|
| Clase PHP personalizada | `assets/templates/clase-php-personalizada.php` |
| Access minimo | `assets/templates/acceso-minimo.php` |
| Catalogo | `assets/templates/catalogo.php` |
| Transaccion | `assets/templates/transaccion.php` |

Si el agente no puede leer `references/` o `assets/`, continuar con las instrucciones de este `SKILL.md` y generar el codigo siguiendo los patrones reales del proyecto.

## Mapa De Casos

### Modificar Una Pantalla O Archivo Existente

Usar cuando el usuario pida cambiar pagelets, templates, estilos, comportamiento, mensajes, imagenes o clases PHP existentes.

Proceder asi:

1. Resolver `src/pers/<personalizacion>/`.
2. Ubicar el archivo original en `src/siu/`.
3. Revisar si ya existe una version personalizada.
4. Crear la ruta espejo dentro de `src/pers/<personalizacion>/`.
5. Aplicar el cambio minimo segun el tipo de archivo.

Reglas por tipo:

- PHP: extender la clase original y sobrescribir solo los metodos necesarios. Ajustar namespaces y aliases.
- Mensajes: agregar o modificar solo las claves necesarias.
- CSS: usar reglas especificas de la operacion o pagelet.
- JavaScript y Twig: revisar el original; suelen requerir copiar el archivo completo en la ruta espejo.
- Imagenes: reemplazar con el mismo nombre y ruta espejo si el framework resuelve por path.

### Crear O Modificar Accesos Y Menus

Usar cuando el usuario pida modificar `acc_<PERFIL>.php`, menus, visibilidad, entradas de acceso o publicacion de operaciones.

Proceder asi:

1. Resolver `src/pers/<personalizacion>/`.
2. Identificar el perfil, por ejemplo `Alumno`.
3. Ubicar el access original.
4. Revisar si existe access personalizado.
5. Declarar solo lo que cambia si la version soporta merge.
6. Si la operacion pertenece a una zona, revisar seguridad y parametros protegidos.

No copiar completo `acc_<PERFIL>.php` salvo que la version instalada lo requiera.

### Crear Una Operacion Nueva En 3W

Usar cuando el usuario pida una nueva operacion de Autogestion 3W.

Crear bajo:

```text
src/pers/<personalizacion>/operaciones/<ruta_operacion>/
```

Confirmar antes de crear:

- personalizacion objetivo;
- perfil o acceso donde se publica;
- ubicacion de menu;
- pertenencia a zona;
- necesidad de modelo, catalogos o transacciones.

Empezar con controlador minimo y `accion__index()`. Agregar vista, Twig, pagelets, CSS y JS solo si la operacion lo necesita y siguiendo convenciones existentes.

### Trabajar Con Catalogos Y Transacciones

Usar cuando el usuario pida SQL, consultas, escritura, reglas de negocio, funciones de catalogo, cache o regeneracion de catalogo.

Reglas:

- Los catalogos contienen SQL.
- Las transacciones orquestan reglas de negocio y llamadas a catalogos.
- Evitar SQL en controladores, vistas y Twig.
- Revisar las directivas/comentarios requeridos por la version instalada.
- Registrar funciones nuevas o modificadas en `_info_catalogo.php` o ejecutar `bin/guarani generar_catalogo`.
- Usar cache solo si es seguro. Valores habituales: `no`, `memoria`, `sesion`.

### Revisar Zonas Y Seguridad

Usar cuando el usuario pida permisos, parametros protegidos, navegacion entre operaciones, derechos, enlaces seguros o acciones de escritura.

Verificar:

- que la operacion este incorporada a la zona correcta;
- que los parametros de navegacion esten protegidos;
- que acciones de escritura, AJAX, guardado, borrado o cambio de estado tengan controles;
- que los enlaces usen helpers o patrones del framework;
- que `rs_consulta` se use cuando la version lo requiera para navegacion protegida.

### Mantenimiento Y Cache

Si cambiaron catalogos o metadata de modelo, ejecutar o recomendar:

```bash
bin/guarani generar_catalogo
```

Si los cambios no aparecen, ejecutar o recomendar:

```bash
bin/guarani limpiar_cache
```

Tambien verificar permisos en `instalacion/cache`.

## Activacion Y Cascada

La activacion suele estar en `instalacion/config.php`:

```php
'usar_personalizaciones' => true,
```

El punto de acceso debe apuntar a la personalizacion:

```php
'accesos' => array(
    'des01' => array(
        'personalizacion' => 'mi_universidad',
    ),
),
```

Si hay cascada:

```php
'personalizacion' => array('pers_institucion', 'pers_facultad'),
```

La personalizacion mas a la derecha tiene mayor prioridad. Orden de resolucion: core SIU, `pers_institucion`, `pers_facultad`.

## Fuentes Y Versiones

Priorizar documentacion oficial de la version instalada de SIU-Guarani sobre transcripciones, tutoriales historicos o ejemplos de otras versiones.

El manual fuente de esta skill fue contrastado con documentacion oficial de SIU-Guarani 3.23.0, pero las instalaciones reales pueden variar.

## Checklist Final

- `src/siu/` quedo intacto.
- Se eligio o creo explicitamente `src/pers/<personalizacion>/`.
- La ruta personalizada refleja la ruta original cuando corresponde.
- Se copio o creo solo lo necesario.
- Namespaces, aliases y herencia fueron revisados.
- Access/menu usa declaracion minima cuando la version lo permite.
- Zonas, permisos y acciones de escritura fueron revisados cuando corresponde.
- Catalogos fueron registrados o regenerados cuando cambio el modelo.
- Cache fue limpiada o recomendada cuando el comportamiento queda stale.
- El cambio fue pensado para desarrollo/pruebas y bajo versionado.
