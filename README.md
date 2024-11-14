# Vertex

**Vertex** es un framework base en PHP diseñado para proporcionar una arquitectura modular y escalable, ideal para construir aplicaciones web modernas. Su estructura flexible permite una rápida implementación de nuevos proyectos y asegura la mantenibilidad y reutilización de componentes clave. Vertex encapsula funcionalidades esenciales como enrutamiento, inyección de dependencias, manejo de errores, logging y abstracción de consultas SQL, proporcionando una base sólida para cualquier proyecto.

## Estructura del Proyecto

```plaintext
.
├── app                  # Código de la aplicación (controladores, modelos, vistas)
├── config               # Archivos de configuración (e.g., base de datos, entorno)
├── core                 # Núcleo del framework (contiene Bootstrap, Handler, VQuery, VRouter)
│   ├── Bootstrap        # Inicialización y carga de servicios
│   ├── Handler          # Manejo de errores y logger
│   ├── VQuery           # Abstracción de consultas SQL/ORM
│   └── VRouter          # Enrutamiento de la aplicación
├── public               # Directorio público (punto de entrada `index.php`, assets)
├── src                  # Módulos e interfaces del framework
│   ├── Interfaces       # Interfaces para el contenedor, logger, comandos, etc.
│   └── Commands         # Comandos específicos de la aplicación
└── tmp                  # Archivos temporales (cache, logs, sesiones)
```

## Paquetes Clave

Los siguientes paquetes han sido instalados para proporcionar funcionalidades esenciales en Vertex:

- **php-di/php-di**: Inyección de dependencias para una arquitectura flexible.
- **monolog/monolog**: Logging detallado para el registro de eventos y errores.
- **vlucas/phpdotenv**: Carga de variables de entorno desde archivos `.env`.
- **symfony/console**: Manejo de comandos en la CLI.
- **kint-php/kint**: Depuración fácil y visual durante el desarrollo.
- **phpunit/phpunit**: Testing unitario (para entornos de desarrollo).

## Requisitos Previos

- **PHP >= 8.3**
- **Composer**

## Instalación

1. Clona este repositorio:

   ```bash
   git clone https://github.com/nandocdev/vetrex-php.git
   ```

2. Navega al directorio del proyecto:

   ```bash
   cd vertex
   ```

3. Instala las dependencias usando Composer:

   ```bash
   composer install
   ```

4. Crea un archivo `.env` en la raíz del proyecto y configura las variables de entorno necesarias (consulta `.env.example` como referencia).

5. Configura el servidor web para apuntar al directorio `public/index.php` como el punto de entrada.

## Uso

1. **Iniciar la Aplicación**: Vertex utiliza `public/index.php` como punto de entrada.
2. **Configurar Dependencias**: Puedes añadir tus servicios personalizados en `core/Bootstrap/Container.php`.
3. **Agregar Rutas**: Define las rutas de la aplicación en `core/VRouter/Router.php`.
4. **Ejecutar Comandos CLI**: Agrega comandos en `src/Commands` y ejecútalos usando el componente de consola de Symfony.
5. **Depurar**: Usa la clase `Debug` en `core` para aprovechar Kint en la depuración.

### Conexión a la Base de Datos

Vertex incluye una capa de abstracción para consultas SQL. Utiliza el `QueryBuilderFacade` para construir y ejecutar consultas de manera sencilla:

```php
// Ejemplo de uso del QueryBuilderFacade para una consulta SELECT
$queryBuilder = new QueryBuilderFacade('users');
$queryBuilder->select()->where('id', '=', 1)->execute();
```

### Definir Rutas y Controladores

1. **Definir una ruta en `Router.php`**:

   ```php
   $router->get('/hello', [HelloController::class, 'greet']);
   ```

2. **Crear un controlador en `app`**:

   ```php
   namespace App\Controllers;

   class HelloController {
       public function greet() {
           echo "¡Hola desde Vertex!";
       }
   }
   ```

3. Accede a `http://tu-dominio/hello` para ver el resultado.

### Realizar Operaciones CRUD

Vertex también facilita la creación de consultas CRUD mediante el uso de los `QueryBuilder` para las operaciones de base de datos:

```php
// Insertar un nuevo registro
$queryBuilder = new QueryBuilderFacade('users');
$queryBuilder->insert()->set([
   'name' => 'John Doe',
   'email' => 'johndoe@example.com'
])->execute();

// Actualizar un registro
$queryBuilder = new QueryBuilderFacade('users');
$queryBuilder->update()->set([
   'name' => 'Jane Doe'
])->where('id', '=', 1)->execute();

// Eliminar un registro
$queryBuilder = new QueryBuilderFacade('users');
$queryBuilder->delete()->where('id', '=', 1)->execute();
```

## Testing

Para ejecutar las pruebas unitarias, utiliza:

```bash
composer test
```

## Contribuir

¡Las contribuciones son bienvenidas! Si deseas mejorar Vertex, envía un pull request o abre un issue para discutir cambios importantes.

## Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.
