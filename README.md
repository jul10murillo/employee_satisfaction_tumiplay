Employee Satisfaction API

Esta API es el backend de la aplicación de seguimiento de la satisfacción de empleados. 
Permite visualizar una lista de personas, buscar en ella utilizando cualquiera de sus propiedades y gestionar favoritos.

Requisitos:
- PHP 8.x
- Composer
- Laravel 8 o superior
- MySQL/MariaDB (u otra base de datos compatible)
- Node.js (para compilación de assets, si es necesario)

Instalación:
1. Clonar el repositorio:
   git clone https://github.com/jul10murillo/employee_satisfaction_tumiplay
   cd tu_repositorio

2. Instalar dependencias de PHP:
   composer install

3. Configurar el archivo .env:
   cp .env.example .env
   Edita el archivo `.env` con tus credenciales y configuraciones.

4. Generar la clave de la aplicación:
   php artisan key:generate

5. Ejecutar las migraciones y seeders:
   php artisan migrate --seed

6. Iniciar el servidor de desarrollo:
   php artisan serve

Endpoints de la API:
Todos los endpoints se agrupan bajo el prefijo `/api/v1/employees`.

Empleados:
- Obtener lista de empleados (con paginación):
  GET /api/v1/employees?page=1

- Buscar empleados:
  GET /api/v1/employees/search?page=1&search=termino

Favoritos:
- Agregar un empleado a favoritos:
  POST /api/v1/employees/favorite
  Content-Type: application/json

  Cuerpo de la petición:
  {
      "employee_id": 123
  }

- Eliminar un empleado de favoritos:
  DELETE /api/v1/employees/favorite/{employee}

- Obtener la lista de favoritos:
  GET /api/v1/employees/favorites

  La respuesta tendrá una estructura similar a:
  {
      "data": [
          {
              "id": 31,
              "full_name": "Prof. Mae Pagac",
              "email": "rwyman@example.com",
              "area": "Ventas",
              "category": "Directivo",
              "satisfaction_level": 91,
              "created_at": "2025-02-20 04:28:51",
              "updated_at": "2025-02-20 04:28:51"
          }
      ],
      "count": 1
  }

Notas Adicionales:
- Manejo de Favoritos: La lógica de favoritos se maneja mediante endpoints que permiten agregar y eliminar empleados de la lista de favoritos. 
  La API retorna un objeto JSON con un `status` (por ejemplo, "success" o "error") y un mensaje descriptivo.
- Búsqueda y Paginación: La funcionalidad de búsqueda permite filtrar la lista de empleados utilizando cualquier propiedad, 
  y la paginación se gestiona mediante parámetros de URL (`page` y `search`).
- Migraciones y Seeders: Se incluyen migraciones para crear las tablas necesarias (empleados, empresas, favorites, etc.) y seeders 
  para poblar la base de datos con datos de prueba.

Patrones de Diseño usados:
-1. Patrón Repository

Categoría: Patrón Arquitectural (Capa de Abstracción)

Implementación en Laravel: EmployeeRepository.php

Este patrón desacopla la lógica de acceso a datos de la lógica del negocio, lo que facilita la reutilización y el mantenimiento del código.

Ejemplo aplicado:
	•	Se usa la clase EmployeeRepository para encapsular todas las operaciones relacionadas con la entidad Employee.
	•	La interfaz EmployeeRepositoryInterface define los métodos obligatorios.
	•	Se inyecta en los controladores a través del constructor (Dependency Injection).
	
-2. Patrón Singleton

Categoría: Creacional

Implementación en Laravel: Cache de Favoritos (Cache::put())

El patrón Singleton asegura que una clase tenga una única instancia en toda la aplicación. En este caso, se usa Cache::get() para almacenar y gestionar la lista de favoritos sin necesidad de acceder a la base de datos en cada solicitud.

-3. Patrón Inyección de Dependencias

Categoría: Principio SOLID (D - Dependency Inversion)

Implementación en Laravel: Uso de Interfaces en los Controladores

Laravel usa Service Container para inyectar dependencias automáticamente. En este caso, en los controladores, se inyecta la dependencia del repositorio en lugar de crear instancias manualmente.

Ejemplo en AddFavoriteController.php

-4. Patrón Factory

Categoría: Creacional

Implementación en Laravel: Uso de Factory en los seeders

Laravel implementa el Patrón Factory en la creación de datos de prueba para las bases de datos. Se usa EmployeeFactory.php para generar empleados con datos falsos.

-5. Patrón Resource/DTO (Data Transfer Object)

Categoría: Estructural

Implementación en Laravel: EmployeeResource.php

Laravel usa Resources para formatear las respuestas de la API antes de enviarlas al frontend. Esto sigue el Patrón DTO, que encapsula y transforma los datos.
