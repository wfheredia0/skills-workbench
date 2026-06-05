---
titulo: "Manual corregido de Personalización de Chulupí: Partes I y II"
base_transcripcion: "Manual_Personalizacion_Chulupi_Partes_I_y_II.md"
fuente_tecnica_prioritaria: "Guia_Tecnica_Chulupi_corregida.md"
version_documento: "manual_consolidado_contrastado_documentacion_oficial_3.23.0"
---

# Manual de Personalización de Chulupí: Partes I y II

## Criterio de consolidación

Este manual parte de la transcripción de las Partes I y II de la capacitación y la corrige con la información técnica prioritaria de la guía actualizada. Cuando una nota procedente del audio puede depender de la versión instalada, se presenta expresamente como una recomendación a verificar y no como una regla universal.

Este documento se encuentra en proceso de elaboración y podrá ser modificado en el futuro con el propósito de corregir errores, incorporar nueva información o mejorar la claridad y precisión de la documentación.

Para esta revisión se contrastó el contenido con la documentación oficial de **SIU-Guaraní 3.23.0**. Cuando existe una diferencia entre la transcripción de la capacitación y las páginas oficiales consultadas, se adopta como criterio prioritario la documentación oficial de esa versión.

## Resumen ejecutivo

El framework **Chulupí** es una arquitectura web utilizada por **SIU-Guaraní 3.x**, especialmente en **Autogestión 3W** y **Preinscripción**.

Su esquema de personalización se basa en separar claramente:

- el código provisto por SIU;
- el código personalizado por la institución, facultad o unidad académica.

La idea central es:

> **No modificar el núcleo del SIU.**

Toda personalización debe realizarse dentro de `src/pers/<nombre_personalizacion>/`, recreando la estructura del archivo original ubicado en `src/siu/`.

Chulupí permite personalizar clases PHP, mensajes, CSS, JavaScript, Twig, imágenes, modelos de datos, transacciones, accesos, parámetros de configuración, comandos de consola, dependencias Composer y operaciones completas.

---

# 1. Estructura general del proyecto

Los proyectos basados en Chulupí organizan su código de forma similar a la siguiente:

```text
├── bin
├── instalacion
└── src
    ├── pers
    │   ├── ejemplo01
    │   └── ejemplo02
    └── siu
```

## 1.1. Carpetas principales

| Carpeta | Descripción |
|---|---|
| `src/siu/` | Código original provisto por SIU. No debe modificarse. |
| `src/pers/` | Código de personalización propio de la institución, facultad o unidad académica. |
| `instalacion/` | Configuración local del proyecto, incluyendo `config.php`. |
| `bin/` | Comandos de consola del proyecto, por ejemplo `bin/guarani`. |

## 1.2. Regla principal

Para modificar un archivo del core, se debe recrear su ruta dentro de la personalización.

Ejemplo:

```text
src/siu/operaciones/acceso/login/pagelet_login.php
```

se personaliza como:

```text
src/pers/mi_universidad/operaciones/acceso/login/pagelet_login.php
```

**Importante:** copiar solo los archivos que se desean modificar. No se debe copiar una carpeta completa del core si solo se necesita cambiar un archivo.

---

# 2. Activación de personalizaciones

La activación se realiza en:

```text
instalacion/config.php
```

## 2.1. Activar el uso de personalizaciones

```php
'usar_personalizaciones' => true,
```

## 2.2. Elegir una personalización para un punto de acceso

Ejemplo:

```php
'accesos' => array(
    'des01' => array(
        'personalizacion' => 'mi_universidad',
    ),
),
```

Esto indica que el punto de acceso `des01` debe usar la personalización:

```text
src/pers/mi_universidad/
```

---

# 3. Personalización en cascada

Chulupí permite definir más de una personalización en un mismo punto de acceso.

Ejemplo:

```php
'accesos' => array(
    'des01' => array(
        'personalizacion' => array('pers_institucion', 'pers_facultad_economicas'),
    ),
),
```

## 3.1. Orden correcto de prioridad

La personalización definida **más a la derecha** tiene mayor prioridad.

En el ejemplo anterior:

```php
array('pers_institucion', 'pers_facultad_economicas')
```

el orden de resolución es:

1. Core SIU.
2. `pers_institucion`.
3. `pers_facultad_economicas`.

Por lo tanto, si un mismo archivo existe en ambas personalizaciones, el archivo de:

```text
src/pers/pers_facultad_economicas/
```

sobrescribe al de:

```text
src/pers/pers_institucion/
```

## 3.2. Recomendación

Aunque el framework permite cascada de N niveles, para mantenimiento conviene usar pocos niveles, por ejemplo:

```text
institución -> facultad
```

Demasiados niveles pueden dificultar la trazabilidad de qué archivo se está ejecutando realmente.

---

# 4. Comparativa conceptual: Chulupí vs Toba

| Característica | Toba / Gestión | Chulupí / Autogestión |
|---|---|---|
| Público típico | Administradores y operadores internos | Alumnos, docentes y usuarios masivos |
| Enfoque | Backoffice administrativo | Autogestión web |
| Metadatos | Base de datos y herramientas visuales | Archivos PHP, comentarios/directivas y estructura de archivos |
| Interfaz | Más guiada por componentes/metadatos | HTML, Twig, CSS y JS con mayor control manual |
| Personalización | Dependiente del esquema de Toba | Basada en `src/pers` y namespaces |
| Rendimiento | Orientado a gestión interna | Optimizado para mayor concurrencia |
| SQL | Abstracciones y componentes de datos | Catálogos con SQL explícito |

Esta comparación debe entenderse como una simplificación conceptual. La implementación concreta puede variar según la versión de SIU-Guaraní.

---

# 5. Arquitectura MVC en Chulupí

Chulupí utiliza una arquitectura basada en el patrón **Modelo-Vista-Controlador**.

## 5.1. Controlador

El controlador recibe la petición, valida datos, coordina el flujo y comunica modelo y vista.

Convenciones comunes:

```php
function accion__index()
{
    // Acción por defecto
}
```

```php
function accion__guardar()
{
    // Acción disparada por una operación del usuario
}
```

El controlador debe evitar enviar lógica compleja a la vista. La vista debería recibir datos ya preparados.

## 5.2. Modelo

El modelo se divide principalmente en:

- **Catálogos**, que contienen SQL.
- **Transacciones**, que orquestan lógica de negocio y llamadas a catálogos.

## 5.3. Vista

La vista utiliza:

- plantillas **Twig**;
- pagelets;
- recursos CSS y JS;
- layouts como `kernel/dos_columnas.twig` o `kernel/pagelet.twig`.

---

# 6. Pagelets

Un **Pagelet** es una unidad independiente de interfaz.

Puede incluir:

- una clase PHP;
- un template Twig;
- CSS;
- JavaScript;
- datos preparados para la vista.

## 6.1. Anatomía típica

```text
operaciones/mi_operacion/
├── controlador.php
├── vista.php
├── template.twig
├── pagelet_mi_bloque.php
└── mi_bloque/
    └── default.twig
```

## 6.2. Responsabilidad del Pagelet

El Pagelet prepara datos y renderiza una parte de la pantalla.

Ejemplo:

```php
class pagelet_cuadro_nombres extends pagelet
{
    function get_nombre()
    {
        return 'cuadro_nombres';
    }

    function prepare()
    {
        $this->data = array();
        $this->data['nombres'] = $this->controlador->modelo()->info__nombres();
    }
}
```

---

# 7. Personalización por tipo de archivo

No todos los archivos se personalizan igual.

## 7.1. Clases PHP

Para personalizar una clase PHP:

1. Crear un archivo en la misma ruta relativa dentro de `src/pers/<nombre>/`. El archivo personalizado debe contener solamente la nueva clase, el `namespace`, la extensión de la clase original y los métodos que sea necesario sobrescribir. No se debe copiar íntegramente el contenido de la clase del core.
2. Usar el namespace de la personalización.
3. Extender la clase original del core.
4. Sobrescribir solo los métodos necesarios.

**Importante:** al personalizar una clase PHP no se debe copiar el contenido completo de la clase original. Se crea una nueva clase dentro de `src/pers/<nombre>/`, se extiende la clase correspondiente del core y se incluyen únicamente los métodos que se desean modificar. Los métodos que no se sobrescriben continúan heredándose de la clase original ubicada en `src/siu/`.

Ejemplo:

```php
<?php

namespace mi_universidad\extension_kernel;

use siu\errores\error_guarani_login;

class login extends \siu\extension_kernel\login
{
    function autenticar($id, $clave)
    {
        $parametros = array(
            'usuario' => $id,
            'clave' => $clave
        );

        $id_usuario = catalogo::consultar('persona', 'autenticar', $parametros);

        if (empty($id_usuario)) {
            throw new error_guarani_login('-1');
        }

        return $id_usuario;
    }
}
```

## 7.2. Uso de alias para evitar colisiones

Cuando la clase personalizada tiene el mismo nombre que la clase original, se recomienda usar alias:

```php
<?php

namespace mi_universidad\modelo\datos\db;

use siu\modelo\datos\db\alumno as alumno_siu;

class alumno extends alumno_siu
{
    // Sobrescribir solo lo necesario
}
```

## 7.3. Mensajes

Los archivos de mensajes se fusionan con el original.

No se debe copiar completo:

```text
src/siu/mensajes/mensajes.es.php
```

En cambio, se crea:

```text
src/pers/mi_universidad/mensajes/mensajes.es.php
```

con solo las claves nuevas o modificadas.

Ejemplo:

```php
<?php

return array(
    'header.bienvenido' => 'Bienvenido al Sitio',
    'nombre_clave_nueva' => 'Mensaje nuevo'
);
```

## 7.4. CSS

El CSS personalizado se carga después del original, por lo que puede pisar reglas usando la cascada CSS.

Ejemplo:

```css
.label-obligatorio {
    font-style: normal;
    font-weight: bold;
}
```

## 7.5. JavaScript

Los archivos JS deben copiarse completos en la personalización.

Ejemplo:

```js
kernel.renderer.registrar_pagelet('lista_carreras', function(info) {
    var id = '#' + info.id;

    return {
        onload: function() {
            $(id + ' #boton').on('click', function () {
                $('#lista_encuestas_pendientes').toggle();
            });
        }
    }
});
```

## 7.6. Twig

Los archivos Twig también deben copiarse completos cuando se personalizan.

Ejemplo:

```twig
{% extends "kernel/pagelet.twig" %}

{% block contenido %}
    <button class="btn btn-primary" id="boton">Ocultar Encuestas</button>
{% endblock %}
```

## 7.7. Imágenes

Para reemplazar una imagen, crear una imagen con el mismo nombre y en la misma ruta dentro de la personalización.

Ejemplo:

```text
src/siu/www/img/logo-transparente.png
```

se reemplaza con:

```text
src/pers/mi_universidad/www/img/logo-transparente.png
```

El nombre debe coincidir exactamente, incluyendo mayúsculas, minúsculas y extensión.

---

# 8. Modelo: catálogos y transacciones

## 8.1. Catálogos

Los catálogos están ubicados normalmente en:

```text
modelo/datos/db
```

Contienen métodos con SQL y comentarios/directivas obligatorias.

Ejemplo:

```php
<?php

namespace mi_universidad\modelo\datos\db;

class certificados extends \siu\modelo\datos\db\certificados
{
    /**
     * parametros: _ua, nro_inscripcion, alumno
     * cache: no
     * filas: n
     */
    function certificados_habilitados($parametros)
    {
        $sql = "execute procedure sp_certif_habilit ({$parametros['_ua']}, {$parametros['nro_inscripcion']})";

        // ...
    }
}
```

## 8.2. Directivas obligatorias del comentario

Las directivas usadas por el catálogo son:

| Directiva | Obligatorio | Descripción |
|---|---:|---|
| `parametros` | Sí, si el método recibe parámetros | Lista de parámetros separados por coma. |
| `no_quote` | No | Parámetros que no deben envolverse entre comillas simples. |
| `cache` | Sí | Puede ser `no`, `memoria` o `sesion`. |
| `cache_expiracion` | No | Duración del cache en segundos. Por defecto puede usar `CACHE_EXPIRATION_MAX`. |
| `filas` | Sí | `n` para cantidad indefinida o un número entero. |

## 8.3. Corrección importante sobre directivas

En versiones/documentos puede encontrarse referencia a `@param`, `@cache` o `@nocuote`, pero para esta guía corregida se adopta el formato actualizado del archivo de referencia:

```php
/**
 * parametros: nombre, apellido
 * cache: no
 * filas: n
 */
```

y:

```php
/**
 * parametros: persona
 * no_quote: persona
 * cache: no
 * filas: n
 */
```

No usar simultáneamente nombres distintos para la misma directiva dentro de una misma guía.

## 8.4. Transacciones

Las transacciones suelen ubicarse en:

```text
modelo/transacciones
```

Convenciones comunes:

| Prefijo | Uso |
|---|---|
| `info__` | Consultas o lectura de datos. |
| `evt__` | Eventos que modifican datos o ejecutan acciones. |

Ejemplo:

```php
<?php

namespace mi_universidad\modelo\transacciones;

use siu\modelo\datos\catalogo;

class carga_nombres
{
    function info__nombres()
    {
        return catalogo::consultar('nombres', 'lista', array());
    }

    function evt__agregar_nombre($parametros)
    {
        catalogo::consultar('nombres', 'insertar_nombre', $parametros);
    }
}
```

**Nota:** si una operación modifica datos o combina varias acciones dependientes, se recomienda manejar la atomicidad con transacciones, `commit`, `rollback` y control de errores según el patrón usado por la aplicación.

---

# 9. Registro de funciones en el catálogo

Cada función del modelo de datos necesita una entrada en:

```text
src/pers/<nombre>/modelo/datos/_info_catalogo.php
```

para que pueda ser invocada mediante:

```php
catalogo::consultar('clase', 'metodo', $parametros);
```

Hay dos formas de registrar funciones.

## 9.1. Opción A: manual

Agregar un método estático en `_info_catalogo.php` con el nombre:

```text
Clase__metodo
```

Ejemplo:

```php
static function zona_examenes__lista_mesas_con_actas_abiertas()
{
    return array(
        'parametros' => array(
            0 => 'persona',
        ),
        'cache' => 'no',
        'filas' => 'n',
    );
}
```

El archivo debe:

- estar en el namespace de la personalización;
- contener una clase llamada `_info_catalogo`;
- declarar las mismas directivas que el comentario del método.

## 9.2. Opción B: automática

Ejecutar:

```bash
bin/guarani generar_catalogo
```

El comando escanea los métodos declarados en los archivos de:

```text
src/pers/<nombre>/modelo/datos/db/
```

y regenera `_info_catalogo.php`.

## 9.3. Cuándo usar cada opción

| Caso | Recomendación |
|---|---|
| Se agrega una sola función nueva y ya existe `_info_catalogo.php` con otras entradas | Usar edición manual para evitar regenerar todo. |
| Se cambiaron nombres de funciones, parámetros o varias directivas | Usar `bin/guarani generar_catalogo`. |
| Se está creando el catálogo desde cero | Usar `bin/guarani generar_catalogo` y luego revisar el resultado. |

---

# 10. Comandos de consola útiles

## 10.1. Regenerar catálogo

```bash
bin/guarani generar_catalogo
```

Usar luego de:

- agregar métodos nuevos en catálogos;
- modificar comentarios/directivas;
- cambiar parámetros de métodos consultados por `catalogo::consultar()`.

## 10.2. Limpiar caché

```bash
bin/guarani limpiar_cache
```

Usar cuando:

- los cambios no aparecen;
- se modificaron vistas;
- se modificaron recursos;
- se modificó configuración;
- se sospecha que se están sirviendo datos viejos.

## 10.3. Nota sobre permisos

Si los cambios no se reflejan, verificar permisos de escritura sobre:

```text
instalacion/cache
```

El usuario del servidor web, por ejemplo `www-data`, debe poder escribir donde corresponda.

---

# 11. Personalización de accesos

Los archivos de acceso se encuentran en:

```text
src/siu/conf/acceso/acc_<PERFIL>.php
```

Para personalizarlos, crear un archivo con el mismo nombre dentro de la personalización:

```text
src/pers/<PERSONALIZACION>/conf/acceso/acc_<PERFIL>.php
```

## 11.1. Merge del archivo de acceso

No hace falta copiar todo el archivo original. El archivo personalizado debe conservar la estructura necesaria, pero especificar solamente los valores que se desean agregar o modificar.

En ejecución, el framework fusiona la personalización con el archivo original mediante `array_replace_recursive()`.

> **Evitar este antipatrón:** copiar íntegramente `src/siu/conf/acceso/acc_<PERFIL>.php` dentro de `src/pers/<PERSONALIZACION>/conf/acceso/` para realizar un cambio pequeño. Aunque un tutorial histórico muestra una copia completa de `acc_Alumno.php`, la guía específica de personalización de accesos indica que solo debe declararse lo que cambia. Mantener una copia completa aumenta el acoplamiento con el core y el riesgo de conflictos al actualizar SIU-Guaraní.

## 11.2. Estructura general

```php
return array(
    'id' => 'ALU',
    'parametros' => array(
        'index' => 'inicio_alumno'
    ),
    'operaciones' => array(
        'examen' => array(
            'activa' => true,
            'accion_default' => 'index',
            'url' => 'examen',
            'menu' => array(
                'visible' => true,
                'submenu' => null,
                'texto' => 'clave'
            ),
            'parametros' => array(
                'x' => 'y'
            )
        ),
    ),
);
```

## 11.3. Campos frecuentes

| Campo | Descripción |
|---|---|
| `id` | Nombre del perfil, por ejemplo `ALU` o `GER`. |
| `parametros.index` | Página inicial del perfil. |
| `operaciones.<nombre>.activa` | Indica si la operación está activada. |
| `operaciones.<nombre>.url` | URL de acceso. Si no se indica, suele usar el nombre de la operación. |
| `operaciones.<nombre>.menu.visible` | Indica si aparece en el menú. |
| `operaciones.<nombre>.menu.submenu` | Submenú donde se agrupa. |
| `operaciones.<nombre>.menu.texto` | Clave del mensaje para el menú. |

## 11.4. Ejemplo

```php
<?php

return array(
    'operaciones' => array(
        'listado_alumnos' => array(
            'menu' => array(
                'visible' => true,
                'submenu' => 'listados'
            ),
        ),
        'cursada' => array(
            'activa' => false
        ),
        'examen' => array(
            'url' => 'examenes',
        ),
    ),
);
```

---

# 12. Personalización de parámetros de configuración

Se pueden agregar parámetros propios dentro de `instalacion/config.php`.

Ejemplo:

```php
'accesos' => array(
    'des01' => array(
        'personalizacion' => 'mi_universidad',
        'clave_parametro_pers' => 'valor_parametro_pers',
    ),
),
```

Luego crear:

```text
src/pers/mi_universidad/extension_kernel/proyecto.php
```

Ejemplo:

```php
<?php

namespace mi_universidad\extension_kernel;

use siu\extension_kernel\proyecto as proyecto_siu;

class proyecto extends proyecto_siu
{
    function get_parametro_pers()
    {
        $id_pto_acceso = $this->get('id_pto_acceso');
        $accesos = $this->get('accesos');

        return $accesos[$id_pto_acceso]['clave_parametro_pers'] ?? '';
    }
}
```

Uso:

```php
$parametro_pers = kernel::proyecto()->get_parametro_pers();
```

---

# 13. Personalización de comandos de consola

Se pueden crear comandos propios.

## 13.1. Crear comando

Ruta:

```text
src/pers/mi_universidad/util/consola/comandos/comando_pers.php
```

Ejemplo:

```php
<?php

namespace mi_universidad\util\consola\comandos;

use Symfony\Component\Console;
use SIU\Chulupi\kernel;
use siu\util\consola\comandos\comando_guarani;

class comando_pers extends comando_guarani
{
    protected $input;
    protected $output;

    protected function configure()
    {
        $this
            ->setName('comando_pers')
            ->setDescription('Comando personalizado.')
            ->setHelp('Comando personalizado.');
    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->output->setDecorated(true);
        $this->output->writeln('<info>Mensaje de información</info>');
        $this->output->writeln('<comment>Comentario</comment>');
        $this->output->writeln('<error>Mensaje de error</error>');

        return 0;
    }
}
```

## 13.2. Registrar comando

Ruta:

```text
src/pers/mi_universidad/util/consola/gadmin.php
```

Ejemplo:

```php
<?php

namespace mi_universidad\util\consola;

use siu\util\consola\gadmin as gadmin_siu;

class gadmin extends gadmin_siu
{
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct('SIU-Guaraní Pers', '3.23.0');

        $this->addCommands(array(
            new comandos\comando_pers(),
        ));
    }
}
```

---

# 14. Personalización de Composer

## 14.1. Agregar librerías externas

Editar:

```text
src/pers/composer.json
```

Ejemplo:

```json
{
  "require": {
    "nesbot/carbon": "1.22.1"
  }
}
```

Luego ejecutar desde la raíz del proyecto:

```bash
composer update nesbot/carbon
```

## 14.2. Recomendaciones

- Usar versiones cerradas de dependencias.
- Evitar subir cambios innecesarios del `composer.lock` raíz.
- Revisar compatibilidad con la versión de SIU-Guaraní instalada.

## 14.3. Scripts personalizados de Composer

Se pueden agregar scripts que se ejecuten luego de `composer install` o `composer update`.

Archivo:

```text
src/pers/Composer.php
```

Ejemplo:

```php
<?php

namespace pers;

use Composer\Script\Event;

class Composer
{
    public static function postInstallCmd(Event $event)
    {
        // Scripts personalizados luego de composer install
    }

    public static function postUpdateCmd(Event $event)
    {
        // Scripts personalizados luego de composer update
    }
}
```

---

# 15. Creación de una operación nueva en 3W

Esta sección resume el flujo para crear una operación nueva como personalización.

## 15.1. Requisitos

- Instalación funcional de SIU-Guaraní 3W. El tutorial oficial indica como base una instalación `>= 3.7`; este manual fue contrastado específicamente con las páginas publicadas para la versión **3.23.0**.
- Personalización activada en `instalacion/config.php`.
- Permisos para crear archivos en `src/pers/<nombre>/`.

## 15.2. Crear la carpeta de personalización

```bash
mkdir -p src/pers/tut_01
```

Configurar:

```php
'usar_personalizaciones' => true,
```

y en el punto de acceso:

```php
'personalizacion' => 'tut_01',
```

## 15.3. Estructura de la operación

```text
src/pers/tut_01/operaciones/abm_nombres/
├── controlador.php
├── vista.php
├── template.twig
├── form_nombres/
│   ├── default.twig
│   └── builder_form_nombres.php
├── pagelet_form_nombres.php
├── cuadro_nombres/
│   └── default.twig
└── pagelet_cuadro_nombres.php
```

## 15.4. Agregar operación al menú

Personalizar:

```text
src/pers/tut_01/conf/acceso/acc_ALU.php
```

Ejemplo:

```php
<?php

return array(
    'operaciones' => array(
        'abm_nombres' => array(
            'activa' => true,
            'menu' => array(
                'visible' => true
            )
        ),
    ),
);
```

**Importante:** para agregar esta operación no se debe copiar completo el archivo `src/siu/conf/acceso/acc_Alumno.php`. Como el archivo personalizado se fusiona con la configuración del core, alcanza con declarar el subárbol correspondiente a `operaciones.abm_nombres`.

Agregar mensaje:

```text
src/pers/tut_01/mensajes/mensajes.es.php
```

```php
<?php

return array(
    'header.menu.abm_nombres' => 'ABM de Nombres',
    'tit_abm_nombres' => 'ABM de Nombres',
);
```

## 15.5. Controlador mínimo

```php
<?php

namespace tut_01\operaciones\abm_nombres;

use siu\extension_kernel\controlador_g3w2;

class controlador extends controlador_g3w2
{
    function modelo()
    {
        return null;
    }

    function accion__index()
    {
    }
}
```

## 15.6. Vista mínima

```php
<?php

namespace tut_01\operaciones\abm_nombres;

use siu\extension_kernel\vista_g3w2;

class vista extends vista_g3w2
{
    function ini()
    {
    }
}
```

## 15.7. Template mínimo

```twig
{% extends "kernel/dos_columnas.twig" %}

{% block titulo_operacion %}
    <h2>{{ "tit_abm_nombres" | trans | capitalize }}</h2>
{% endblock %}

{% block columna_1 %}{% endblock %}
{% block columna_2 %}{% endblock %}
```

---

# 16. Ejemplo de modelo para una operación nueva

## 16.1. Tabla de ejemplo

```sql
CREATE TABLE tut_form (
    id serial,
    nombre varchar(40),
    apellido varchar(40)
);

INSERT INTO tut_form (nombre, apellido) VALUES ('Juan', 'Perez');
INSERT INTO tut_form (nombre, apellido) VALUES ('Roberto', 'Gomez');
```

## 16.2. Catálogo

Archivo:

```text
src/pers/tut_01/modelo/datos/db/nombres.php
```

```php
<?php

namespace tut_01\modelo\datos\db;

use kernel\kernel;

class nombres
{
    /**
     * parametros: nombre, apellido
     * cache: no
     * filas: n
     */
    function insertar_nombre($parametros)
    {
        $sql = "
            INSERT INTO tut_form (nombre, apellido)
            VALUES (
                {$parametros['nombre']},
                {$parametros['apellido']}
            );
        ";

        kernel::db()->ejecutar($sql);
    }

    /**
     * cache: no
     * filas: n
     */
    function lista()
    {
        $sql = "SELECT id, nombre, apellido FROM tut_form";

        return kernel::db()->consultar($sql);
    }
}
```

Luego regenerar catálogo:

```bash
bin/guarani generar_catalogo
```

## 16.3. Transacción

Archivo:

```text
src/pers/tut_01/modelo/transacciones/carga_nombres.php
```

```php
<?php

namespace tut_01\modelo\transacciones;

use siu\modelo\datos\catalogo;

class carga_nombres
{
    function info__nombres()
    {
        return catalogo::consultar('nombres', 'lista', array());
    }

    function evt__agregar_nombre($parametros)
    {
        return catalogo::consultar('nombres', 'insertar_nombre', $parametros);
    }
}
```

---

# 17. Pagelets para la operación de ejemplo

## 17.1. Pagelet de cuadro

Archivo:

```text
src/pers/tut_01/operaciones/abm_nombres/pagelet_cuadro_nombres.php
```

```php
<?php

namespace tut_01\operaciones\abm_nombres;

use kernel\interfaz\pagelet;

class pagelet_cuadro_nombres extends pagelet
{
    function get_nombre()
    {
        return 'cuadro_nombres';
    }

    function prepare()
    {
        $this->data = array();
        $this->data['nombres'] = $this->controlador->modelo()->info__nombres();
    }
}
```

Template:

```text
src/pers/tut_01/operaciones/abm_nombres/cuadro_nombres/default.twig
```

```twig
{% extends "kernel/pagelet.twig" %}

{% block contenido %}
<table class="table table-condensed table-hover table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ "nombre" | trans }}</th>
            <th>{{ "apellido" | trans }}</th>
        </tr>
    </thead>
    <tbody>
        {% for persona in this.data.nombres %}
            <tr>
                <td>{{ persona.id }}</td>
                <td>{{ persona.nombre }}</td>
                <td>{{ persona.apellido }}</td>
            </tr>
        {% endfor %}
    </tbody>
</table>
{% endblock %}
```

## 17.2. Incluir pagelet en la vista

```php
<?php

namespace tut_01\operaciones\abm_nombres;

use siu\extension_kernel\vista_g3w2;
use kernel\kernel;

class vista extends vista_g3w2
{
    function ini()
    {
        $clase = 'operaciones\\abm_nombres\\pagelet_cuadro_nombres';
        $pl = kernel::localizador()->instanciar($clase, 'cuadro_nombres');

        $this->add_pagelet($pl);
    }
}
```

Template:

```twig
{% extends "kernel/dos_columnas.twig" %}

{% block titulo_operacion %}
    <h2>{{ "tit_abm_nombres" | trans | capitalize }}</h2>
{% endblock %}

{% block columna_1 %}{% endblock %}

{% block columna_2 %}
    {{ cuadro_nombres.render }}
{% endblock %}
```

---

# 18. Formulario de ejemplo

## 18.1. Builder del formulario

Archivo:

```text
src/pers/tut_01/operaciones/abm_nombres/form_nombres/builder_form_nombres.php
```

```php
<?php

namespace tut_01\operaciones\abm_nombres\form_nombres;

use kernel\interfaz\componentes\forms\form_elemento_config;
use kernel\kernel;
use kernel\util\validador;
use siu\extension_kernel\formularios\builder_formulario;
use siu\extension_kernel\formularios\fabrica_formularios;
use siu\extension_kernel\formularios\guarani_form;

class builder_form_nombres extends builder_formulario
{
    function get_id_html()
    {
        return 'formulario_nombres';
    }

    function get_action()
    {
        return kernel::vinculador()->crear('abm_nombres', 'index');
    }

    protected function generar_definicion(guarani_form $form, fabrica_formularios $fabrica)
    {
        $form->add_accion($fabrica->accion_boton_submit(
            'agregar',
            kernel::traductor()->trans('agregar')
        ));

        $form->add_elemento($fabrica->elemento('nombre', array(
            form_elemento_config::filtro => validador::TIPO_ALPHA,
            form_elemento_config::obligatorio => true,
            form_elemento_config::elemento => array('tipo' => 'text'),
            form_elemento_config::largo => 40
        )));

        $form->add_elemento($fabrica->elemento('apellido', array(
            form_elemento_config::filtro => validador::TIPO_TEXTO,
            form_elemento_config::obligatorio => true,
            form_elemento_config::elemento => array('tipo' => 'text'),
            form_elemento_config::largo => 40
        )));
    }

    function get_configuracion_layout_grilla()
    {
        return array(
            array(
                'grupo' => 'nombres',
                'titulo' => trans('alta_nombres'),
                'filas' => array(
                    array('nombre' => array('span' => 9)),
                    array('apellido' => array('span' => 9)),
                )
            ),
        );
    }
}
```

## 18.2. Pagelet del formulario

Archivo:

```text
src/pers/tut_01/operaciones/abm_nombres/pagelet_form_nombres.php
```

```php
<?php

namespace tut_01\operaciones\abm_nombres;

use kernel\kernel;
use kernel\interfaz\pagelet;

class pagelet_form_nombres extends pagelet
{
    protected $form_builder;

    function get_nombre()
    {
        return 'form_nombres';
    }

    function get_builder_form()
    {
        if (! isset($this->form_builder)) {
            $this->form_builder = kernel::localizador()->instanciar(
                'operaciones\\abm_nombres\\form_nombres\\builder_form_nombres'
            );
        }

        return $this->form_builder;
    }

    function prepare()
    {
        $form = $this->get_builder_form()->get_formulario();
        $form->inicializar();

        $this->add_form($form);
    }
}
```

## 18.3. Twig del formulario

Archivo:

```text
src/pers/tut_01/operaciones/abm_nombres/form_nombres/default.twig
```

```twig
{% extends "kernel/pagelet.twig" %}

{% block contenido %}
    {% set vista_form = this.get_builder_form().get_vista() %}

    {{ vista_form.render_encabezado() | raw }}
    {{ vista_form.render_cuerpo() | raw }}
    {{ vista_form.render_acciones() | raw }}
    {{ vista_form.render_cierre() | raw }}
{% endblock %}
```

## 18.4. Controlador final con guardado

```php
<?php

namespace tut_01\operaciones\abm_nombres;

use siu\extension_kernel\controlador_g3w2;
use tut_01\modelo\transacciones\carga_nombres;
use kernel\kernel;

class controlador extends controlador_g3w2
{
    function modelo()
    {
        return new carga_nombres();
    }

    function accion__index()
    {
        if (kernel::request()->isPost()) {
            $pagelet = $this->vista()->pagelet('form_nombres');
            $form = $pagelet->get_builder_form()->get_formulario();

            if ($form->procesar()) {
                $this->modelo()->evt__agregar_nombre($form->get_datos());
            }
        }
    }
}
```

---

# 19. Seguridad: zonas, parámetros y derechos

Las operaciones que trabajan con **comisiones** o **mesas de examen** pueden formar parte de una zona. En este contexto no alcanza con mostrar una interfaz común: también debe conservarse la selección de la entidad, proteger los parámetros y verificar los derechos correspondientes.

## 19.1. Aspectos que integran una zona

Las zonas reúnen tres aspectos:

1. La selección de una entidad, por ejemplo una comisión o una mesa de examen. La selección actual se mantiene al navegar entre operaciones de la zona.
2. La protección de parámetros. Los identificadores sensibles no deben enviarse como valores editables directamente por el usuario.
3. La evaluación de derechos. El procesador de la zona verifica si el docente actual posee el derecho requerido para operar sobre la entidad seleccionada.

Las definiciones se encuentran en archivos personalizables como:

```text
operaciones/_comun/zonas/zona_comision.php
operaciones/_comun/zonas/zona_examen.php
```

Entre los métodos relevantes se encuentran:

```php
get_datos_navegacion()
get_descripcion_fila($fila)
get_operaciones_zona()
get_datos_detalle($transaccion, $seleccion)
get_template()
```

## 19.2. Regla obligatoria para `rs_consulta` y `rs_arreglo`

El listado de entidades consumido por `get_datos_navegacion()` **debe recibir una instancia de `rs_consulta`**. No debe reemplazarse por `rs_arreglo`, porque `rs_arreglo` no está diseñado para funcionar entre operaciones de una zona.

Para la zona de comisiones, los enlaces deben provenir de una `rs_consulta` cuya consulta al catálogo incluya la columna `id`. Para exámenes se requiere la columna `llamado_mesa`.

Esta regla no significa que `rs_arreglo` esté prohibido en todos los casos. Para generar enlaces hacia otras acciones de la **misma operación**, puede utilizarse `rs_arreglo` cuando corresponda, ya que resulta más eficiente. La distinción importante es la siguiente:

| Caso | Clase recomendada u obligatoria |
|---|---|
| Navegación entre operaciones de una zona | `rs_consulta` obligatoria |
| Enlaces hacia otras acciones de la misma operación | `rs_arreglo` puede utilizarse cuando corresponda |

## 19.3. Incorporar una operación a una zona

Una operación perteneciente a una zona debe agregarse en `get_operaciones_zona()` y su controlador debe implementar `get_info_zona()` para devolver la zona y la selección actual.

Ejemplo:

```php
function get_info_zona()
{
    if (isset($this->params_comision['comision'])) {
        return array(
            'zona' => 'comision',
            'seleccion' => $this->params_comision
        );
    }
}
```

## 19.4. Decodificación de parámetros protegidos

La forma documentada de validar y decodificar el parámetro es:

```php
$this->hash_comision = $this->validate_param(0, 'get', validador::TIPO_TEXTO);
$this->params_comision = $this->decodificar_hash($this->hash_comision, 'edicion');
```

La acción esperada debe restringirse tanto como sea posible. Cuando una acción `guardar` recibe un hash generado originalmente para `edicion`, debe validarlo de acuerdo con ese origen.

> **Evitar este antipatrón de seguridad:** utilizar `'*'` para aceptar un hash generado para cualquier acción sin una necesidad justificada. El framework lo admite para casos complejos, pero la documentación oficial advierte que esta variante es más manipulable por un atacante. Es preferible declarar una acción concreta o un conjunto acotado, por ejemplo `array('edicion', 'guardar')`.

## 19.5. Derechos sobre todas las acciones

No se deben proteger únicamente las pantallas visibles. También deben verificarse las acciones que modifican datos.

Ejemplo típico:

- `editar` muestra el formulario;
- `guardar` procesa el `POST`.

Ambas acciones deben tener los derechos correspondientes para impedir un acceso directo a `guardar`.

## 19.6. Generación de enlaces con derechos

La transacción puede devolver resultados envueltos en `rs_consulta` o `rs_arreglo`, indicando una columna identificadora y, cuando corresponda, la entidad sobre la cual se aplican derechos.

Ejemplo:

```php
$rs = new rs_consulta(
    'carga_notas_cursada_comision',
    'lista_comisiones_docente',
    $parametros
);
$rs->set_columna_id('comision');
$rs->agregar_entidad('comision');
```

Desde el controlador se accede mediante `codificador_modelo()`, que prepara la generación de enlaces y la evaluación de derechos:

```php
$comisiones = $this->codificador_modelo()
    ->info__comisiones_docente($anio_academico, $periodo_lectivo, $actividad);

$comisiones->link('LINK', 'notas_cursada_comision', 'edicion');
```

---

# 20. Interconexión con Gestión y SVN Externals

El documento original mencionaba la reutilización de lógica de Gestión mediante SVN Externals.

Esta idea se mantiene, pero se debe evitar fijar rutas absolutas o nombres de carpetas sin verificar la versión instalada.

Redacción recomendada:

> En algunas instalaciones de SIU-Guaraní, Chulupí reutiliza componentes o lógica del núcleo de Gestión mediante enlaces o mecanismos de integración del repositorio. Las rutas concretas pueden variar según versión, por lo que deben verificarse contra la instalación real.

Evitar contradicciones como usar en el mismo documento rutas distintas sin aclaración, por ejemplo:

```text
src/siu/modelo/g3
```

y:

```text
source/modelo_g3
```

Si se documentan, deben indicarse como ejemplos dependientes de versión, no como regla universal.

---

# 21. Buenas prácticas de mantenimiento

## 21.1. No modificar el core

Nunca modificar directamente:

```text
src/siu/
```

## 21.2. Copia mínima

Copiar solo lo necesario.

| Tipo | Recomendación |
|---|---|
| PHP | Extender y sobrescribir métodos puntuales. |
| Mensajes | Copiar solo claves nuevas o modificadas. |
| CSS | Crear reglas específicas que pisen las originales. |
| JS | Copiar archivo completo si se necesita modificar. |
| Twig | Copiar archivo completo si se necesita modificar. |
| Imágenes | Reemplazar con mismo nombre y ruta espejo. |
| Accesos | Declarar solo lo que cambia. No copiar completo `acc_<PERFIL>.php`. |

## 21.3. Versionado

Mantener `src/pers/<nombre>/` bajo control de versiones propio.

## 21.4. Actualizaciones del SIU

Después de actualizar SIU-Guaraní:

1. Revisar archivos personalizados.
2. Comparar cambios del core con las copias en `src/pers`.
3. Revisar especialmente Twig y JS, porque suelen requerir copia completa.
4. Regenerar catálogo si hubo cambios en modelo.
5. Limpiar caché.
6. Probar operaciones críticas.

---

# 22. Checklist rápido para personalizar Chulupí

Antes de modificar:

- [ ] Identificar el archivo original en `src/siu`.
- [ ] Crear la misma ruta dentro de `src/pers/<nombre>`.
- [ ] Copiar solo lo necesario.
- [ ] Corregir namespace.
- [ ] Extender la clase original si corresponde.
- [ ] Agregar mensajes nuevos sin copiar todo `mensajes.es.php`.
- [ ] Si se modifica modelo, revisar directivas del comentario.
- [ ] Registrar en `_info_catalogo.php` o ejecutar `bin/guarani generar_catalogo`.
- [ ] Si se modifica acceso, editar `acc_<PERFIL>.php` en la personalización declarando únicamente lo que cambia.
- [ ] Si la operación pertenece a una zona, usar `rs_consulta` para la navegación entre operaciones y revisar la protección de todas las acciones, incluidas las que procesan escritura.
- [ ] Ejecutar `bin/guarani limpiar_cache` si los cambios no aparecen.
- [ ] Verificar permisos sobre `instalacion/cache`.

---


# 23. Notas prácticas recuperadas de la capacitación

Las siguientes notas estaban presentes en la transcripción de la capacitación y siguen siendo útiles como apoyo operativo. Se redactan de forma compatible con la estructura corregida del proyecto.

## 23.1. Tipos de caché

En las directivas de catálogo pueden utilizarse, según corresponda:

| Valor | Uso esperado |
|---|---|
| `no` | No conservar el resultado en caché. |
| `memoria` | Conservar temporalmente el resultado durante la ejecución correspondiente. |
| `sesion` | Conservar el resultado durante la sesión del usuario. |

La conveniencia de aplicar caché depende de la naturaleza de la consulta. No debe utilizarse una caché prolongada para datos que necesitan reflejar cambios inmediatamente.

## 23.2. Sintaxis básica de Twig

Variables:

```twig
{{ variable }}
```

Condicionales:

```twig
{% if condicion %}
    ...
{% endif %}
```

Iteraciones:

```twig
{% for usuario in usuarios %}
    ...
{% endfor %}
```

Herencia de templates:

```twig
{% extends "kernel/pagelet.twig" %}
```

La plantilla base concreta depende del tipo de vista o pagelet que se esté implementando.

## 23.3. Localización rápida de una operación

Como heurística de trabajo, la URL suele ayudar a ubicar la carpeta de una operación dentro de:

```text
src/siu/operaciones/
```

Por ejemplo, una URL asociada a `fecha_examen` puede orientar la búsqueda hacia:

```text
src/siu/operaciones/fecha_examen/
```

Esto debe tomarse como una ayuda práctica y no como una equivalencia obligatoria para todos los casos: la URL puede configurarse explícitamente en los archivos de acceso.

## 23.4. Barra o herramientas de desarrollo

Cuando la instalación dispone de herramientas de desarrollo habilitadas, pueden ser útiles para revisar aspectos como:

- consumo de memoria;
- tiempo de ejecución;
- errores y advertencias;
- pagelets cargados;
- consultas ejecutadas.

La disponibilidad concreta depende de la versión y de la configuración del entorno.

## 23.5. Convenciones habituales de pagelets

En una operación pueden encontrarse convenciones como:

```text
default.twig
default.css
pagelet_<nombre>.js
```

La carga automática y los nombres exactos deben verificarse en la operación original que se está personalizando. Para CSS conviene agregar únicamente las reglas necesarias. Para JavaScript y Twig, cuando se personalizan, debe copiarse el archivo completo dentro de la ruta espejo de `src/pers/<nombre>/`.

## 23.6. Reutilización de componentes de Gestión

En algunas instalaciones es posible reutilizar consultas, actualizaciones, reportes Jasper, constantes o clases procedentes de Gestión. También puede resultar útil encapsular una consulta reutilizada dentro de un catálogo Chulupí y aplicar caché cuando sea seguro hacerlo.

Las rutas y los mecanismos concretos de integración dependen de la versión instalada. Antes de documentar o implementar una reutilización, se debe comprobar la estructura real del proyecto.

## 23.7. AJAX

Algunas versiones o bases de proyecto incluyen wrappers propios para realizar llamadas AJAX sobre `jQuery.ajax()`, mostrar indicadores de carga y normalizar respuestas del controlador. Cuando exista un wrapper como `KernelAjax.call(...)`, conviene reutilizarlo en lugar de implementar llamadas aisladas sin revisar el patrón del proyecto.

La firma exacta del wrapper y el formato de respuesta deben verificarse en el código de la versión instalada.

---

# 24. Fuentes oficiales contrastadas

Esta revisión tomó como referencia prioritaria las siguientes páginas de la documentación oficial de SIU-Guaraní 3.23.0:

- `personalizaciones/personalizacion_chulupi`
- `personalizaciones/creacion_operacion_3w`
- `personalizaciones/operaciones_en_zonas`
- `personalizaciones/personalizacion_de_acceso`
- `personalizaciones/personalizacion_scripts_composer`
- `personalizaciones/personalizacion_parametros_configuracion`
- `personalizaciones/personalizacion_comandos_consola`

También se consultó la nota técnica enlazada desde la página de personalización de Chulupí para ampliar la sección de modelo:

- `NotasTecnicas/personalizaciones/modelo`

Cuando un ejemplo de un tutorial histórico entra en tensión con una guía específica, este manual prioriza la regla más precisa para evitar acoplamiento innecesario. El caso principal detectado es la copia completa de `acc_Alumno.php`: se conserva como recomendación correcta la personalización mínima mediante merge.

---

# 25. Conclusión

Este manual consolidado corrige la transcripción de las Partes I y II, elimina contradicciones internas y adopta como base técnica prioritaria el esquema actualizado de personalización de Chulupí para SIU-Guaraní 3.x.

Los puntos más importantes son:

1. Mantener intacto `src/siu`.
2. Personalizar siempre dentro de `src/pers`.
3. Entender que en cascada la última personalización del array tiene prioridad.
4. Usar namespaces correctos.
5. Personalizar cada tipo de archivo según su mecanismo propio.
6. Registrar correctamente funciones de catálogo.
7. Mantener la personalización mínima y versionada.
