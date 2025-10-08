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
        // ============ SECCIÓN DE CONTENIDO ============
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

        // ============ ESTILO - CONTENEDOR ============
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
                'label' => esc_html__('Relleno interno', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '15',
                    'right' => '20',
                    'bottom' => '15',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-filter-horizontal-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__('Margen externo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .category-filter-horizontal-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => esc_html__('Sombra', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-filter-horizontal-wrapper',
            ]
        );

        $this->end_controls_section();

        // ============ ESTILO - ITEMS DE CATEGORÍA ============
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
                'label' => esc_html__('Relleno del item', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '10',
                    'right' => '20',
                    'bottom' => '10',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => esc_html__('Espacio entre items', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-filter-horizontal' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => esc_html__('Radio de borde del item', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '6',
                    'bottom' => '6',
                    'left' => '6',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Tabs para estados: Normal, Hover, Active
        $this->start_controls_tabs('item_style_tabs');

        // TAB NORMAL
        $this->start_controls_tab(
            'item_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $this->add_control(
            'item_background',
            [
                'label' => esc_html__('Color de fondo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .category-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => esc_html__('Borde', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-item',
            ]
        );

        $this->end_controls_tab();

        // TAB HOVER
        $this->start_controls_tab(
            'item_hover_tab',
            [
                'label' => esc_html__('Hover', 'woo-custom-enhancements'),
            ]
        );

        $this->add_control(
            'item_background_hover',
            [
                'label' => esc_html__('Color de fondo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f0f0f0',
                'selectors' => [
                    '{{WRAPPER}} .category-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border_hover',
                'label' => esc_html__('Borde', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-item:hover',
            ]
        );

        $this->end_controls_tab();

        // TAB ACTIVE
        $this->start_controls_tab(
            'item_active_tab',
            [
                'label' => esc_html__('Activo', 'woo-custom-enhancements'),
            ]
        );

        $this->add_control(
            'item_background_active',
            [
                'label' => esc_html__('Color de fondo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .category-item.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border_active',
                'label' => esc_html__('Borde', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-item.active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // ============ ESTILO - TEXTO DE CATEGORÍA ============
        $this->start_controls_section(
            'text_style_section',
            [
                'label' => esc_html__('Texto de Categoría', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'category_name_typography',
                'label' => esc_html__('Tipografía', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-name',
            ]
        );

        $this->add_control(
            'text_transform',
            [
                'label' => esc_html__('Transformación de texto', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'uppercase',
                'options' => [
                    'none' => esc_html__('Ninguna', 'woo-custom-enhancements'),
                    'uppercase' => esc_html__('MAYÚSCULAS', 'woo-custom-enhancements'),
                    'lowercase' => esc_html__('minúsculas', 'woo-custom-enhancements'),
                    'capitalize' => esc_html__('Capitalizar', 'woo-custom-enhancements'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-name' => 'text-transform: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('text_color_tabs');

        $this->start_controls_tab(
            'text_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $this->add_control(
            'category_name_color',
            [
                'label' => esc_html__('Color', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .category-item .category-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_hover_tab',
            [
                'label' => esc_html__('Hover', 'woo-custom-enhancements'),
            ]
        );

        $this->add_control(
            'category_name_color_hover',
            [
                'label' => esc_html__('Color', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .category-item:hover .category-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_active_tab',
            [
                'label' => esc_html__('Activo', 'woo-custom-enhancements'),
            ]
        );

        $this->add_control(
            'category_name_color_active',
            [
                'label' => esc_html__('Color', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .category-item.active .category-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // ============ ESTILO - CONTADOR ============
        $this->start_controls_section(
            'counter_style_section',
            [
                'label' => esc_html__('Contador de Productos', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_product_count' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'counter_typography',
                'label' => esc_html__('Tipografía', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-count',
            ]
        );

        $this->add_control(
            'counter_spacing',
            [
                'label' => esc_html__('Espacio superior', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-count' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('counter_color_tabs');

        $this->start_controls_tab(
            'counter_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $this->add_control(
            'counter_color',
            [
                'label' => esc_html__('Color', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .category-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'counter_active_tab',
            [
                'label' => esc_html__('Activo', 'woo-custom-enhancements'),
            ]
        );

        $this->add_control(
            'counter_color_active',
            [
                'label' => esc_html__('Color', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .category-item.active .category-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // ============ ESTILO - DROPDOWN ============
        $this->start_controls_section(
            'dropdown_style_section',
            [
                'label' => esc_html__('Menú Desplegable', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dropdown_background',
            [
                'label' => esc_html__('Fondo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .subcategories-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'label' => esc_html__('Borde', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .subcategories-dropdown',
            ]
        );

        $this->add_control(
            'dropdown_border_radius',
            [
                'label' => esc_html__('Radio del borde', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .subcategories-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'label' => esc_html__('Sombra', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .subcategories-dropdown',
            ]
        );

        $this->end_controls_section();
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
            /* Permitir overflow visible en todos los contenedores */
            .elementor-widget-category_filter_horizontal,
            .elementor-widget-category_filter_horizontal .elementor-widget-container,
            .elementor-widget-category_filter_horizontal .elementor-widget-container * {
                overflow: visible !important;
            }

            .category-filter-horizontal-wrapper {
                background: #fff;
                border-radius: 50px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
                overflow: visible !important;
                position: relative;
                z-index: 1000;
            }

            .category-filter-horizontal {
                display: flex;
                align-items: center;
                overflow-x: auto;
                overflow-y: visible !important;
                scrollbar-width: thin;
                scrollbar-color: #ddd transparent;
                gap: 10px;
            }

            .category-filter-horizontal::-webkit-scrollbar {
                height: 4px;
            }

            .category-filter-horizontal::-webkit-scrollbar-track {
                background: transparent;
            }

            .category-filter-horizontal::-webkit-scrollbar-thumb {
                background: #ddd;
                border-radius: 4px;
            }

            .category-item {
                position: relative;
                flex-shrink: 0;
                cursor: pointer;
                transition: all 0.3s ease;
                background: #fff;
                text-align: center;
                white-space: nowrap;
                overflow: visible !important;
            }

            .category-item:hover {
                transform: translateY(-2px);
            }

            .category-name {
                display: block;
                font-size: 14px;
                font-weight: 600;
                color: #333;
            }

            .category-count {
                display: block;
                font-size: 11px;
                color: #999;
                font-weight: 400;
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
                max-height: 400px;
                overflow-y: auto;
                min-width: 200px;
                top: calc(100% + 8px);
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
                font-size: 14px;
                font-weight: 500;
                color: #333;
            }

            .subcategory-count {
                font-size: 11px;
                color: #666;
                background: #f0f0f0;
                padding: 3px 10px;
                border-radius: 12px;
                margin-left: 10px;
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
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            @media (max-width: 768px) {
                .category-filter-horizontal-wrapper {
                    border-radius: 30px;
                }

                .category-name {
                    font-size: 13px;
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
