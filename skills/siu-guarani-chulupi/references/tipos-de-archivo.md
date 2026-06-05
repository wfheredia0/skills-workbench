# Tipos de archivo

Antes de personalizar cualquier archivo, resolver el directorio `src/pers/<personalizacion>/`. Si existen varias personalizaciones y el usuario no eligio una, preguntar cual usar. Si la personalizacion indicada no existe, crear el directorio antes de copiar o crear archivos.

## PHP

Para clases PHP, preferir extender la clase original y sobrescribir solo los metodos necesarios. Ajustar namespace y aliases para evitar colisiones entre la clase SIU y la clase personalizada.

Punto de partida: `assets/templates/clase-php-personalizada.php`.

Antes de usar el template, copiar el namespace y el nombre real de clase desde la instalacion.

## Mensajes

Agregar o sobrescribir solo las claves necesarias. Evitar copiar completo `mensajes.es.php` si el proyecto permite declaracion minima.

## CSS

Agregar reglas especificas que pisen las originales sin ampliar el impacto visual. Mantener selectores acotados a la operacion, pagelet o contenedor correspondiente.

## JavaScript

Verificar el patron de la operacion original. Cuando se personaliza JavaScript, normalmente se copia el archivo completo en la ruta espejo dentro de `src/pers/<personalizacion>/`.

Si el proyecto incluye wrappers AJAX propios, reutilizarlos antes de escribir llamadas aisladas a `jQuery.ajax()`.

## Twig

Verificar el template original y la herencia usada. Cuando se personaliza Twig, normalmente se copia el archivo completo en la ruta espejo.

Sintaxis basica:

```twig
{{ variable }}

{% if condicion %}
{% endif %}

{% for item in items %}
{% endfor %}

{% extends "kernel/pagelet.twig" %}
```

La plantilla base concreta depende del tipo de vista o pagelet.

## Imagenes

Reemplazar con el mismo nombre y ruta espejo cuando el framework resuelve assets por ruta.

## Checklist

- Namespace y aliases revisados.
- Solo se modifico el archivo necesario.
- En CSS, los selectores son especificos.
- En JS y Twig, se comparo contra el original de la version instalada.
- Los mensajes agregan claves puntuales.
