<?php
/**
 * Widget de Elementor para Filtro de Categorías
 * Archivo: includes/elementor-category-filter-widget.php
 */

if (!defined('ABSPATH')) exit;

class Elementor_Category_Filter_Widget extends \Elementor\Widget_Base {

    public function get_name() { 
        return 'category_filter_horizontal'; 
    }
    
    public function get_title() { 
        return esc_html__('Filtro de Categorías', 'woo-custom-enhancements'); 
    }
    
    public function get_icon() { 
        return 'eicon-filter'; 
    }
    
    public function get_categories() { 
        return ['general', 'woocommerce-elements']; 
    }
    
    public function get_keywords() { 
        return ['categorías', 'filtro', 'woocommerce', 'productos', 'shop']; 
    }

    protected function register_controls() {
        
        // Sección de Contenido
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Contenido', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_all_option',
            [
                'label' => esc_html__('Mostrar opción "Todas"', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'woo-custom-enhancements'),
                'label_off' => esc_html__('No', 'woo-custom-enhancements'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'all_option_text',
            [
                'label' => esc_html__('Texto de "Todas"', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Todas', 'woo-custom-enhancements'),
                'condition' => [
                    'show_all_option' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_product_count',
            [
                'label' => esc_html__('Mostrar contador de productos', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'woo-custom-enhancements'),
                'label_off' => esc_html__('No', 'woo-custom-enhancements'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Sección de Estilo - Contenedor
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => esc_html__('Contenedor', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => esc_html__('Fondo', 'woo-custom-enhancements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .category-filter-horizontal-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Relleno', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .category-filter-horizontal-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_border_radius',
            [
                'label' => esc_html__('Radio del borde', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .category-filter-horizontal-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Sección de Estilo - Items
        $this->start_controls_section(
            'item_style_section',
            [
                'label' => esc_html__('Items de Categoría', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Relleno', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .category-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_background',
            [
                'label' => esc_html__('Fondo normal', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .category-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_background_hover',
            [
                'label' => esc_html__('Fondo hover', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .category-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_background_active',
            [
                'label' => esc_html__('Fondo activo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .category-item.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Sección de Estilo - Texto
        $this->start_controls_section(
            'text_style_section',
            [
                'label' => esc_html__('Texto', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'category_name_typography',
                'label' => esc_html__('Tipografía del nombre', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-name',
            ]
        );

        $this->add_control(
            'category_name_color',
            [
                'label' => esc_html__('Color del nombre', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .category-item .category-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_name_color_active',
            [
                'label' => esc_html__('Color del nombre (activo)', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .category-item.active .category-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
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
        .category-filter-horizontal-wrapper {
            margin: 20px 0 30px 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: visible !important;
            position: relative;
            z-index: 1000;
        }
        .category-filter-horizontal {
            display: flex;
            overflow-x: auto;
            overflow-y: visible !important;
            scrollbar-width: thin;
            scrollbar-color: #ddd transparent;
        }
        
        /* Forzar que todos los contenedores padre permitan overflow visible */
        .elementor-widget-category_filter_horizontal {
            overflow: visible !important;
        }
        
        .elementor-widget-category_filter_horizontal .elementor-widget-container {
            overflow: visible !important;
        }
        
        .elementor-widget-category_filter_horizontal .elementor-widget-container * {
            overflow: visible !important;
        }
        
        /* Asegurar que el contenedor del filtro permita que los dropdowns se vean */
        .category-filter-horizontal-wrapper,
        .category-filter-horizontal,
        .category-item {
            overflow: visible !important;
        }
        .category-filter-horizontal::-webkit-scrollbar { height: 6px; }
        .category-filter-horizontal::-webkit-scrollbar-track { background: #f1f1f1; }
        .category-filter-horizontal::-webkit-scrollbar-thumb { background: #ddd; border-radius: 3px; }
        .category-item {
            position: relative;
            flex-shrink: 0;
            min-width: 160px;
            padding: 20px 25px;
            cursor: pointer;
            transition: all 0.3s;
            border-right: 1px solid #f0f0f0;
            background: #fff;
            text-align: center;
        }
        .category-item:hover { background: #f8f9fa; }
        .category-item.active {
            background: #007cba;
            color: #fff;
        }
        .category-item.active .category-name,
        .category-item.active .category-count { color: #fff; }
        .category-name {
            display: block;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
            text-transform: uppercase;
        }
        .category-count {
            display: block;
            font-size: 12px;
            color: #666;
        }
        .subcategories-dropdown {
            position: absolute;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 99999;
            max-height: 400px;
            overflow-y: auto;
            min-width: 200px;
            top: 100%;
            left: 0;
        }
        .subcategories-dropdown.active {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }
        .subcategory-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
        }
        .subcategory-item:hover { background: #f8f9fa; }
        .subcategory-name {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }
        .subcategory-count {
            font-size: 11px;
            color: #666;
            background: #f0f0f0;
            padding: 2px 8px;
            border-radius: 10px;
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
            .category-item {
                min-width: 140px;
                padding: 15px 20px;
            }
            .category-name { font-size: 14px; }
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('.category-item, .subcategory-item').on('click', function(e) {
                e.preventDefault();
                var categoryId = $(this).data('category');
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
            });
            
            $('.category-item.has-subcategories').on('mouseenter', function() {
                var $dropdown = $(this).find('.subcategories-dropdown');
                $('.subcategories-dropdown').removeClass('active');
                $dropdown.addClass('active');
            }).on('mouseleave', function() {
                var $dropdown = $(this).find('.subcategories-dropdown');
                setTimeout(function() {
                    if (!$dropdown.is(':hover')) {
                        $dropdown.removeClass('active');
                    }
                }, 200);
            });
            
            $('.subcategories-dropdown').hover(
                function() { $(this).addClass('active'); },
                function() { $(this).removeClass('active'); }
            );
        });
        </script>
        <?php
    }
}