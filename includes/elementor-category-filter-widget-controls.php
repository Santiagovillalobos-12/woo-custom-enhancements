<?php
/**
 * Configuraciones de controles de Elementor para el Widget de Filtro de Categorías
 * Archivo: includes/elementor-category-filter-widget-controls.php
 */

if (!defined('ABSPATH')) exit;

class Elementor_Category_Filter_Widget_Controls {

    /**
     * Registra todos los controles de configuración del widget
     */
    public static function register_controls($widget) {
        
        // ============ SECCIÓN DE CONTENIDO ============
        $widget->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Contenido', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $widget->add_control(
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

        $widget->add_control(
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

        $widget->add_control(
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

        $widget->end_controls_section();

        // ============ ESTILO - CONTENEDOR ============
        $widget->start_controls_section(
            'container_style_section',
            [
                'label' => esc_html__('Contenedor', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => esc_html__('Fondo', 'woo-custom-enhancements'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .category-filter-horizontal-wrapper',
            ]
        );

        $widget->add_responsive_control(
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

        $widget->add_responsive_control(
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

        $widget->add_control(
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

        $widget->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => esc_html__('Sombra', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-filter-horizontal-wrapper',
            ]
        );

        $widget->end_controls_section();

        // ============ ESTILO - ITEMS DE CATEGORÍA ============
        $widget->start_controls_section(
            'item_style_section',
            [
                'label' => esc_html__('Items de Categoría', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_responsive_control(
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

        $widget->add_responsive_control(
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

        $widget->add_control(
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
        $widget->start_controls_tabs('item_style_tabs');

        // TAB NORMAL
        $widget->start_controls_tab(
            'item_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
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

        $widget->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => esc_html__('Borde', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-item',
            ]
        );

        $widget->end_controls_tab();

        // TAB HOVER
        $widget->start_controls_tab(
            'item_hover_tab',
            [
                'label' => esc_html__('Hover', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
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

        $widget->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border_hover',
                'label' => esc_html__('Borde', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-item:hover',
            ]
        );

        $widget->end_controls_tab();

        // TAB ACTIVE
        $widget->start_controls_tab(
            'item_active_tab',
            [
                'label' => esc_html__('Activo', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
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

        $widget->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border_active',
                'label' => esc_html__('Borde', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-item.active',
            ]
        );

        $widget->end_controls_tab();

        $widget->end_controls_tabs();

        $widget->end_controls_section();

        // ============ ESTILO - TEXTO DE CATEGORÍA ============
        $widget->start_controls_section(
            'text_style_section',
            [
                'label' => esc_html__('Texto de Categoría', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'category_name_typography',
                'label' => esc_html__('Tipografía', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-name',
            ]
        );

        $widget->add_control(
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

        $widget->start_controls_tabs('text_color_tabs');

        $widget->start_controls_tab(
            'text_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
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

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'text_hover_tab',
            [
                'label' => esc_html__('Hover', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
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

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'text_active_tab',
            [
                'label' => esc_html__('Activo', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
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

        $widget->end_controls_tab();

        $widget->end_controls_tabs();

        $widget->end_controls_section();

        // ============ ESTILO - CONTADOR ============
        $widget->start_controls_section(
            'counter_style_section',
            [
                'label' => esc_html__('Contador de Productos', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_product_count' => 'yes',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'counter_typography',
                'label' => esc_html__('Tipografía', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .category-count',
            ]
        );

        $widget->add_control(
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

        $widget->start_controls_tabs('counter_color_tabs');

        $widget->start_controls_tab(
            'counter_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
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

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'counter_active_tab',
            [
                'label' => esc_html__('Activo', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
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

        $widget->end_controls_tab();

        $widget->end_controls_tabs();

        $widget->end_controls_section();

        // ============ ESTILO - DROPDOWN ============
        $widget->start_controls_section(
            'dropdown_style_section',
            [
                'label' => esc_html__('Menú Desplegable', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_control(
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

        $widget->add_responsive_control(
            'dropdown_padding',
            [
                'label' => esc_html__('Relleno interno', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '8',
                    'right' => '0',
                    'bottom' => '8',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .subcategories-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'dropdown_min_width',
            [
                'label' => esc_html__('Ancho mínimo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 150,
                        'max' => 400,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .subcategories-dropdown' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'dropdown_max_height',
            [
                'label' => esc_html__('Altura máxima', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 600,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .subcategories-dropdown' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'label' => esc_html__('Borde', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .subcategories-dropdown',
            ]
        );

        $widget->add_control(
            'dropdown_border_radius',
            [
                'label' => esc_html__('Radio del borde', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .subcategories-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_box_shadow',
                'label' => esc_html__('Sombra', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .subcategories-dropdown',
            ]
        );

        $widget->end_controls_section();

        // ============ ESTILO - ITEMS DEL DROPDOWN ============
        $widget->start_controls_section(
            'dropdown_item_style_section',
            [
                'label' => esc_html__('Items del Dropdown', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_responsive_control(
            'dropdown_item_padding',
            [
                'label' => esc_html__('Relleno del item', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '12',
                    'right' => '20',
                    'bottom' => '12',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .subcategory-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'dropdown_item_border_bottom',
            [
                'label' => esc_html__('Borde inferior', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'woo-custom-enhancements'),
                'label_off' => esc_html__('No', 'woo-custom-enhancements'),
                'return_value' => 'yes',
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .subcategory-item' => 'border-bottom: 1px solid #f5f5f5;',
                    '{{WRAPPER}} .subcategory-item:last-child' => 'border-bottom: none;',
                ],
            ]
        );

        $widget->add_control(
            'dropdown_item_border_color',
            [
                'label' => esc_html__('Color del borde', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f5f5',
                'condition' => [
                    'dropdown_item_border_bottom' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .subcategory-item' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        // Tabs para estados del item del dropdown
        $widget->start_controls_tabs('dropdown_item_style_tabs');

        // TAB NORMAL
        $widget->start_controls_tab(
            'dropdown_item_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
            'dropdown_item_background',
            [
                'label' => esc_html__('Color de fondo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .subcategory-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();

        // TAB HOVER
        $widget->start_controls_tab(
            'dropdown_item_hover_tab',
            [
                'label' => esc_html__('Hover', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
            'dropdown_item_background_hover',
            [
                'label' => esc_html__('Color de fondo', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .subcategory-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->end_controls_tabs();

        $widget->end_controls_section();

        // ============ ESTILO - TEXTO DEL DROPDOWN ============
        $widget->start_controls_section(
            'dropdown_text_style_section',
            [
                'label' => esc_html__('Texto del Dropdown', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_text_typography',
                'label' => esc_html__('Tipografía del nombre', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .subcategory-name',
                'default' => [
                    'font_size' => '14',
                    'font_weight' => '500',
                ],
            ]
        );

        // Tabs para colores del texto del dropdown
        $widget->start_controls_tabs('dropdown_text_color_tabs');

        $widget->start_controls_tab(
            'dropdown_text_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
            'dropdown_text_color',
            [
                'label' => esc_html__('Color del nombre', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .subcategory-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'dropdown_text_hover_tab',
            [
                'label' => esc_html__('Hover', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
            'dropdown_text_color_hover',
            [
                'label' => esc_html__('Color del nombre', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .subcategory-item:hover .subcategory-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->end_controls_tabs();

        $widget->end_controls_section();

        // ============ ESTILO - CONTADOR DEL DROPDOWN ============
        $widget->start_controls_section(
            'dropdown_counter_style_section',
            [
                'label' => esc_html__('Contador del Dropdown', 'woo-custom-enhancements'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_product_count' => 'yes',
                ],
            ]
        );

        $widget->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_counter_typography',
                'label' => esc_html__('Tipografía del contador', 'woo-custom-enhancements'),
                'selector' => '{{WRAPPER}} .subcategory-count',
                'default' => [
                    'font_size' => '11',
                    'font_weight' => '400',
                ],
            ]
        );

        $widget->add_control(
            'dropdown_counter_background',
            [
                'label' => esc_html__('Fondo del contador', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f0f0f0',
                'selectors' => [
                    '{{WRAPPER}} .subcategory-count' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'dropdown_counter_border_radius',
            [
                'label' => esc_html__('Radio del borde', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .subcategory-count' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_responsive_control(
            'dropdown_counter_padding',
            [
                'label' => esc_html__('Relleno del contador', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '3',
                    'right' => '10',
                    'bottom' => '3',
                    'left' => '10',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .subcategory-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Tabs para colores del contador del dropdown
        $widget->start_controls_tabs('dropdown_counter_color_tabs');

        $widget->start_controls_tab(
            'dropdown_counter_normal_tab',
            [
                'label' => esc_html__('Normal', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
            'dropdown_counter_color',
            [
                'label' => esc_html__('Color del contador', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .subcategory-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'dropdown_counter_hover_tab',
            [
                'label' => esc_html__('Hover', 'woo-custom-enhancements'),
            ]
        );

        $widget->add_control(
            'dropdown_counter_color_hover',
            [
                'label' => esc_html__('Color del contador', 'woo-custom-enhancements'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .subcategory-item:hover .subcategory-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_tab();

        $widget->end_controls_tabs();

        $widget->end_controls_section();
    }
}
