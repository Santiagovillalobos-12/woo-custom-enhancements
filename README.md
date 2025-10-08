# Woo Custom Enhancements

Plugin de WordPress que mejora significativamente la experiencia de WooCommerce con funcionalidades avanzadas de filtrado, galería de productos, efectos hover y un sistema de importación automática de plantillas de Elementor.

## 🚀 Características Principales

### 📊 Sistema de Filtrado y Ordenamiento Avanzado
- **Shortcode `[sorting_dropdown]`** - Dropdown con contador de resultados
- **Shortcode `[category_filter_horizontal]`** - Filtro horizontal de categorías con AJAX
- **Ordenamiento por:** popularidad, valoración, fecha, precio (ascendente/descendente)
- **Paginación inteligente** que mantiene los filtros activos
- **Contador de resultados** en tiempo real
- **Subcategorías con hover** para navegación mejorada

### 🖼️ Galería de Productos Mejorada
- **Flechas de navegación** en la galería principal
- **Miniaturas debajo** de la imagen principal
- **Diseño responsive** optimizado para móviles
- **Efectos de transición** suaves y profesionales

### 🎨 Efectos Hover en Imágenes
- **Shortcode `[hover_product_image]`** - Muestra segunda imagen al hacer hover
- **Transiciones suaves** entre imagen principal y secundaria
- **Optimizado para rendimiento** con lazy loading
- **Compatible con móviles** (touch events)

### 📱 Grid de Productos Optimizado
- **Diseño responsive** con CSS Grid moderno
- **Imágenes uniformes** con tamaños optimizados
- **Efectos hover** en tarjetas de productos
- **Lazy loading** nativo para mejor rendimiento

### 🎯 Importación Automática de Plantillas Elementor
- **Importación automática** al activar el plugin
- **3 plantillas incluidas:**
  - Archive Products (archivo de productos)
  - Product Item Loop (item individual en loops)
  - Single Product (producto individual)
- **Sistema de gestión** desde el panel de administración

## 📋 Requisitos

- **WordPress** 5.0 o superior
- **WooCommerce** 5.0 o superior
- **Elementor** 3.0 o superior (para las plantillas)
- **PHP** 7.4 o superior

## 🔧 Instalación

### Método 1: Instalación Manual
1. **Descarga** el plugin desde GitHub
2. **Sube la carpeta** `woo-custom-enhancements` a `/wp-content/plugins/`
3. **Activa el plugin** desde el panel de administración de WordPress
4. **¡Listo!** Las plantillas de Elementor se importarán automáticamente

### Método 2: Clonar desde GitHub
```bash
cd wp-content/plugins/
git clone https://github.com/Santiagovillalobos-12/woo-custom-enhancements.git
```

## 🎮 Uso

### Shortcodes Disponibles

#### 1. Dropdown de Ordenamiento
```php
[sorting_dropdown]
```
**Ubicación recomendada:** En la página de tienda o archivos de productos

#### 2. Imagen con Efecto Hover
```php
[hover_product_image]
```
**Ubicación:** En el loop de productos de WooCommerce

#### 3. Filtro Horizontal de Categorías (NUEVO)
**Widget de Elementor:** "Filtro de Categorías"
**Ubicación:** En cualquier parte de la página usando Elementor
**Características:**
- Filtro horizontal con AJAX (sin recargar página)
- Muestra número de productos por categoría
- Dropdown de subcategorías con hover
- Compatible con Loop Grid de Elementor
- Personalización completa desde Elementor
- Funciona en cualquier ubicación de la página

### Plantillas de Elementor

#### Plantillas Incluidas:
- **Archive Products** - Para páginas de archivo de productos
- **Product Item Loop** - Para items individuales en loops
- **Single Product** - Para páginas de producto individual

#### Cómo Usar las Plantillas:
1. Ve a **Elementor → Plantillas → Guardadas**
2. Selecciona la plantilla que necesites
3. Haz clic en **"Usar plantilla"**
4. Aplica a la página deseada

### Gestión desde el Panel de Administración

#### Botones Disponibles:
- **"Verificar Plantillas"** - Muestra todas las plantillas disponibles
- **"Reimportar Plantillas"** - Reimporta las plantillas si es necesario

## 🎨 Personalización

### CSS Personalizado
El plugin incluye estilos optimizados que se pueden sobrescribir:

```css
/* Personalizar grid de productos */
.woocommerce ul.products {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

/* Personalizar efectos hover */
.woocommerce ul.products li.product:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.2);
}
```

### Hooks y Filtros Disponibles
```php
// Modificar opciones de ordenamiento
add_filter('woocommerce_get_catalog_ordering_args', 'mi_funcion_ordenamiento');

// Modificar productos por página
add_filter('loop_shop_per_page', function($products) {
    return 12; // Cambiar a 12 productos por página
}, 20);
```

## 🔧 Configuración Avanzada

### Tamaños de Imagen Optimizados
El plugin registra automáticamente estos tamaños:
- `product-grid` (400x400px)
- `product-grid-large` (600x600px)
- `product-grid-small` (300x300px)

### Opciones de Galería
```php
// Activar/desactivar flechas de navegación
add_filter('woocommerce_single_product_carousel_options', function($options) {
    $options['directionNav'] = true; // true/false
    return $options;
});
```

## 🐛 Solución de Problemas

### Las plantillas no aparecen
1. Verifica que **Elementor esté activo**
2. Usa el botón **"Verificar Plantillas"** en la página de plugins
3. Revisa los logs de debug de WordPress

### El dropdown no funciona
1. Asegúrate de usar el shortcode `[sorting_dropdown]`
2. Verifica que estés en una página de productos
3. Comprueba que no haya conflictos con otros plugins

### Efectos hover no funcionan
1. Verifica que el shortcode `[hover_product_image]` esté en el lugar correcto
2. Asegúrate de que los productos tengan imágenes en la galería
3. Comprueba que no haya CSS que interfiera

## 📝 Changelog

### Versión 1.0.0
- ✅ Sistema de filtrado y ordenamiento avanzado
- ✅ Galería de productos mejorada con flechas
- ✅ Efectos hover en imágenes de productos
- ✅ Grid responsive optimizado
- ✅ Importación automática de plantillas Elementor
- ✅ Sistema de gestión desde panel de administración
- ✅ Soporte completo para móviles
- ✅ Lazy loading optimizado


## 📄 Licencia

Este proyecto está bajo la Licencia GPL2. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

**Santiago Villalobos**
- GitHub: [@Santiagovillalobos-12](https://github.com/Santiagovillalobos-12)

## 🙏 Agradecimientos

- **WooCommerce** por la excelente base de ecommerce
- **Elementor** por el sistema de plantillas
- **WordPress** por la plataforma

---

⭐ **Si este plugin te ha sido útil, considera darle una estrella en GitHub!**

## 💡 Motivación del Proyecto

Este plugin nació de la necesidad de mejorar significativamente la experiencia de usuario en ecommerce que utilizan únicamente **Elementor + Elementor Pro**. La experiencia base que ofrecen estos constructores de páginas para tiendas online deja mucho que desear en términos de funcionalidad, diseño y usabilidad.

**Woo Custom Enhancements** busca llenar ese vacío proporcionando:
- **Funcionalidades avanzadas** que no están disponibles en Elementor por defecto
- **Mejoras visuales** que elevan la calidad del diseño de la tienda
- **Optimización de rendimiento** para una mejor experiencia de usuario
- **Plantillas especializadas** para ecommerce que se integran perfectamente con Elementor

El objetivo es permitir que cualquier tienda online construida con Elementor tenga la misma calidad y funcionalidad que una desarrollada con código personalizado, pero de manera simple y accesible.
