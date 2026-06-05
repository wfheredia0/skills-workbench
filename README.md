# skills-workbench

Este repositorio reune skills que he creado para usar en mis proyectos personales y en mi trabajo. Cada skill busca guardar conocimiento, criterios de implementacion, referencias y plantillas reutilizables para resolver tareas concretas de forma mas consistente.

Las skills pueden actualizarse con el tiempo para incorporar mejoras, corregir errores, ajustar instrucciones, agregar nuevas referencias o adaptar los flujos de trabajo a cambios en las herramientas y proyectos donde se usan.

## Enfoque principal

Estas skills estan pensadas principalmente para usarse con Codex de OpenAI. Por eso siguen la convencion de `SKILL.md`, `references/`, `assets/` y metadata opcional en `agents/openai.yaml`.

Aunque el foco principal es Codex, el contenido busca mantenerse lo suficientemente claro y portable para poder adaptarse a otros agentes o herramientas compatibles con Agent Skills.

## Responsabilidad de uso

El uso de estas skills es bajo responsabilidad de quien las aplique. No se recomienda ejecutar cambios directamente sobre entornos de produccion.

Antes de aplicar cualquier modificacion sugerida por una skill, se recomienda trabajar en un entorno de desarrollo o pruebas, revisar los cambios manualmente y contar siempre con algun mecanismo de versionado, como Git, que permita auditar, comparar y revertir modificaciones si fuera necesario.

## Estructura

```text
skills/
  siu-guarani-chulupi/
    SKILL.md
    agents/openai.yaml
    references/
    assets/templates/
docs/
  Manual_Personalizacion_Chulupi_Partes_I_y_II_contrastado_documentacion_oficial_3.23.0.md
  uso-skill-siu-guarani-chulupi-con-otras-ia.md
```

## Skills disponibles

- `siu-guarani-chulupi`: guia operativa para personalizaciones de Chulupi en SIU-Guarani 3.x, basada en el [manual consolidado de personalizacion de Chulupi](docs/Manual_Personalizacion_Chulupi_Partes_I_y_II_contrastado_documentacion_oficial_3.23.0.md).

## Uso con agentes distintos de Codex

Ver [docs/uso-skill-siu-guarani-chulupi-con-otras-ia.md](docs/uso-skill-siu-guarani-chulupi-con-otras-ia.md) para adaptar la skill `siu-guarani-chulupi` a agentes o herramientas que no carguen skills igual que Codex de OpenAI.

## Instalacion con Vercel skills

Este repositorio sigue una estructura compatible con instaladores de Agent Skills que leen carpetas `skills/<nombre>/SKILL.md`.

Cuando el repositorio este publicado en GitHub, la skill podra instalarse con el CLI de Vercel `skills` indicando el repositorio y el nombre de la skill:

```bash
npx skills add wfheredia0/skills-workbench --skill siu-guarani-chulupi
```

Tambien puede instalarse desde una URL git o desde una ruta local si la herramienta lo soporta. El instalador se encarga de copiar o enlazar la skill en la ubicacion esperada por el agente elegido.

La carpeta `agents/openai.yaml` contiene metadata especifica para Codex/OpenAI. Otros agentes pueden ignorarla sin afectar el uso de `SKILL.md`, `references/` y `assets/templates/`.

## Revision y mantenimiento

Antes de usar o actualizar una skill, conviene revisar que sus instrucciones sigan siendo claras, que las referencias correspondan con la version del proyecto donde se aplican y que los templates no contradigan los patrones reales del codigo.

Si se usa Codex u otra herramienta que incluya validadores propios para skills, esa validacion puede ejecutarse como control adicional, pero no es un requisito general para usar este repositorio.
