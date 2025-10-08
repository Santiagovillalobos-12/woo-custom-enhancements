<?php
/**
 * Widget de Elementor para Filtro de Categorías
 * Archivo: includes/elementor-category-filter-widget.php
 */

if (!defined('ABSPATH')) exit;

class Elementor_Category_Filter_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'category_filter_horizontal';
    }

    public function get_title()
    {
        return esc_html__('Filtro de Categorías', 'woo-custom-enhancements');
    }

    public function get_icon()
    {
        return 'eicon-filter';
    }

    public function get_categories()
    {
        return ['general', 'woocommerce-elements'];
    }

    public function get_keywords()
    {
        return ['categorías', 'filtro', 'woocommerce', 'productos', 'shop'];
    }

    protected function register_controls()
    {
        // Cargar controles desde archivo separado
        require_once plugin_dir_path(__FILE__) . 'elementor-category-filter-widget-controls.php';
        Elementor_Category_Filter_Widget_Controls::register_controls($this);
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (!is_shop() && !is_product_category()) {
            return;
        }

        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0,
            'orderby' => 'name',
            'order' => 'ASC'
        ]);

        if (empty($categories) || is_wp_error($categories)) {
            return;
        }

        $show_all = $settings['show_all_option'] === 'yes';
        $all_text = $settings['all_option_text'] ?: 'Todas';
        $show_count = $settings['show_product_count'] === 'yes';
?>

         <div class="category-filter-horizontal-wrapper">
             <div class="category-filter-horizontal">
                 <?php if ($show_all): ?>
                     <div class="category-item <?php echo !is_product_category() ? 'active' : ''; ?>"
                         data-category="0">
                         <span class="category-name"><?php echo esc_html($all_text); ?></span>
                         <?php if ($show_count): ?>
                             <span class="category-count"><?php echo wp_count_posts('product')->publish; ?></span>
                         <?php endif; ?>
                     </div>
                 <?php endif; ?>

                 <?php foreach ($categories as $category):
                     $subcategories = get_terms([
                         'taxonomy' => 'product_cat',
                         'hide_empty' => true,
                         'parent' => $category->term_id,
                     ]);

                     $has_subs = !empty($subcategories) && !is_wp_error($subcategories);
                     $is_active = is_product_category() && get_queried_object_id() == $category->term_id;
                 ?>
                     <div class="category-item <?php echo $is_active ? 'active' : ''; ?> <?php echo $has_subs ? 'has-subcategories' : ''; ?>"
                         data-category="<?php echo esc_attr($category->term_id); ?>">

                         <div class="category-main">
                             <span class="category-name"><?php echo esc_html($category->name); ?></span>
                             <?php if ($show_count): ?>
                                 <span class="category-count"><?php echo $category->count; ?></span>
                             <?php endif; ?>
                             <?php if ($has_subs): ?>
                                 <span class="subcategory-indicator">
                                     <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                         <path d="M6 9l6 6 6-6"/>
                                     </svg>
                                 </span>
                             <?php endif; ?>
                         </div>

                         <?php if ($has_subs): ?>
                             <div class="subcategories-dropdown">
                                 <div class="subcategory-item" data-category="<?php echo esc_attr($category->term_id); ?>">
                                     <span class="subcategory-name">Ver todos</span>
                                     <?php if ($show_count): ?>
                                         <span class="subcategory-count"><?php echo $category->count; ?></span>
                                     <?php endif; ?>
                                 </div>
                                 <?php foreach ($subcategories as $sub): ?>
                                     <div class="subcategory-item" data-category="<?php echo esc_attr($sub->term_id); ?>">
                                         <span class="subcategory-name"><?php echo esc_html($sub->name); ?></span>
                                         <?php if ($show_count): ?>
                                             <span class="subcategory-count"><?php echo $sub->count; ?></span>
                                         <?php endif; ?>
                                     </div>
                                 <?php endforeach; ?>
                             </div>
                         <?php endif; ?>
                     </div>
                 <?php endforeach; ?>
             </div>
         </div>

        <style>
            /* Reset importante para Elementor */
            .elementor-widget-category_filter_horizontal {
                overflow: visible !important;
            }
            
            .elementor-widget-category_filter_horizontal .elementor-widget-container {
                overflow: visible !important;
            }

            .category-filter-horizontal-wrapper {
                background: #fff;
                border-radius: 50px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
                position: relative;
                z-index: 1000;
                padding: 8px 12px;
                width: 100%;
                box-sizing: border-box;
                overflow: hidden;
            }

            /* SCROLL HORIZONTAL PURO CON CSS */
            .category-filter-horizontal {
                display: flex;
                align-items: center;
                flex-wrap: nowrap;
                overflow-x: auto;
                overflow-y: hidden;
                gap: 6px;
                scrollbar-width: none;
                -ms-overflow-style: none;
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
                padding: 2px 0;
                width: 100%;
                height: 100%;
                box-sizing: border-box;
            }

            /* Ocultar scrollbar pero mantener funcionalidad */
            .category-filter-horizontal::-webkit-scrollbar {
                display: none;
            }

            .category-item {
                flex-shrink: 0;
                min-width: max-content;
                padding: 8px 14px; /* 👈 REDUCIDO para más elementos visibles */
                border-radius: 25px;
                background: #fff;
                border: 1px solid #e0e0e0;
                white-space: nowrap;
                cursor: pointer;
                transition: all 0.3s ease;
                text-align: center;
                position: relative;
                display: flex;
                align-items: center;
                gap: 4px; /* 👈 REDUCIDO */
            }

            .category-item:hover {
                transform: translateY(-1px);
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
                border-color: #007cba;
            }

            .category-item.active {
                background: #007cba;
                color: white;
                border-color: #007cba;
                box-shadow: 0 3px 10px rgba(0, 124, 186, 0.3);
            }

            .category-item.active .category-count {
                color: rgba(255, 255, 255, 0.9);
            }

            .category-main {
                display: flex;
                align-items: center;
                gap: 4px; /* 👈 REDUCIDO */
            }

            .subcategory-indicator {
                display: flex;
                align-items: center;
                opacity: 0.7;
                margin-left: 2px;
            }

            .category-item.active .subcategory-indicator {
                color: white;
            }

            .category-name {
                font-weight: 500;
                font-size: 13px; /* 👈 LIGERAMENTE MÁS PEQUEÑO */
                line-height: 1.2;
            }

            .category-count {
                font-size: 10px; /* 👈 MÁS PEQUEÑO */
                color: #777;
                font-weight: 400;
                background: rgba(0, 0, 0, 0.04);
                padding: 1px 6px; /* 👈 REDUCIDO */
                border-radius: 8px;
                line-height: 1.2;
            }

            .category-item.active .category-count {
                background: rgba(255, 255, 255, 0.2);
            }

            .subcategories-dropdown {
                position: absolute;
                background: #fff;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: all 0.3s ease;
                z-index: 99999;
                max-height: 300px;
                overflow-y: auto;
                min-width: 180px;
                top: calc(100% + 5px);
                left: 0;
            }

            .subcategories-dropdown.active {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }

            .subcategory-item {
                padding: 10px 16px;
                cursor: pointer;
                transition: background 0.2s;
                border-bottom: 1px solid #f5f5f5;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .subcategory-item:last-child {
                border-bottom: none;
            }

            .subcategory-item:hover {
                background: #f8f9fa;
            }

            .subcategory-name {
                font-size: 13px;
                font-weight: 500;
                color: #333;
            }

            .subcategory-count {
                font-size: 10px;
                color: #666;
                background: #f0f0f0;
                padding: 2px 8px;
                border-radius: 10px;
                margin-left: 8px;
            }

            .category-filter-loading {
                opacity: 0.6;
                pointer-events: none;
            }

            .category-filter-loading::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 20px;
                height: 20px;
                margin: -10px 0 0 -10px;
                border: 2px solid #f3f3f3;
                border-top: 2px solid #007cba;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @media (max-width: 768px) {
                .category-filter-horizontal-wrapper {
                    border-radius: 30px;
                    padding: 6px 8px;
                }

                .category-filter-horizontal {
                    gap: 4px;
                    padding: 1px 0;
                }

                .category-name {
                    font-size: 12px;
                }

                .category-item {
                    padding: 6px 12px;
                    min-width: auto;
                }

                .category-count {
                    font-size: 9px;
                    padding: 1px 4px;
                }
            }

            @media (max-width: 480px) {
                .category-filter-horizontal-wrapper {
                    border-radius: 25px;
                    padding: 5px 6px;
                }

                .category-item {
                    padding: 5px 10px;
                }

                .category-name {
                    font-size: 11px;
                }

                .category-count {
                    font-size: 8px;
                }

                .category-filter-horizontal {
                    gap: 3px;
                }
            }
        </style>

        <script>
            jQuery(document).ready(function($) {
                var hoverTimeout;
                var isHoveringDropdown = false;

                // Función de filtrado
                function filterByCategory(categoryId) {
                    var $wrapper = $('.category-filter-horizontal-wrapper');
                    $wrapper.addClass('category-filter-loading');

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'filter_products_by_category',
                            category_id: categoryId,
                            nonce: '<?php echo wp_create_nonce('category_filter_nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success && response.data.url) {
                                window.location.href = response.data.url;
                            }
                        },
                        error: function() {
                            $wrapper.removeClass('category-filter-loading');
                        }
                    });
                }

                // Eventos de click
                $('.subcategory-item').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var categoryId = $(this).data('category');
                    filterByCategory(categoryId);
                });

                $('.category-item .category-main').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var categoryId = $(this).parent().data('category');
                    filterByCategory(categoryId);
                });

                $('.category-item:not(.has-subcategories)').on('click', function(e) {
                    e.preventDefault();
                    var categoryId = $(this).data('category');
                    filterByCategory(categoryId);
                });

                // Hover para dropdown
                $('.category-item.has-subcategories').on('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                    var $dropdown = $(this).find('.subcategories-dropdown');
                    $('.subcategories-dropdown').removeClass('active');
                    $dropdown.addClass('active');
                    isHoveringDropdown = false;
                }).on('mouseleave', function() {
                    var $dropdown = $(this).find('.subcategories-dropdown');
                    hoverTimeout = setTimeout(function() {
                        if (!isHoveringDropdown) {
                            $dropdown.removeClass('active');
                        }
                    }, 300);
                });

                $('.subcategories-dropdown').on('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                    isHoveringDropdown = true;
                    $(this).addClass('active');
                }).on('mouseleave', function() {
                    isHoveringDropdown = false;
                    var self = this;
                    hoverTimeout = setTimeout(function() {
                        $(self).removeClass('active');
                    }, 100);
                });
            });
        </script>
<?php
    }
}