# Funcionalidad de Búsqueda de Certificados

## Descripción
Se ha agregado una nueva funcionalidad de búsqueda de certificados por DNI que permite buscar certificados tanto en VRA como en VRI usando los scripts Python existentes.

## Características
- Búsqueda de certificados por DNI (8 dígitos)
- Resultados separados para VRA y VRI
- Interfaz moderna y responsiva con Vuetify
- Validación de entrada
- Manejo de errores
- Enlaces directos a los certificados

## Instalación

### 1. Instalar dependencias PHP
```bash
composer install
```

### 2. Instalar dependencias Python
```bash
pip install pandas beautifulsoup4 selenium webdriver-manager
```

### 3. Configurar Chrome WebDriver
El sistema utiliza Chrome WebDriver para automatizar las búsquedas. Asegúrate de tener Chrome instalado.

### 4. Configurar permisos de almacenamiento
```bash
php artisan storage:link
chmod -R 775 storage/app
```

## Uso

### Acceso a la funcionalidad
1. Navega a la aplicación
2. Haz clic en "Búsqueda" en el navbar
3. Ingresa un DNI de 8 dígitos
4. Haz clic en "Buscar Certificados"

### Resultados
- Los certificados VRA se muestran en un panel expandible
- Los certificados VRI se muestran en otro panel expandible
- Cada certificado muestra:
  - Nombre del curso/certificado
  - Código
  - Estado/Tipo
  - Enlace para ver el certificado (si está disponible)

## Estructura de archivos

### Nuevos archivos creados:
- `resources/js/components/BusquedaComponent.vue` - Componente Vue para la interfaz
- `resources/views/busqueda.blade.php` - Vista Blade
- `app/Http/Controllers/BusquedaController.php` - Controlador para la lógica de búsqueda
- `README_BUSQUEDA.md` - Este archivo

### Archivos modificados:
- `resources/js/components/NavBar.vue` - Agregado botón de búsqueda
- `resources/js/app.js` - Registrado el componente de búsqueda
- `routes/web.php` - Agregada ruta para la página de búsqueda
- `routes/api.php` - Agregada ruta API para la búsqueda
- `composer.json` - Agregada dependencia symfony/process

## Configuración técnica

### Scripts Python
Los scripts Python se ejecutan en modo headless para evitar interferencias con la interfaz. Se crean archivos temporales que se limpian automáticamente después de cada búsqueda.

### Timeout
El timeout para cada búsqueda está configurado en 5 minutos (300 segundos) para permitir que los sitios web respondan correctamente.

### Validación
- El DNI debe tener exactamente 8 dígitos
- Se valida tanto en el frontend como en el backend

## Solución de problemas

### Error: "Chrome WebDriver no encontrado"
- Asegúrate de tener Chrome instalado
- El webdriver-manager descargará automáticamente el driver compatible

### Error: "Timeout en la búsqueda"
- Verifica la conexión a internet
- Los sitios web pueden estar lentos, considera aumentar el timeout

### Error: "No se encontraron certificados"
- Verifica que el DNI sea correcto
- Es posible que la persona no tenga certificados en alguno o ambos sistemas

## Notas importantes
- La búsqueda puede tomar varios segundos debido a que se consultan sitios web externos
- Los resultados dependen de la disponibilidad de los sitios web de VRA y VRI
- Se recomienda usar la funcionalidad durante horarios de menor tráfico para mejores resultados 