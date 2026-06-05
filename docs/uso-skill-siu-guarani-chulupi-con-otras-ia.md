# Uso de la skill SIU-Guarani Chulupi con otras IA

Este documento explica como usar la skill `siu-guarani-chulupi` con otras IA o agentes distintos de Codex de OpenAI. La skill esta orientada a personalizaciones de Chulupi en SIU-Guarani 3.x, especialmente Autogestion 3W y Preinscripcion.

La estructura esta pensada principalmente para Codex de OpenAI, pero `SKILL.md` tambien contiene las reglas nucleares para agentes que solo puedan leer el archivo principal.

Si otra herramienta entiende el formato de skills y puede cargar `SKILL.md`, `references/` y `assets/` por si misma, no hace falta ninguna adaptacion especial. Si solo puede leer `SKILL.md`, la skill sigue siendo usable; las referencias y templates quedan como ampliacion opcional.

Instaladores compatibles, como el CLI de Vercel `skills`, pueden instalar esta skill desde el repositorio y ubicarla en la carpeta esperada por cada agente:

```bash
npx skills add wfheredia0/skills-workbench --skill siu-guarani-chulupi
```

Si la herramienta no tiene soporte nativo para skills, usar el siguiente prompt de agente para indicarle como decidir que archivos necesita. Si el agente informa que no puede acceder a `references/` o `assets/`, debe trabajar con las instrucciones nucleares incluidas en `SKILL.md`.

## Como incorporar el prompt

El bloque "Prompt de agente" debe agregarse como instruccion del agente o del asistente que va a trabajar con la skill. Esto permite que la IA organice mejor el trabajo: leer `SKILL.md`, decidir que referencias necesita, usar templates cuando corresponda y respetar las reglas de personalizacion.

Si la herramienta permite editar instrucciones del agente, pegar ahi el prompt completo. Si no permite configurarlo, pedirle explicitamente a la IA que incorpore ese texto como instrucciones de trabajo antes de comenzar la tarea.

## Archivos importantes

Usar estos archivos:

```text
skills/siu-guarani-chulupi/SKILL.md
skills/siu-guarani-chulupi/references/*.md
skills/siu-guarani-chulupi/assets/templates/*.php
```

Ignorar o tratar como metadata especifica de Codex de OpenAI:

```text
skills/siu-guarani-chulupi/agents/openai.yaml
```

## Prompt de agente

```text
Vas a trabajar usando la skill `siu-guarani-chulupi`.

La skill esta ubicada en:

skills/siu-guarani-chulupi/

Primero lee:

skills/siu-guarani-chulupi/SKILL.md

Luego decide que recursos cargar segun las secciones `Uso De Recursos` y `Mapa De Casos` de ese archivo. No cargues todas las referencias por defecto: carga solo las necesarias para la tarea concreta.

Si no puedes acceder a `references/` o `assets/`, usa las instrucciones nucleares incluidas en `SKILL.md` y continua con ellas.

Referencias disponibles:

- `references/reglas-generales.md`: activacion, cascada, rutas, directorio de personalizacion, cache y mantenimiento.
- `references/tipos-de-archivo.md`: PHP, mensajes, CSS, JavaScript, Twig e imagenes.
- `references/accesos.md`: access files, menus, entradas de acceso y estrategia de merge.
- `references/operaciones-nuevas.md`: creacion de operaciones nuevas en Autogestion 3W.
- `references/catalogos-y-transacciones.md`: SQL, directivas, registro de catalogo y transacciones.
- `references/zonas-y-seguridad.md`: zonas, parametros protegidos, derechos y enlaces seguros.
- `references/fuentes-oficiales.md`: criterios de documentacion oficial y cautelas por version.

Templates disponibles:

- `assets/templates/clase-php-personalizada.php`
- `assets/templates/acceso-minimo.php`
- `assets/templates/catalogo.php`
- `assets/templates/transaccion.php`

Reglas obligatorias:

1. No modifiques `src/siu/`.
2. Antes de crear o modificar archivos, identifica el directorio de personalizacion `src/pers/<personalizacion>/`.
3. Si existen varias personalizaciones y el usuario no indico cual usar, pregunta cual corresponde.
4. Si la personalizacion elegida no existe, crea `src/pers/<personalizacion>/` antes de agregar archivos.
5. Trabaja con cambios minimos y rutas espejo bajo la personalizacion elegida.
6. Antes de usar un template, revisa los patrones existentes del proyecto real.
7. Si la tarea toca catalogos o transacciones, revisa si corresponde ejecutar o recomendar `bin/guarani generar_catalogo`.
8. Si los cambios no se reflejan, revisa si corresponde ejecutar o recomendar `bin/guarani limpiar_cache`.
9. Si un detalle depende de version, explicita la version asumida y verifica contra la instalacion real.
10. No apliques cambios directamente en produccion; recomienda trabajar con versionado y en entorno de desarrollo o pruebas.

Casos de decision:

- Si la tarea modifica una pantalla, pagelet, template, estilo, comportamiento, mensaje o imagen, carga `reglas-generales.md` y `tipos-de-archivo.md`.
- Si la tarea agrega o cambia menus, accesos, visibilidad de operaciones o `acc_<PERFIL>.php`, carga `accesos.md`; si hay zona, carga tambien `zonas-y-seguridad.md`.
- Si la tarea crea una operacion nueva de Autogestion 3W, carga `operaciones-nuevas.md`; si requiere modelo, carga tambien `catalogos-y-transacciones.md`.
- Si la tarea agrega SQL, cambia reglas de negocio, expone funciones de catalogo o regenera metadata, carga `catalogos-y-transacciones.md`.
- Si la tarea trata permisos, parametros protegidos, navegacion entre operaciones, acciones de escritura o enlaces seguros, carga `zonas-y-seguridad.md`.
- Si la tarea depende de version, documentacion oficial o contradicciones con material historico, carga `fuentes-oficiales.md`.
```

## Limitaciones

Otras IA no siempre activan automaticamente la skill `siu-guarani-chulupi` como Codex de OpenAI. Si no pueden acceder al repositorio, hay que proporcionarles el contenido de `SKILL.md` y de las referencias que pidan.

El archivo `SKILL.md` no reemplaza la inspeccion del proyecto real. Chulupi y SIU-Guarani pueden variar por version, instalacion y personalizaciones previas.
