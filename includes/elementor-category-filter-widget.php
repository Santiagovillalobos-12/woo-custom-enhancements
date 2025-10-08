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
                border-radius: 16px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
                position: relative;
                z-index: 1000;
                padding: 8px;
                width: 100%;
                box-sizing: border-box;
            }

            .category-filter-horizontal {
               
                gap: 6px !important;
                width: 100%;
                box-sizing: border-box;
                align-items: center;
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                display: grid;
            }

            .category-item {
                position: relative;
                cursor: pointer;
                transition: all 0.3s ease;
                background: #fff;
                text-align: center;
                white-space: nowrap;
                padding: 6px 10px;
                border-radius: 16px;
                border: 1px solid #e0e0e0;
                flex-shrink: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .category-item:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
                border-color: #007cba;
                z-index: 10;
            }

            .category-item.active {
                background: #007cba;
                color: white;
                border-color: #007cba;
                box-shadow: 0 2px 8px rgba(0, 124, 186, 0.3);
            }

            .category-item.active .category-count {
                color: rgba(255, 255, 255, 0.9);
                background: rgba(255, 255, 255, 0.2);
            }

            .category-main {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 4px !important;
                width: 100%;
            }

            .category-name {
                font-size: 12px;
                font-weight: 600;
                color: #333;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                flex: 1;
                text-align: center;
            }

            .category-count {
                font-size: 9px;
                color: #666;
                font-weight: 500;
                background: #f5f5f5;
                padding: 2px 6px;
                border-radius: 8px;
                min-width: 18px;
            }

            .subcategories-dropdown {
                position: absolute;
                background: #fff;
                border: 1px solid #e0e0e0;
                border-radius: 12px;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
                opacity: 0;
                visibility: hidden;
                transform: translateY(8px);
                transition: all 0.3s ease;
                z-index: 1001;
                max-height: 300px;
                overflow-y: auto;
                min-width: 160px;
                top: calc(100% + 4px);
                left: 50%;
                transform: translateY(8px) translateX(-50%);
            }

            .subcategories-dropdown.active {
                opacity: 1;
                visibility: visible;
                transform: translateY(4px) translateX(-50%);
            }

            /* Ajustar dropdowns cerca del borde derecho */
            .category-item.has-subcategories.dropdown-right .subcategories-dropdown {
                left: auto !important;
                right: 0 !important;
                transform: translateY(8px) !important;
            }

            .category-item.has-subcategories.dropdown-right .subcategories-dropdown.active {
                transform: translateY(4px) !important;
            }

            .subcategory-item {
                padding: 10px 16px;
                cursor: pointer;
                transition: background 0.2s;
                border-bottom: 1px solid #f5f5f5;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 8px !important;
            }

            .subcategory-item:last-child {
                border-bottom: none;
            }

            .subcategory-item:hover {
                background: #f8f9fa;
            }

            .subcategory-name {
                font-size: 12px;
                font-weight: 500;
                color: #333;
                flex: 1;
                text-align: left;
            }

            .subcategory-count {
                font-size: 10px;
                color: #666;
                background: #f0f0f0;
                padding: 2px 8px;
                border-radius: 10px;
                min-width: 20px;
                text-align: center;
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
                width: 16px;
                height: 16px;
                margin: -8px 0 0 -8px;
                border: 2px solid #f3f3f3;
                border-top: 2px solid #007cba;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            /* Responsive - Flex optimizado */
            @media (min-width: 1200px) {
                .category-filter-horizontal {
                    gap: 8px !important;
                }
            }

            @media (max-width: 768px) {
                .category-filter-horizontal-wrapper {
                    border-radius: 12px;
                    padding: 6px;
                }

                .category-filter-horizontal {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    gap: 4px !important;
                    justify-content: center;
                }

                .category-item {
                    padding: 5px 8px;
                    border-radius: 12px;
                    width: 30%;
                }

                .category-name {
                    font-size: 11px;
                }

                .category-count {
                    font-size: 8px;
                    padding: 1px 4px;
                    min-width: 16px;
                }

                .subcategories-dropdown {
                    min-width: 140px;
                }

                .subcategory-item {
                    padding: 8px 12px;
                }

                .subcategory-name {
                    font-size: 11px;
                }

                .subcategory-count {
                    font-size: 9px;
                    padding: 1px 6px;
                }
            }

            @media (max-width: 480px) {
                .category-filter-horizontal-wrapper {
                    border-radius: 10px;
                    padding: 5px;
                }

                .category-filter-horizontal {
                    gap: 3px !important;
                }

                .category-item {
                    padding: 5px 8px;
                    border-radius: 14px;
                    min-height: 32px;
                }

                .category-name {
                    font-size: 10px;
                }

                .category-count {
                    font-size: 7px;
                    padding: 1px 3px;
                    min-width: 14px;
                }

                .subcategories-dropdown {
                    min-width: 120px;
                }

                .subcategory-item {
                    padding: 6px 10px;
                }

                .subcategory-name {
                    font-size: 10px;
                }
            }

            /* Para pantallas muy pequeñas */
            @media (max-width: 360px) {
                .category-filter-horizontal {
                    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                    gap: 4px !important;
                }

                .category-item {
                    padding: 4px 6px;
                    min-height: 30px;
                }

                .category-name {
                    font-size: 9px;
                }
            }
        </style>

        <script>
            jQuery(document).ready(function($) {
                var hoverTimeout;
                var isHoveringDropdown = false;

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

                // Click en subcategorías del dropdown
                $('.subcategory-item').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var categoryId = $(this).data('category');
                    filterByCategory(categoryId);
                });

                // Click en categorías principales (solo en el área del texto, no en todo el item)
                $('.category-item .category-main').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var categoryId = $(this).parent().data('category');
                    filterByCategory(categoryId);
                });

                // Click en categorías sin subcategorías
                $('.category-item:not(.has-subcategories)').on('click', function(e) {
                    e.preventDefault();
                    var categoryId = $(this).data('category');
                    filterByCategory(categoryId);
                });

                // Hover para mostrar dropdown
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
