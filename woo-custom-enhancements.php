<?php
/**
 * Plugin Name: Woo Custom Enhancements
 * Plugin URI: 
 * Description: Personalizaciones de WooCommerce (filtros, galería, hover en imágenes, grid optimizado, etc.).
 * Version: 1.0.0
 * Author: Santiago Villalobos
 * Author URI: 
 * License: GPL2
 * Text Domain: woo-custom-enhancements
 */

// Evitar acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// FILTRO DE ORDENAMIENTO CON PAGINACIÓN CORREGIDA
// 1. Shortcode para el dropdown con contador corregido
function sorting_dropdown_with_count_shortcode() {
    $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order';

    // Obtener información correcta de la consulta actual
    global $wp_query;
    
    // Verificar si estamos en una consulta de productos
    if (is_post_type_archive('product') || is_tax('product_cat') || is_tax('product_tag')) {
        $total = $wp_query->found_posts;
        $per_page = $wp_query->query_vars['posts_per_page'] ?? get_option('posts_per_page', 6);
        $paged = max(1, get_query_var('paged'));
        
        // Calcular correctamente los números de inicio y fin
        $first = ($per_page * ($paged - 1)) + 1;
        $last = min($total, $per_page * $paged);
        
        // Evitar números negativos o incorrectos
        if ($total == 0) {
            $first = 0;
            $last = 0;
        } elseif ($first > $total) {
            $first = $total;
            $last = $total;
        }
    } else {
        // Fallback para otras páginas
        $total = $wp_query->found_posts;
        $per_page = get_option('posts_per_page', 6);
        $paged = max(1, get_query_var('paged'));
        $first = ($per_page * ($paged - 1)) + 1;
        $last = min($total, $per_page * $paged);
    }

    ob_start();
    ?>
    <div class="woocommerce-ordering-wrapper" style="margin-bottom:20px;">
        <p class="woocommerce-result-count">
            <?php if ($total > 0): ?>
                Mostrando <?php echo $first; ?>–<?php echo $last; ?> de <?php echo $total; ?> resultados
            <?php else: ?>
                No se encontraron productos
            <?php endif; ?>
        </p>

        <form class="woocommerce-ordering" method="get" action="">
            <select name="orderby" class="orderby" aria-label="Ordenar por">
                <option value="menu_order" <?php selected($orderby, 'menu_order'); ?>>Orden predeterminado</option>
                <option value="popularity" <?php selected($orderby, 'popularity'); ?>>Ordenar por popularidad</option>
                <option value="rating" <?php selected($orderby, 'rating'); ?>>Ordenar por valoración media</option>
                <option value="date" <?php selected($orderby, 'date'); ?>>Ordenar por lo más reciente</option>
                <option value="price" <?php selected($orderby, 'price'); ?>>Ordenar por precio: bajo a alto</option>
                <option value="price-desc" <?php selected($orderby, 'price-desc'); ?>>Ordenar por precio: alto a bajo</option>
                <option value="custom_sort" <?php selected($orderby, 'custom_sort'); ?>>Orden personalizado</option>
            </select>
            <input type="hidden" name="paged" value="1">

            <?php
            // Mantener otros parámetros GET excepto 'orderby' y 'paged'
            foreach ($_GET as $key => $value) {
                if ($key !== 'orderby' && $key !== 'paged') {
                    if (is_array($value)) {
                        foreach ($value as $v) {
                            echo '<input type="hidden" name="' . esc_attr($key) . '[]" value="' . esc_attr($v) . '">';
                        }
                    } else {
                        echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
                    }
                }
            }
            ?>
        </form>
    </div>

    <script>
    jQuery(function($){
        $('.woocommerce-ordering select.orderby').on('change', function(){
            $(this).closest('form').submit();
        });
    });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('sorting_dropdown', 'sorting_dropdown_with_count_shortcode');

// 2. Hook principal para WooCommerce
add_filter('woocommerce_get_catalog_ordering_args', function($args) {
    if (!isset($_GET['orderby']) || empty($_GET['orderby'])) {
        return $args;
    }

    $orderby = sanitize_text_field($_GET['orderby']);
    
    switch ($orderby) {
        case 'popularity':
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'rating':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'date':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'price':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;
        case 'price-desc':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'custom_sort':
            $args['orderby'] = 'menu_order';
            $args['order'] = 'ASC';
            break;
        default:
            $args['orderby'] = 'menu_order';
            $args['order'] = 'ASC';
            break;
    }
    
    return $args;
}, 10);

// 3. Asegurar que WooCommerce use 6 productos por página
add_filter('loop_shop_per_page', function($products) {
    return 6; // Forzar 6 productos por página
}, 20);


// --- INICIA: Código para colocar miniaturas y flechas debajo de la imagen principal ---

// Activar flechas en la galería de productos (WooCommerce Flexslider)
add_filter( 'woocommerce_single_product_carousel_options', 'loom_activate_gallery_arrows' );
function loom_activate_gallery_arrows( $options ) {
    $options['directionNav'] = true; // mostrar flechas
    $options['animation']   = 'slide';
    return $options;
}

// Inyectar estilos personalizados solo en páginas de producto
add_action( 'wp_enqueue_scripts', 'loom_product_gallery_styles', 999 );
function loom_product_gallery_styles() {
    if ( ! function_exists( 'is_product' ) || ! is_product() ) {
        return;
    }

    $custom_css = <<<'CSS'
/* Contenedor principal de la galería: imagen arriba, miniaturas abajo */
.woocommerce-product-gallery.woocommerce-product-gallery--with-images {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 100%;
}

/* Imagen principal ocupa todo el ancho */
div.product div.images .flex-viewport {
    order: 1;
    width: 100% !important;
    max-width: 100%;
    position: relative;
}

/* Flechas de navegación - posicionadas un poco más arriba del centro */
ul.flex-direction-nav {
    order: 2;
    position: absolute;
    top: 40%;
    left: 0;
    right: 0;
    display: flex;
    justify-content: space-between;
    width: 100%;
    pointer-events: none;
    padding: 0 20px;
    list-style: none;
    z-index: 10;
}

ul.flex-direction-nav li a {
    pointer-events: all;
    background: #fff;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    color: #333;
    font-size: 0; /* ocultar texto */
    text-decoration: none;
    position: relative;
    transition: all 0.2s ease;
    border: 1px solid #e0e0e0;
}
ul.flex-direction-nav li a:hover {
    background: #f8f8f8;
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}

/* Flecha izquierda con pseudo-elemento - más pequeña */
ul.flex-direction-nav li:first-child a::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 8px;
    height: 8px;
    border-left: 2px solid #333;
    border-bottom: 2px solid #333;
    transform: translate(-50%, -50%) rotate(45deg);
}

/* Flecha derecha con pseudo-elemento - más pequeña */
ul.flex-direction-nav li:last-child a::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 8px;
    height: 8px;
    border-right: 2px solid #333;
    border-top: 2px solid #333;
    transform: translate(-50%, -50%) rotate(45deg);
}

/* Contenedor de miniaturas - ocupa todo el ancho */
.flex-control-thumbs {
    order: 3;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 0;
    list-style: none;
    position: relative;
}

/* Ajustar tamaño de miniaturas - más grandes */
.flex-control-thumbs li {
    width: 90px !important;
    height: 90px !important;
    margin: 0 !important;
    flex-shrink: 0;
}

/* Imagen miniatura ocupe el contenedor */
.flex-control-thumbs li img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    transition: transform 0.2s, border-color 0.2s;
    cursor: pointer;
}
.flex-control-thumbs li img:hover {
    transform: scale(1.05);
    border-color: #ccc;
}

/* Miniatura activa */
.flex-control-thumbs .flex-active img {
    border: 3px solid #ff9900;
    box-shadow: 0 2px 8px rgba(255, 153, 0, 0.3);
}

/* Ajustes responsive */
@media (max-width: 768px) {
    .flex-control-thumbs li {
        width: 75px !important;
        height: 75px !important;
    }
    ul.flex-direction-nav {
        top: 40%;
        padding: 0 15px;
    }
    ul.flex-direction-nav li a {
        width: 32px;
        height: 32px;
    }
    ul.flex-direction-nav li a::before {
        width: 7px;
        height: 7px;
        border-width: 2px;
    }
}

@media (max-width: 575px) {
    .flex-control-thumbs li {
        width: 65px !important;
        height: 65px !important;
    }
    ul.flex-direction-nav {
        top: 40%;
        padding: 0 10px;
    }
    ul.flex-direction-nav li a {
        width: 28px;
        height: 28px;
    }
    ul.flex-direction-nav li a::before {
        width: 6px;
        height: 6px;
        border-width: 2px;
    }
}

/* Asegurar que miniaturas no queden ocultas por overflow */
.woocommerce-product-gallery .flex-control-thumbs {
    overflow: visible;
}

/* Mejorar el contenedor de la galería */
div.product div.images {
    width: 100%;
    max-width: 100%;
}
CSS;

    // Registrar y encolar un "style" vacío para poder inyectar CSS inline de forma segura
    wp_register_style( 'loom-vertical-slider-style', false );
    wp_enqueue_style( 'loom-vertical-slider-style' );
    wp_add_inline_style( 'loom-vertical-slider-style', $custom_css );
}

// --- FIN: Código para la galería ---

// SOLUCIÓN ÓPTIMA Y ESCALABLE PARA IMÁGENES UNIFORMES
add_action('after_setup_theme', function() {
    // Registrar tamaños de imagen optimizados para productos
    add_image_size('product-grid', 400, 400, true); // Tamaño base para grid
    add_image_size('product-grid-large', 600, 600, true); // Para pantallas grandes
    add_image_size('product-grid-small', 300, 300, true); // Para móviles
});

// Shortcode para mostrar imagen destacada + segunda imagen con efecto hover y enlace al producto
function producto_con_hover_shortcode() {
    global $product;

    if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
        return ''; // Si no es un producto válido, no muestra nada
    }

    // URL del producto
    $product_url = get_permalink( $product->get_id() );

    // Imagen destacada - usando el nuevo tamaño optimizado
    $imagen_principal_id = $product->get_image_id();
    $imagen_principal_url = wp_get_attachment_image_url( $imagen_principal_id, 'product-grid' );

    // Segunda imagen (si existe en la galería) - usando el nuevo tamaño optimizado
    $galeria_ids = $product->get_gallery_image_ids();
    $segunda_imagen_url = isset($galeria_ids[0]) ? wp_get_attachment_image_url( $galeria_ids[0], 'product-grid' ) : '';

    // Si no hay segunda imagen, no aplicamos el efecto
    if ( ! $segunda_imagen_url ) {
        return '<a href="' . esc_url( $product_url ) . '" class="hover-product-image"><img src="' . esc_url( $imagen_principal_url ) . '" alt="' . esc_attr( $product->get_name() ) . '" loading="lazy"></a>';
    }

    ob_start();
    ?>
    <div class="hover-product-image-wrapper">
        <a href="<?php echo esc_url( $product_url ); ?>" class="hover-product-image" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
            <img class="primary-image" src="<?php echo esc_url( $imagen_principal_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy">
            <img class="secondary-image" src="<?php echo esc_url( $segunda_imagen_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy">
        </a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'hover_product_image', 'producto_con_hover_shortcode' );



// CSS SIMPLIFICADO SIN POSITION ABSOLUTE PROBLEMÁTICO
add_action('wp_head', function() {
    if (is_shop() || is_product_category() || is_product_tag() || is_product()) {
        ?>
        <style>
        /* Grid system moderno y escalable */
        .woocommerce ul.products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            align-items: start;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        /* Cada producto - diseño flexible */
        .woocommerce ul.products li.product {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .woocommerce ul.products li.product:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        /* Contenedor de imagen SIMPLIFICADO */
        .woocommerce ul.products li.product .woocommerce-loop-product__link {
            display: block;
            width: 100%;
            overflow: hidden;
            background: #f8f9fa;
        }
        
        /* Imágenes SIMPLIFICADAS - sin position absolute */
        .woocommerce ul.products li.product img,
        .woocommerce .products .product img {
            width: 100% !important;
            height: auto !important;
            display: block;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Efecto hover simple */
        .woocommerce ul.products li.product .woocommerce-loop-product__link:hover img {
            transform: scale(1.05);
        }
        
        /* Tu shortcode hover - SIMPLIFICADO */
        .hover-product-image-wrapper {
            position: relative;
            display: block;
            width: 100%;
            overflow: hidden;
            background: #f8f9fa;
        }

        .hover-product-image {
            position: relative;
            display: block;
            width: 100%;
            overflow: hidden;
        }

        .hover-product-image img {
            width: 100% !important;
            height: auto !important;
            display: block;
            transition: opacity 0.4s ease-in-out;
        }

        .hover-product-image .secondary-image {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.4s ease-in-out;
        }

        .hover-product-image:hover .primary-image {
            opacity: 0;
        }

        .hover-product-image:hover .secondary-image {
            opacity: 1;
        }
        
        /* Contenido del producto */
        .woocommerce ul.products li.product .woocommerce-loop-product__title,
        .woocommerce .products .product .woocommerce-loop-product__title {
            padding: 1rem 1rem 0.5rem;
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.3;
            color: #333;
        }
        
        .woocommerce ul.products li.product .price {
            padding: 0 1rem 1rem;
            margin: 0;
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .woocommerce ul.products li.product .button {
            margin: 0 1rem 1rem;
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        /* RESPONSIVE SIMPLIFICADO */
        @media (min-width: 1200px) {
            .woocommerce ul.products {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 2.5rem;
            }
        }
        
        @media (max-width: 1199px) and (min-width: 768px) {
            .woocommerce ul.products {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
            }
        }
        
        /* TABLET */
        @media (max-width: 767px) and (min-width: 481px) {
            .woocommerce ul.products {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
            
            .woocommerce ul.products li.product .woocommerce-loop-product__title {
                font-size: 1rem;
                padding: 0.8rem 0.8rem 0.4rem;
            }
            
            .woocommerce ul.products li.product .price {
                padding: 0 0.8rem 0.8rem;
                font-size: 1.1rem;
            }
            
            .woocommerce ul.products li.product .button {
                margin: 0 0.8rem 0.8rem;
                padding: 0.7rem 1.2rem;
                font-size: 0.9rem;
            }
        }
        
        /* MÓVIL - SIMPLIFICADO */
        @media (max-width: 480px) {
            .woocommerce ul.products {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 1rem;
            }
            
            .woocommerce ul.products li.product {
                border-radius: 6px;
                margin-bottom: 0;
            }
            
            /* Imágenes en móvil - altura fija simple */
            .woocommerce ul.products li.product img,
            .woocommerce .products .product img,
            .hover-product-image img {
                width: 100% !important;
                height: auto !important; /* Altura fija en móvil */
                object-fit: cover !important;
                object-position: center !important;
            }
            
            .woocommerce ul.products li.product .woocommerce-loop-product__title {
                font-size: 0.95rem;
                padding: 0.7rem 0.7rem 0.3rem;
                line-height: 1.2;
            }
            
            .woocommerce ul.products li.product .price {
                padding: 0 0.7rem 0.7rem;
                font-size: 1rem;
            }
            
            .woocommerce ul.products li.product .button {
                margin: 0 0.7rem 0.7rem;
                padding: 0.8rem 1rem;
                font-size: 0.85rem;
                width: calc(100% - 1.4rem);
                text-align: center;
            }
        }
        
        /* MÓVIL PEQUEÑO */
        @media (max-width: 360px) {
            .woocommerce ul.products {
                padding: 0 0.5rem;
                gap: 1rem;
            }
            
            .woocommerce ul.products li.product img,
            .woocommerce .products .product img,
            .hover-product-image img {
                height: 200px !important; /* Altura más pequeña */
            }
            
            .woocommerce ul.products li.product .woocommerce-loop-product__title {
                font-size: 0.9rem;
                padding: 0.6rem 0.6rem 0.2rem;
            }
            
            .woocommerce ul.products li.product .price {
                padding: 0 0.6rem 0.6rem;
                font-size: 0.95rem;
            }
            
            .woocommerce ul.products li.product .button {
                margin: 0 0.6rem 0.6rem;
                padding: 0.7rem 0.8rem;
                font-size: 0.8rem;
            }
        }
        
        /* Optimización para lazy loading */
        .woocommerce ul.products li.product img[loading="lazy"] {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .woocommerce ul.products li.product img[loading="lazy"].loaded {
            opacity: 1;
        }
        
        /* Accesibilidad mejorada */
        .woocommerce ul.products li.product .woocommerce-loop-product__link:focus,
        .hover-product-image:focus {
            outline: 2px solid #007cba;
            outline-offset: 2px;
        }
        </style>
        <?php
    }
});

// Optimizar carga de imágenes
add_filter('wp_get_attachment_image_attributes', function($attr, $attachment, $size) {
    if (is_shop() || is_product_category() || is_product_tag()) {
        // Agregar lazy loading nativo
        $attr['loading'] = 'lazy';
        
        // Agregar sizes para responsive
        if ($size === 'product-grid') {
            $attr['sizes'] = '(max-width: 480px) 200px, (max-width: 767px) 240px, (max-width: 1199px) 280px, 320px';
        }
    }
    return $attr;
}, 10, 3);

// JavaScript para mejorar la experiencia
add_action('wp_footer', function() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        ?>
        <script>
        jQuery(function($) {
            // Mejorar lazy loading
            $('img[loading="lazy"]').on('load', function() {
                $(this).addClass('loaded');
            });
            
            // Optimizar hover en móviles
            if ('ontouchstart' in window) {
                $('.woocommerce ul.products li.product').on('touchstart', function() {
                    $(this).addClass('touch-hover');
                }).on('touchend', function() {
                    setTimeout(() => {
                        $(this).removeClass('touch-hover');
                    }, 300);
                });
            }
        });
        </script>
        <?php
    }
});


// --- INICIA: IMPORTACIÓN AUTOMÁTICA DE PLANTILLAS ELEMENTOR ---

// Hook para importar plantillas al activar el plugin
register_activation_hook( __FILE__, 'woo_custom_enhancements_importar_elementor_templates' );

function woo_custom_enhancements_importar_elementor_templates() {
    // Evitar cualquier salida durante la activación
    ob_start();
    
    // Debug: Log de inicio
    error_log('Woo Custom Enhancements: Iniciando importación de plantillas');
    
    // Verificar que Elementor esté activo
    if ( ! class_exists( '\Elementor\Plugin' ) ) {
        error_log('Woo Custom Enhancements: Elementor no está activo');
        ob_end_clean();
        return;
    }

    $templates_dir = plugin_dir_path( __FILE__ ) . 'templates/';
    error_log('Woo Custom Enhancements: Buscando plantillas en: ' . $templates_dir);
    
    // Verificar que el directorio existe
    if ( ! is_dir( $templates_dir ) ) {
        error_log('Woo Custom Enhancements: El directorio templates/ no existe');
        ob_end_clean();
        return;
    }
    
    $files = glob( $templates_dir . '*.json' );
    error_log('Woo Custom Enhancements: Archivos encontrados: ' . count($files));

    if ( empty( $files ) ) {
        error_log('Woo Custom Enhancements: No se encontraron archivos JSON en la carpeta templates/');
        ob_end_clean();
        return;
    }

    $imported_count = 0;
    $skipped_count = 0;
    $errors = array();

    foreach ( $files as $file ) {
        $filename = basename($file);
        error_log('Woo Custom Enhancements: Procesando archivo: ' . $filename);
        
        if ( ! file_exists( $file ) ) {
            $errors[] = 'El archivo no existe: ' . $filename;
            continue;
        }
        
        $content = file_get_contents( $file );
        if ( $content === false ) {
            $errors[] = 'No se pudo leer el archivo: ' . $filename;
            continue;
        }
        
        $data = json_decode( $content, true );
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            $errors[] = 'Error JSON en archivo ' . $filename . ': ' . json_last_error_msg();
            continue;
        }

        if ( ! $data || ! isset( $data['title'] ) ) {
            $errors[] = 'Datos inválidos en archivo: ' . $filename;
            continue;
        }

        error_log('Woo Custom Enhancements: Procesando plantilla: ' . $data['title']);

        // Verificar si la plantilla ya existe usando WP_Query
        $existing_query = new WP_Query(array(
            'post_type' => 'elementor_library',
            'post_status' => array('publish', 'draft', 'private'),
            'title' => $data['title'],
            'posts_per_page' => 1,
            'fields' => 'ids'
        ));
        
        if ($existing_query->have_posts()) {
            $existing_query->the_post();
            $existing_post_id = get_the_ID();
            error_log('Woo Custom Enhancements: La plantilla ya existe con ID: ' . $existing_post_id . ' - Título: ' . $data['title']);
            wp_reset_postdata();
            $skipped_count++;
            continue; // Ya existe, saltar
        }

        // Crear la plantilla
        $post_data = array(
            'post_title'   => $data['title'],
            'post_type'    => 'elementor_library',
            'post_status'  => 'publish',
            'post_content' => '',
            'meta_input'   => array()
        );
        
        $post_id = wp_insert_post( $post_data );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            error_log('Woo Custom Enhancements: Post creado con ID: ' . $post_id);
            
            // Guardar los datos de Elementor
            if ( isset( $data['content'] ) ) {
                $elementor_data = wp_slash( json_encode( $data['content'] ) );
                update_post_meta( $post_id, '_elementor_data', $elementor_data );
                error_log('Woo Custom Enhancements: Datos de Elementor guardados');
            }
            
            if ( isset( $data['page_settings'] ) ) {
                update_post_meta( $post_id, '_elementor_page_settings', $data['page_settings'] );
            }
            
            if ( isset( $data['type'] ) ) {
                update_post_meta( $post_id, '_elementor_template_type', $data['type'] );
                error_log('Woo Custom Enhancements: Tipo de plantilla: ' . $data['type']);
            }

            // Marcar como editado con Elementor
            update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
            
            // Obtener versión de Elementor de forma segura
            $elementor_version = '3.0.0'; // Versión por defecto
            if ( defined( 'ELEMENTOR_VERSION' ) ) {
                $elementor_version = ELEMENTOR_VERSION;
            }
            update_post_meta( $post_id, '_elementor_version', $elementor_version );

            $imported_count++;
            error_log('Woo Custom Enhancements: Plantilla importada exitosamente: ' . $data['title']);
        } else {
            $errors[] = 'Error al crear post para: ' . $data['title'];
            if ( is_wp_error( $post_id ) ) {
                $errors[] = 'Error WP: ' . $post_id->get_error_message();
            }
        }
    }

    // Marcar que las plantillas fueron procesadas
    update_option( 'woo_custom_enhancements_templates_imported', true );
    update_option( 'woo_custom_enhancements_last_import', current_time('timestamp') );
    
    error_log('Woo Custom Enhancements: Importación completada. Importadas: ' . $imported_count . ', Omitidas: ' . $skipped_count);
    
    // Mostrar mensaje de éxito en admin
    add_action( 'admin_notices', function() use ( $imported_count, $skipped_count, $errors ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>Woo Custom Enhancements:</strong> Procesamiento de plantillas completado.</p>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>✅ Plantillas importadas: <strong><?php echo $imported_count; ?></strong></li>
                <li>⏭️ Plantillas omitidas (ya existían): <strong><?php echo $skipped_count; ?></strong></li>
                <?php if ( ! empty( $errors ) ): ?>
                    <li>❌ Errores: <strong><?php echo count($errors); ?></strong></li>
                <?php endif; ?>
            </ul>
            <?php if ( ! empty( $errors ) ): ?>
                <details style="margin-top: 10px;">
                    <summary style="cursor: pointer; font-weight: bold;">Ver errores detallados</summary>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        <?php foreach ( $errors as $error ): ?>
                            <li style="color: #d63638;"><?php echo esc_html( $error ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>
            <?php endif; ?>
        </div>
        <?php
    });
    
    // Limpiar cualquier salida buffer
    ob_end_clean();
}

// Función para limpiar las plantillas al desactivar el plugin (opcional)
register_deactivation_hook( __FILE__, 'woo_custom_enhancements_limpiar_templates' );

function woo_custom_enhancements_limpiar_templates() {
    // Comentado por seguridad - descomenta si quieres que se eliminen al desactivar
    /*
    $templates_dir = plugin_dir_path( __FILE__ ) . 'templates/';
    $files = glob( $templates_dir . '*.json' );

    foreach ( $files as $file ) {
        $content = file_get_contents( $file );
        $data = json_decode( $content, true );

        if ( $data && isset( $data['title'] ) ) {
            $post = get_page_by_title( $data['title'], OBJECT, 'elementor_library' );
            if ( $post ) {
                wp_delete_post( $post->ID, true );
            }
        }
    }
    
    delete_option( 'woo_custom_enhancements_templates_imported' );
    */
}



// Agregar enlace para reimportar en la página de plugins (solo para administradores)
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woo_custom_enhancements_plugin_links' );

function woo_custom_enhancements_plugin_links( $links ) {
    if ( current_user_can( 'manage_options' ) ) {
        $fix_elementor_link = '<a href="' . admin_url( 'admin-post.php?action=fix_elementor_compatibility' ) . '" onclick="return confirm(\'¿Aplicar fix de compatibilidad de Elementor?\')" style="color: #0073aa;">Fix Elementor</a>';
        $force_reimport_link = '<a href="' . admin_url( 'admin-post.php?action=force_reimport_elementor_templates' ) . '" onclick="return confirm(\'⚠️ ATENCIÓN: Esto eliminará las plantillas existentes y las volverá a crear. ¿Continuar?\')" style="color: #d63638;">Forzar Reimportación</a>';
        $reimport_link = '<a href="' . admin_url( 'admin-post.php?action=reimport_elementor_templates' ) . '" onclick="return confirm(\'¿Estás seguro de que quieres reimportar las plantillas?\')">Reimportar Plantillas</a>';
        $check_link = '<a href="' . admin_url( 'admin-post.php?action=check_elementor_templates' ) . '">Verificar Plantillas</a>';
        array_unshift( $links, $check_link );
        array_unshift( $links, $reimport_link );
        array_unshift( $links, $force_reimport_link );
        array_unshift( $links, $fix_elementor_link );
    }
    return $links;
}

// Función para verificar plantillas existentes
add_action( 'admin_post_check_elementor_templates', 'woo_custom_enhancements_check_templates' );

function woo_custom_enhancements_check_templates() {
    // Verificar permisos de administrador
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'No tienes permisos para realizar esta acción.' );
    }

    error_log('Woo Custom Enhancements: Verificando plantillas existentes');

    // Buscar todas las plantillas de Elementor
    $templates_query = new WP_Query(array(
        'post_type' => 'elementor_library',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ));

    $found_templates = array();
    
    if ($templates_query->have_posts()) {
        while ($templates_query->have_posts()) {
            $templates_query->the_post();
            $post_id = get_the_ID();
            $title = get_the_title();
            $template_type = get_post_meta($post_id, '_elementor_template_type', true);
            $found_templates[] = array(
                'id' => $post_id,
                'title' => $title,
                'type' => $template_type,
                'url' => get_edit_post_link($post_id)
            );
            error_log('Woo Custom Enhancements: Plantilla encontrada - ID: ' . $post_id . ', Título: ' . $title . ', Tipo: ' . $template_type);
        }
        wp_reset_postdata();
    }

    // Guardar resultado en una opción temporal para mostrarlo después
    update_option( 'woo_custom_enhancements_check_result', array(
        'templates' => $found_templates,
        'timestamp' => current_time('timestamp')
    ));

    // Redirigir de vuelta
    wp_redirect( admin_url( 'plugins.php?woo_check_templates=1' ) );
    exit;
}

// Función para reimportar plantillas manualmente
add_action( 'admin_post_reimport_elementor_templates', 'woo_custom_enhancements_reimportar_templates' );

function woo_custom_enhancements_reimportar_templates() {
    // Verificar permisos de administrador
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'No tienes permisos para realizar esta acción.' );
    }

    error_log('Woo Custom Enhancements: Reimportación manual iniciada');

    // Limpiar la marca de importación para forzar reimportación
    delete_option( 'woo_custom_enhancements_templates_imported' );
    
    // Ejecutar importación
    woo_custom_enhancements_importar_elementor_templates();
    
    // Redirigir de vuelta
    wp_redirect( admin_url( 'plugins.php?woo_reimport_templates=1' ) );
    exit;
}

// Función para aplicar fix de Elementor manualmente
add_action( 'admin_post_fix_elementor_compatibility', 'woo_custom_enhancements_aplicar_fix_elementor' );

function woo_custom_enhancements_aplicar_fix_elementor() {
    // Verificar permisos de administrador
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'No tienes permisos para realizar esta acción.' );
    }

    error_log('Woo Custom Enhancements: Aplicando fix de Elementor manualmente');

    // Verificar si existe el archivo problemático
    $elementor_pro_file = WP_PLUGIN_DIR . '/elementor-pro/modules/loop-builder/skins/skin-loop-base.php';
    
    if (!file_exists($elementor_pro_file)) {
        wp_redirect( admin_url( 'plugins.php?woo_fix_elementor_error=1' ) );
        exit;
    }
    
    // Leer el contenido del archivo
    $content = file_get_contents($elementor_pro_file);
    
    // Buscar la línea problemática
    if (strpos($content, '$document->print_content();') !== false) {
        // Aplicar el fix de compatibilidad
        $fixed_content = str_replace(
            '$document->print_content();',
            'if (method_exists($document, \'print_content\')) {
                $document->print_content();
            } elseif (method_exists($document, \'get_content\')) {
                echo $document->get_content();
            }',
            $content
        );
        
        // Escribir el archivo corregido
        if (file_put_contents($elementor_pro_file, $fixed_content)) {
            update_option('woo_custom_enhancements_elementor_fix_applied', true);
            error_log('Woo Custom Enhancements: Fix de compatibilidad aplicado manualmente');
            wp_redirect( admin_url( 'plugins.php?woo_fix_elementor_success=1' ) );
        } else {
            wp_redirect( admin_url( 'plugins.php?woo_fix_elementor_error=1' ) );
        }
    } else {
        wp_redirect( admin_url( 'plugins.php?woo_fix_elementor_already=1' ) );
    }
    
    exit;
}

// Función para forzar reimportación completa (elimina plantillas existentes)
add_action( 'admin_post_force_reimport_elementor_templates', 'woo_custom_enhancements_forzar_reimportar_templates' );

function woo_custom_enhancements_forzar_reimportar_templates() {
    // Verificar permisos de administrador
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'No tienes permisos para realizar esta acción.' );
    }

    error_log('Woo Custom Enhancements: Reimportación forzada iniciada');

    // Eliminar plantillas existentes del plugin
    $templates_dir = plugin_dir_path( __FILE__ ) . 'templates/';
    $files = glob( $templates_dir . '*.json' );
    
    foreach ( $files as $file ) {
        $content = file_get_contents( $file );
        if ( $content !== false ) {
            $data = json_decode( $content, true );
            if ( $data && isset( $data['title'] ) ) {
                // Buscar y eliminar plantillas existentes
                $existing_query = new WP_Query(array(
                    'post_type' => 'elementor_library',
                    'post_status' => array('publish', 'draft', 'private'),
                    'title' => $data['title'],
                    'posts_per_page' => 1,
                    'fields' => 'ids'
                ));
                
                if ($existing_query->have_posts()) {
                    $existing_query->the_post();
                    $post_id = get_the_ID();
                    wp_delete_post($post_id, true);
                    error_log('Woo Custom Enhancements: Plantilla eliminada: ' . $data['title'] . ' (ID: ' . $post_id . ')');
                    wp_reset_postdata();
                }
            }
        }
    }
    
    // Limpiar la marca de importación
    delete_option( 'woo_custom_enhancements_templates_imported' );
    
    // Ejecutar importación
    woo_custom_enhancements_importar_elementor_templates();
    
    // Redirigir de vuelta
    wp_redirect( admin_url( 'plugins.php?woo_force_reimport_templates=1' ) );
    exit;
}

// Mostrar mensajes de resultado en la página de plugins
add_action( 'admin_notices', 'woo_custom_enhancements_show_admin_messages' );

function woo_custom_enhancements_show_admin_messages() {
    // Solo mostrar en la página de plugins
    if ( ! isset( $_GET['page'] ) && ! isset( $_GET['woo_check_templates'] ) && ! isset( $_GET['woo_reimport_templates'] ) ) {
        return;
    }

    // Mostrar resultado de verificación
    if ( isset( $_GET['woo_check_templates'] ) ) {
        $check_result = get_option( 'woo_custom_enhancements_check_result' );
        if ( $check_result && isset( $check_result['templates'] ) ) {
            $found_templates = $check_result['templates'];
            ?>
            <div class="notice notice-info is-dismissible">
                <h3>🔍 Plantillas de Elementor Encontradas (<?php echo count($found_templates); ?>):</h3>
                <?php if ( ! empty( $found_templates ) ): ?>
                    <ul style="margin: 10px 0; list-style: disc; padding-left: 20px;">
                        <?php foreach ( $found_templates as $template ): ?>
                            <li style="margin-bottom: 8px;">
                                <strong><?php echo esc_html( $template['title'] ); ?></strong>
                                <?php if ( $template['type'] ): ?>
                                    <em style="color: #666;"> (<?php echo esc_html( $template['type'] ); ?>)</em>
                                <?php endif; ?>
                                - <a href="<?php echo esc_url( $template['url'] ); ?>" target="_blank" style="color: #0073aa; text-decoration: underline;">✏️ Editar</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div style="background: #f0f8ff; padding: 15px; border-left: 4px solid #0073aa; margin-top: 15px;">
                        <p style="margin: 0 0 10px 0;"><strong>📋 Para usar estas plantillas:</strong></p>
                        <ol style="margin: 0; padding-left: 20px;">
                            <li>Ve a <strong>Elementor → Plantillas → Guardadas</strong></li>
                            <li>O ve a <strong>Páginas → Añadir nueva</strong> y selecciona "Usar plantilla"</li>
                        </ol>
                    </div>
                <?php else: ?>
                    <p>❌ No se encontraron plantillas de Elementor.</p>
                <?php endif; ?>
            </div>
            <?php
            // Limpiar el resultado temporal
            delete_option( 'woo_custom_enhancements_check_result' );
        }
    }

    // Mostrar resultado de reimportación
    if ( isset( $_GET['woo_reimport_templates'] ) ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <h3>✅ Reimportación Completada</h3>
            <p>Se ha ejecutado la reimportación de plantillas. Revisa los logs de debug para más detalles.</p>
            <p><strong>💡 Consejo:</strong> Usa el botón "Verificar Plantillas" para ver qué plantillas están disponibles.</p>
        </div>
        <?php
    }

    // Mostrar resultado de reimportación forzada
    if ( isset( $_GET['woo_force_reimport_templates'] ) ) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <h3>⚠️ Reimportación Forzada Completada</h3>
            <p>Se han eliminado las plantillas existentes y se han vuelto a crear desde cero.</p>
            <p><strong>💡 Consejo:</strong> Usa el botón "Verificar Plantillas" para ver las plantillas recién creadas.</p>
        </div>
        <?php
    }

    // Mostrar resultado del fix de Elementor
    if ( isset( $_GET['woo_fix_elementor_success'] ) ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <h3>✅ Fix de Elementor Aplicado</h3>
            <p>Se ha aplicado el fix de compatibilidad a Elementor Pro. El error de <code>print_content()</code> debería estar solucionado.</p>
            <p><strong>💡 Consejo:</strong> Prueba ahora a editar una plantilla de productos en Elementor.</p>
        </div>
        <?php
    }

    if ( isset( $_GET['woo_fix_elementor_error'] ) ) {
        ?>
        <div class="notice notice-error is-dismissible">
            <h3>❌ Error al Aplicar Fix de Elementor</h3>
            <p>No se pudo aplicar el fix de compatibilidad. Verifica que Elementor Pro esté instalado y activo.</p>
        </div>
        <?php
    }

    if ( isset( $_GET['woo_fix_elementor_already'] ) ) {
        ?>
        <div class="notice notice-info is-dismissible">
            <h3>ℹ️ Fix de Elementor Ya Aplicado</h3>
            <p>El fix de compatibilidad ya está aplicado o no es necesario en tu versión de Elementor.</p>
        </div>
        <?php
    }
}

// --- FIN: IMPORTACIÓN AUTOMÁTICA DE PLANTILLAS ELEMENTOR ---

// --- INICIA: FIX DE COMPATIBILIDAD ELEMENTOR ---

// Fix para el error de compatibilidad entre Elementor Free y Pro
add_action('init', 'woo_custom_enhancements_fix_elementor_compatibility', 1);
function woo_custom_enhancements_fix_elementor_compatibility() {
    // Verificar si ya se aplicó el fix
    $fix_applied = get_option('woo_custom_enhancements_elementor_fix_applied', false);
    
    // Verificar si existe el archivo problemático
    $elementor_pro_file = WP_PLUGIN_DIR . '/elementor-pro/modules/loop-builder/skins/skin-loop-base.php';
    
    if (file_exists($elementor_pro_file) && !$fix_applied) {
        // Leer el contenido del archivo
        $content = file_get_contents($elementor_pro_file);
        
        // Buscar la línea problemática
        if (strpos($content, '$document->print_content();') !== false) {
            // Aplicar el fix de compatibilidad
            $fixed_content = str_replace(
                '$document->print_content();',
                'if (method_exists($document, \'print_content\')) {
                    $document->print_content();
                } elseif (method_exists($document, \'get_content\')) {
                    echo $document->get_content();
                }',
                $content
            );
            
            // Escribir el archivo corregido
            if (file_put_contents($elementor_pro_file, $fixed_content)) {
                update_option('woo_custom_enhancements_elementor_fix_applied', true);
                error_log('Woo Custom Enhancements: Fix de compatibilidad aplicado a Elementor Pro');
                
                // Mostrar mensaje de éxito
                add_action('admin_notices', function() {
                    ?>
                    <div class="notice notice-success is-dismissible">
                        <p><strong>Woo Custom Enhancements:</strong> ✅ Fix de compatibilidad aplicado a Elementor Pro. El error de <code>print_content()</code> ha sido solucionado.</p>
                    </div>
                    <?php
                });
            }
        }
    }
}

// Función para verificar versiones de Elementor
add_action('admin_notices', 'woo_custom_enhancements_check_elementor_versions');
function woo_custom_enhancements_check_elementor_versions() {
    // Solo mostrar en páginas de admin
    if (!is_admin()) return;
    
    // Verificar si Elementor está activo
    if (!class_exists('\Elementor\Plugin')) return;
    
    $elementor_version = defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'No detectada';
    $elementor_pro_version = defined('ELEMENTOR_PRO_VERSION') ? ELEMENTOR_PRO_VERSION : 'No detectada';
    
    // Mostrar información de versiones en la página de plugins
    if (isset($_GET['page']) && $_GET['page'] === 'plugins.php') {
        ?>
        <div class="notice notice-info is-dismissible">
            <h3>🔍 Información de Versiones de Elementor</h3>
            <p><strong>Elementor Free:</strong> <?php echo esc_html($elementor_version); ?></p>
            <p><strong>Elementor Pro:</strong> <?php echo esc_html($elementor_pro_version); ?></p>
            <?php if ($elementor_version !== 'No detectada' && $elementor_pro_version !== 'No detectada'): ?>
                <p><strong>Estado:</strong> 
                    <?php if (version_compare($elementor_version, $elementor_pro_version, '==')): ?>
                        ✅ Versiones compatibles
                    <?php else: ?>
                        ⚠️ Posible incompatibilidad de versiones
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }
}

// --- FIN: FIX DE COMPATIBILIDAD ELEMENTOR ---

// --- INICIA: WIDGET DE ELEMENTOR PARA FILTRO DE CATEGORÍAS ---

// AJAX handler para el filtro de categorías
add_action('wp_ajax_filter_products_by_category', 'handle_category_filter_ajax');
add_action('wp_ajax_nopriv_filter_products_by_category', 'handle_category_filter_ajax');

function handle_category_filter_ajax() {
    // Verificar nonce
    if (!wp_verify_nonce($_POST['nonce'], 'category_filter_nonce')) {
        wp_die('Error de seguridad');
    }

    $category_id = sanitize_text_field($_POST['category_id']);
    
    error_log('Filtro de categorías - ID recibido: ' . $category_id);
    
    // Determinar la URL de destino
    if ($category_id === '0' || $category_id === 'all') {
        // Mostrar todos los productos (página de shop)
        $url = wc_get_page_permalink('shop');
        error_log('Filtro de categorías - Redirigiendo a shop: ' . $url);
    } else {
        // Mostrar productos de la categoría específica
        $category = get_term($category_id, 'product_cat');
        if ($category && !is_wp_error($category)) {
            $url = get_term_link($category);
            error_log('Filtro de categorías - Redirigiendo a categoría: ' . $url);
        } else {
            $url = wc_get_page_permalink('shop');
            error_log('Filtro de categorías - Categoría no encontrada, redirigiendo a shop: ' . $url);
        }
    }

    // Actualizar la URL en el navegador sin recargar la página
    wp_send_json_success(array(
        'redirect' => false,
        'url' => $url,
        'message' => 'URL actualizada: ' . $url
    ));
}

// --- FIN: FILTRO HORIZONTAL DE CATEGORÍAS CON AJAX ---

// --- INICIA: WIDGET DE ELEMENTOR PARA FILTRO DE CATEGORÍAS ---

// Registrar el widget de Elementor
add_action('elementor/widgets/register', 'register_category_filter_elementor_widget', 20);

function register_category_filter_elementor_widget($widgets_manager) {
    // Verificar que el widget manager es válido
    if (!$widgets_manager || !is_object($widgets_manager)) {
        error_log('Error: Widget manager no es válido');
        return;
    }
    
    // Verificar que Elementor está completamente cargado
    if (!class_exists('\Elementor\Widget_Base') || !class_exists('\Elementor\Controls_Manager')) {
        error_log('Error: Elementor no está completamente cargado');
        return;
    }
    
    // Incluir el archivo del widget solo cuando Elementor esté cargado
    $widget_file = plugin_dir_path(__FILE__) . 'includes/elementor-category-filter-widget.php';
    if (!file_exists($widget_file)) {
        error_log('Error: Archivo del widget no encontrado: ' . $widget_file);
        return;
    }
    
    require_once $widget_file;
    
    // Verificar que la clase del widget existe después de incluir el archivo
    if (!class_exists('Elementor_Category_Filter_Widget')) {
        error_log('Error: Clase Elementor_Category_Filter_Widget no encontrada después de incluir archivo');
        return;
    }
    
    // Registrar el widget
    try {
        $widget_instance = new Elementor_Category_Filter_Widget();
        $widgets_manager->register($widget_instance);
        error_log('Widget de filtro de categorías registrado correctamente');
    } catch (Exception $e) {
        error_log('Error al registrar widget: ' . $e->getMessage());
    }
}

// --- FIN: WIDGET DE ELEMENTOR PARA FILTRO DE CATEGORÍAS ---