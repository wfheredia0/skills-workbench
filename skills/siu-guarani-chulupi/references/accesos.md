# Accesos

## Regla

Personalizar accesos con declaraciones minimas. No copiar completo `acc_<PERFIL>.php` salvo que la version instalada lo exija.

Punto de partida: `assets/templates/acceso-minimo.php`.

## Merge

La personalizacion de acceso debe declarar solo lo que cambia. Chulupi mergea la definicion personalizada con la definicion base cuando el patron de version lo soporta.

Antes de editar:

- Resolver primero el directorio `src/pers/<personalizacion>/`; si no existe, crearlo.
- Identificar el perfil, por ejemplo `Alumno`.
- Ubicar el `acc_<PERFIL>.php` original.
- Revisar si ya existe un `acc_<PERFIL>.php` en `src/pers/<personalizacion>/`.
- Confirmar como se registran operaciones y menu en esa version.

## Campos frecuentes

Los campos concretos dependen de la version, pero suelen aparecer:

- Identificador de operacion.
- Ruta o controlador.
- Menu o ubicacion de navegacion.
- Visibilidad.
- Reglas de acceso.
- Parametros propios de la operacion.

## Operaciones en menu

Cuando se agrega una operacion al menu:

1. Crear o personalizar la operacion.
2. Declarar la entrada minima en el access.
3. Si pertenece a una zona, revisar `references/zonas-y-seguridad.md`.
4. Limpiar cache si no aparece.

## Checklist

- Se edito el access del perfil correcto.
- Se trabajo en la personalizacion elegida por el usuario o confirmada desde `src/pers/`.
- La declaracion es minima.
- La operacion existe en la ruta esperada.
- El menu queda en la ubicacion correcta.
- Si hay zona, se revisaron permisos y parametros protegidos.
