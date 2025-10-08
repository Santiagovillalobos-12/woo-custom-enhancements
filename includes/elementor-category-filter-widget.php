<?php
/**
 * Widget de Elementor para Filtro de Categorías
 */

if (!defined("ABSPATH")) {
    exit;
}

class Elementor_Category_Filter_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return "category_filter_horizontal";
    }

    public function get_title() {
        return esc_html__("Filtro de Categorías", "woo-custom-enhancements");
    }

    public function get_icon() {
        return "eicon-filter";
    }

    public function get_categories() {
        return ["general", "woocommerce-elements"];
    }

    public function get_keywords() {
        return ["categorías", "filtro", "woocommerce", "productos", "shop"];
    }

    protected function register_controls() {
        
        // Sección de Contenido
        $this->start_controls_section(
            "content_section",
            [
                "label" => esc_html__("Contenido", "woo-custom-enhancements"),
                "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            "show_all_option",
            [
                "label" => esc_html__("Mostrar opción 'Todas'", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::SWITCHER,
                "label_on" => esc_html__("Sí", "woo-custom-enhancements"),
                "label_off" => esc_html__("No", "woo-custom-enhancements"),
                "return_value" => "yes",
                "default" => "yes",
            ]
        );

        $this->add_control(
            "all_option_text",
            [
                "label" => esc_html__("Texto de 'Todas'", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::TEXT,
                "default" => esc_html__("Todas", "woo-custom-enhancements"),
                "condition" => [
                    "show_all_option" => "yes",
                ],
            ]
        );

        $this->add_control(
            "show_product_count",
            [
                "label" => esc_html__("Mostrar contador de productos", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::SWITCHER,
                "label_on" => esc_html__("Sí", "woo-custom-enhancements"),
                "label_off" => esc_html__("No", "woo-custom-enhancements"),
                "return_value" => "yes",
                "default" => "yes",
            ]
        );

        $this->end_controls_section();

        // Sección de Estilo - Items de Categoría
        $this->start_controls_section(
            "category_item_style_section",
            [
                "label" => esc_html__("Items de Categoría", "woo-custom-enhancements"),
                "tab" => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            "item_normal_background",
            [
                "label" => esc_html__("Fondo normal", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#ffffff",
                "selectors" => [
                    "{{WRAPPER}} .category-item" => "background-color: {{VALUE}};",
                ],
            ]
        );

        $this->add_control(
            "item_hover_background",
            [
                "label" => esc_html__("Fondo hover", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#f8f9fa",
                "selectors" => [
                    "{{WRAPPER}} .category-item:hover" => "background-color: {{VALUE}};",
                ],
            ]
        );

        $this->add_control(
            "item_active_background",
            [
                "label" => esc_html__("Fondo activo", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#007cba",
                "selectors" => [
                    "{{WRAPPER}} .category-item.active" => "background-color: {{VALUE}};",
                ],
            ]
        );

        $this->end_controls_section();

        // Sección de Estilo - Texto
        $this->start_controls_section(
            "text_style_section",
            [
                "label" => esc_html__("Texto", "woo-custom-enhancements"),
                "tab" => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                "name" => "category_name_typography",
                "label" => esc_html__("Tipografía del nombre", "woo-custom-enhancements"),
                "selector" => "{{WRAPPER}} .category-name",
                "default" => [
                    "font_size" => "16",
                    "font_weight" => "600",
                ],
            ]
        );

        $this->add_control(
            "category_name_color",
            [
                "label" => esc_html__("Color del nombre", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#333333",
                "selectors" => [
                    "{{WRAPPER}} .category-item .category-name" => "color: {{VALUE}};",
                ],
            ]
        );

        $this->add_control(
            "category_name_color_active",
            [
                "label" => esc_html__("Color del nombre (activo)", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#ffffff",
                "selectors" => [
                    "{{WRAPPER}} .category-item.active .category-name" => "color: {{VALUE}};",
                ],
            ]
        );

        $this->end_controls_section();

        // Sección de Estilo - Dropdown de Subcategorías
        $this->start_controls_section(
            "subcategories_dropdown_style_section",
            [
                "label" => esc_html__("Dropdown de Subcategorías", "woo-custom-enhancements"),
                "tab" => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                "name" => "dropdown_background",
                "label" => esc_html__("Fondo del dropdown", "woo-custom-enhancements"),
                "types" => ["classic", "gradient"],
                "selector" => "{{WRAPPER}} .subcategories-dropdown",
                "default" => "#ffffff",
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                "name" => "dropdown_border",
                "label" => esc_html__("Borde del dropdown", "woo-custom-enhancements"),
                "selector" => "{{WRAPPER}} .subcategories-dropdown",
                "default" => [
                    "border" => "solid",
                    "width" => "1px",
                    "color" => "#dddddd",
                ],
            ]
        );

        $this->add_control(
            "dropdown_border_radius",
            [
                "label" => esc_html__("Radio del borde", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::DIMENSIONS,
                "size_units" => ["px", "%", "em", "rem"],
                "default" => [
                    "top" => "8",
                    "right" => "8",
                    "bottom" => "8",
                    "left" => "8",
                    "unit" => "px",
                    "isLinked" => true,
                ],
                "selectors" => [
                    "{{WRAPPER}} .subcategories-dropdown" => "border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                "name" => "dropdown_box_shadow",
                "label" => esc_html__("Sombra del dropdown", "woo-custom-enhancements"),
                "selector" => "{{WRAPPER}} .subcategories-dropdown",
                "default" => [
                    "box_shadow_type" => "yes",
                    "box_shadow_horizontal" => "0",
                    "box_shadow_vertical" => "8",
                    "box_shadow_blur" => "25",
                    "box_shadow_spread" => "0",
                    "box_shadow_color" => "rgba(0,0,0,0.15)",
                ],
            ]
        );

        $this->add_responsive_control(
            "dropdown_padding",
            [
                "label" => esc_html__("Relleno del dropdown", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::DIMENSIONS,
                "size_units" => ["px", "%", "em", "rem"],
                "default" => [
                    "top" => "0",
                    "right" => "0",
                    "bottom" => "0",
                    "left" => "0",
                    "unit" => "px",
                    "isLinked" => false,
                ],
                "selectors" => [
                    "{{WRAPPER}} .subcategories-dropdown" => "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
                ],
            ]
        );

        $this->add_control(
            "dropdown_max_height",
            [
                "label" => esc_html__("Altura máxima", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::SLIDER,
                "size_units" => ["px", "vh"],
                "range" => [
                    "px" => [
                        "min" => 200,
                        "max" => 600,
                        "step" => 10,
                    ],
                    "vh" => [
                        "min" => 20,
                        "max" => 80,
                        "step" => 1,
                    ],
                ],
                "default" => [
                    "unit" => "px",
                    "size" => 400,
                ],
                "selectors" => [
                    "{{WRAPPER}} .subcategories-dropdown" => "max-height: {{SIZE}}{{UNIT}};",
                ],
            ]
        );

        $this->end_controls_section();

        // Sección de Estilo - Items de Subcategoría
        $this->start_controls_section(
            "subcategory_item_style_section",
            [
                "label" => esc_html__("Items de Subcategoría", "woo-custom-enhancements"),
                "tab" => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            "subcategory_item_padding",
            [
                "label" => esc_html__("Relleno de items", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::DIMENSIONS,
                "size_units" => ["px", "%", "em", "rem"],
                "default" => [
                    "top" => "12",
                    "right" => "20",
                    "bottom" => "12",
                    "left" => "20",
                    "unit" => "px",
                    "isLinked" => false,
                ],
                "selectors" => [
                    "{{WRAPPER}} .subcategory-item" => "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                "name" => "subcategory_item_border",
                "label" => esc_html__("Borde inferior", "woo-custom-enhancements"),
                "fields_options" => [
                    "border" => [
                        "default" => "solid",
                    ],
                    "width" => [
                        "default" => [
                            "top" => "0",
                            "right" => "0",
                            "bottom" => "1",
                            "left" => "0",
                            "unit" => "px",
                            "isLinked" => false,
                        ],
                    ],
                    "color" => [
                        "default" => "#f0f0f0",
                    ],
                ],
                "selector" => "{{WRAPPER}} .subcategory-item",
            ]
        );

        $this->add_control(
            "subcategory_item_normal_background",
            [
                "label" => esc_html__("Fondo normal", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#ffffff",
                "selectors" => [
                    "{{WRAPPER}} .subcategory-item" => "background-color: {{VALUE}};",
                ],
            ]
        );

        $this->add_control(
            "subcategory_item_hover_background",
            [
                "label" => esc_html__("Fondo hover", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#f8f9fa",
                "selectors" => [
                    "{{WRAPPER}} .subcategory-item:hover" => "background-color: {{VALUE}};",
                ],
            ]
        );

        $this->end_controls_section();

        // Sección de Estilo - Texto de Subcategorías
        $this->start_controls_section(
            "subcategory_text_style_section",
            [
                "label" => esc_html__("Texto de Subcategorías", "woo-custom-enhancements"),
                "tab" => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                "name" => "subcategory_name_typography",
                "label" => esc_html__("Tipografía del nombre", "woo-custom-enhancements"),
                "selector" => "{{WRAPPER}} .subcategory-name",
                "default" => [
                    "font_size" => "14",
                    "font_weight" => "500",
                ],
            ]
        );

        $this->add_control(
            "subcategory_name_color",
            [
                "label" => esc_html__("Color del nombre", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#333333",
                "selectors" => [
                    "{{WRAPPER}} .subcategory-name" => "color: {{VALUE}};",
                ],
            ]
        );

        $this->add_control(
            "subcategory_name_color_hover",
            [
                "label" => esc_html__("Color del nombre (hover)", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#007cba",
                "selectors" => [
                    "{{WRAPPER}} .subcategory-item:hover .subcategory-name" => "color: {{VALUE}};",
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                "name" => "subcategory_count_typography",
                "label" => esc_html__("Tipografía del contador", "woo-custom-enhancements"),
                "selector" => "{{WRAPPER}} .subcategory-count",
                "default" => [
                    "font_size" => "11",
                    "font_weight" => "400",
                ],
            ]
        );

        $this->add_control(
            "subcategory_count_background",
            [
                "label" => esc_html__("Fondo del contador", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#f0f0f0",
                "selectors" => [
                    "{{WRAPPER}} .subcategory-count" => "background-color: {{VALUE}};",
                ],
            ]
        );

        $this->add_control(
            "subcategory_count_color",
            [
                "label" => esc_html__("Color del contador", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#666666",
                "selectors" => [
                    "{{WRAPPER}} .subcategory-count" => "color: {{VALUE}};",
                ],
            ]
        );

        $this->add_control(
            "subcategory_count_color_hover",
            [
                "label" => esc_html__("Color del contador (hover)", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::COLOR,
                "default" => "#007cba",
                "selectors" => [
                    "{{WRAPPER}} .subcategory-item:hover .subcategory-count" => "color: {{VALUE}};",
                ],
            ]
        );

        $this->add_control(
            "subcategory_count_border_radius",
            [
                "label" => esc_html__("Radio del borde del contador", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::SLIDER,
                "size_units" => ["px", "%", "em", "rem"],
                "range" => [
                    "px" => [
                        "min" => 0,
                        "max" => 50,
                        "step" => 1,
                    ],
                ],
                "default" => [
                    "unit" => "px",
                    "size" => 10,
                ],
                "selectors" => [
                    "{{WRAPPER}} .subcategory-count" => "border-radius: {{SIZE}}{{UNIT}};",
                ],
            ]
        );

        $this->add_responsive_control(
            "subcategory_count_padding",
            [
                "label" => esc_html__("Relleno del contador", "woo-custom-enhancements"),
                "type" => \Elementor\Controls_Manager::DIMENSIONS,
                "size_units" => ["px", "%", "em", "rem"],
                "default" => [
                    "top" => "2",
                    "right" => "8",
                    "bottom" => "2",
                    "left" => "8",
                    "unit" => "px",
                    "isLinked" => false,
                ],
                "selectors" => [
                    "{{WRAPPER}} .subcategory-count" => "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Solo mostrar en páginas de productos
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            echo '<div style="padding: 20px; background: #f0f0f0; border: 1px solid #ccc; border-radius: 4px; text-align: center;">';
            echo '<strong>Filtro de Categorías</strong><br>';
            echo '<small>Este widget solo se muestra en páginas de productos (shop, categorías, etiquetas)</small>';
            echo '</div>';
            return;
        }

        // Obtener categorías principales
        $categories = get_terms(array(
            "taxonomy" => "product_cat",
            "hide_empty" => true,
            "parent" => 0,
            "orderby" => "name",
            "order" => "ASC"
        ));

        if (empty($categories) || is_wp_error($categories)) {
            return;
        }

        $show_all_option = $settings["show_all_option"] === "yes";
        $all_option_text = $settings["all_option_text"] ?: "Todas";
        $show_product_count = $settings["show_product_count"] === "yes";

        ?>
        <div class="category-filter-horizontal-wrapper">
            <div class="category-filter-horizontal">
                <?php if ($show_all_option): ?>
                    <div class="category-item <?php echo !is_product_category() ? "active" : ""; ?>" data-category="0">
                        <span class="category-name"><?php echo esc_html($all_option_text); ?></span>
                        <?php if ($show_product_count): ?>
                            <span class="category-count"><?php echo wp_count_posts("product")->publish; ?> productos</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($categories as $category): 
                    $product_count = $category->count;
                    $subcategories = get_terms(array(
                        "taxonomy" => "product_cat",
                        "hide_empty" => true,
                        "parent" => $category->term_id,
                        "orderby" => "name",
                        "order" => "ASC"
                    ));
                    
                    $has_subcategories = !empty($subcategories) && !is_wp_error($subcategories);
                    $current_category_class = (is_product_category() && get_queried_object_id() == $category->term_id) ? "active" : "";
                ?>
                    <div class="category-item <?php echo $current_category_class; ?> <?php echo $has_subcategories ? "has-subcategories" : ""; ?>" 
                         data-category="<?php echo esc_attr($category->term_id); ?>">
                        
                        <div class="category-main">
                            <span class="category-name"><?php echo esc_html($category->name); ?></span>
                            <?php if ($show_product_count): ?>
                                <span class="category-count"><?php echo $product_count; ?> productos</span>
                            <?php endif; ?>
                        </div>

                        <?php if ($has_subcategories): ?>
                            <div class="subcategories-dropdown">
                                <div class="subcategory-item" data-category="<?php echo esc_attr($category->term_id); ?>">
                                    <span class="subcategory-name">Ver todos</span>
                                    <?php if ($show_product_count): ?>
                                        <span class="subcategory-count"><?php echo $product_count; ?> productos</span>
                                    <?php endif; ?>
                                </div>
                                <?php foreach ($subcategories as $subcategory): ?>
                                    <div class="subcategory-item" data-category="<?php echo esc_attr($subcategory->term_id); ?>">
                                        <span class="subcategory-name"><?php echo esc_html($subcategory->name); ?></span>
                                        <?php if ($show_product_count): ?>
                                            <span class="subcategory-count"><?php echo $subcategory->count; ?> productos</span>
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
            background: transparent;
            overflow: visible !important;
            position: relative;
            z-index: 1000;
            width: 100%;
        }

        .category-filter-horizontal {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            overflow-x: auto;
            overflow-y: visible !important;
            padding: 0;
            margin: 0;
            scrollbar-width: thin;
            scrollbar-color: #ddd transparent;
            position: relative;
            width: 100%;
        }

        .category-filter-horizontal::-webkit-scrollbar {
            height: 6px;
        }

        .category-filter-horizontal::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .category-filter-horizontal::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 3px;
        }

        .category-filter-horizontal::-webkit-scrollbar-thumb:hover {
            background: #bbb;
        }

        .category-item {
            position: relative;
            flex-shrink: 0;
            min-width: 160px;
            padding: 20px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-right: 1px solid #f0f0f0;
            background: #fff;
            text-align: center;
        }

        .category-item:last-child {
            border-right: none;
        }

        .category-item:hover,
        .category-item.active {
            background: #f8f9fa;
            color: #007cba;
        }

        .category-item.active {
            background: #007cba;
            color: #fff;
        }

        .category-item.active .category-name,
        .category-item.active .category-count {
            color: #fff;
        }

        .category-name {
            display: block;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .category-count {
            display: block;
            font-size: 12px;
            color: #666;
            font-weight: 400;
        }

        .category-item:hover .subcategories-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .subcategories-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 99999 !important;
            max-height: 400px;
            overflow-y: auto;
            min-width: 200px;
            max-width: 300px;
            width: max-content;
        }

        .subcategories-dropdown.active {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }

        .subcategory-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: background 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .subcategory-item:last-child {
            border-bottom: none;
        }

        .subcategory-item:hover {
            background: #f8f9fa;
            color: #007cba;
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
            padding: 2px 8px;
            border-radius: 10px;
        }

        .subcategory-item:hover .subcategory-name,
        .subcategory-item:hover .subcategory-count {
            color: #007cba;
        }

        .category-filter-horizontal-wrapper * {
            box-sizing: border-box;
        }

        /* Asegurar que el widget funcione en cualquier contenedor de Elementor */
        .elementor-widget-category_filter_horizontal .category-filter-horizontal-wrapper,
        .elementor-widget-category_filter_horizontal .category-filter-horizontal,
        .elementor-widget-category_filter_horizontal .category-item,
        .elementor-widget-category_filter_horizontal .subcategories-dropdown {
            overflow: visible !important;
        }

        /* Forzar que los contenedores padre permitan overflow visible */
        .elementor-widget-category_filter_horizontal {
            overflow: visible !important;
        }

        .elementor-widget-category_filter_horizontal .elementor-widget-container {
            overflow: visible !important;
        }

        .category-filter-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .category-filter-loading::after {
            content: "";
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
        </style>

        <script>
        jQuery(function($) {
            
            $(".category-item, .subcategory-item").on("click", function(e) {
                e.preventDefault();
                
                var categoryId = $(this).data("category");
                var $this = $(this);
                var $wrapper = $(".category-filter-horizontal-wrapper");
                
                $(".category-item, .subcategory-item").removeClass("active");
                $this.addClass("active");
                
                if ($this.hasClass("subcategory-item")) {
                    $this.closest(".category-item").addClass("active");
                }
                
                $wrapper.addClass("category-filter-loading");
                
                var data = {
                    action: "filter_products_by_category",
                    category_id: categoryId,
                    nonce: "<?php echo wp_create_nonce("category_filter_nonce"); ?>"
                };
                
                <?php if (is_product_category()): ?>
                data.current_category = "<?php echo get_queried_object_id(); ?>";
                <?php endif; ?>
                
                $.ajax({
                    url: "<?php echo admin_url("admin-ajax.php"); ?>",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            if (response.data.redirect && response.data.url) {
                                if (response.data.message) {
                                    console.log(response.data.message);
                                }
                                window.location.href = response.data.url;
                            } else {
                                var newUrl = response.data.url;
                                if (newUrl) {
                                    window.history.pushState(null, null, newUrl);
                                }
                                
                                if (response.data.content) {
                                    $(".woocommerce-loop-container, .woocommerce ul.products, .elementor-loop-container").html(response.data.content);
                                }
                                
                                if (response.data.title) {
                                    document.title = response.data.title;
                                }
                                
                                $("html, body").animate({
                                    scrollTop: $(".woocommerce-loop-container, .woocommerce ul.products, .elementor-loop-container").offset().top - 100
                                }, 500);
                            }
                        } else {
                            console.error("Error:", response.data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                    },
                    complete: function() {
                        $wrapper.removeClass("category-filter-loading");
                    }
                });
            });
            
            $(".category-item.has-subcategories").on("mouseenter", function() {
                var $this = $(this);
                var $dropdown = $this.find(".subcategories-dropdown");
                
                $(".subcategories-dropdown").removeClass("active");
                $dropdown.addClass("active");
            });
            
            $(".category-item.has-subcategories").on("mouseleave", function() {
                var $this = $(this);
                var $dropdown = $this.find(".subcategories-dropdown");
                
                setTimeout(function() {
                    if (!$dropdown.is(":hover")) {
                        $dropdown.removeClass("active");
                    }
                }, 100);
            });
            
            $(".subcategories-dropdown").on("mouseenter", function() {
                $(this).addClass("active");
            }).on("mouseleave", function() {
                $(this).removeClass("active");
            });
            
            $(".category-item.has-subcategories").on("click", function(e) {
                if ("ontouchstart" in window) {
                    e.preventDefault();
                    var $this = $(this);
                    var $dropdown = $this.find(".subcategories-dropdown");
                    
                    $(".subcategories-dropdown").not($dropdown).removeClass("active");
                    
                    if ($dropdown.hasClass("active")) {
                        $dropdown.removeClass("active");
                    } else {
                        $dropdown.addClass("active");
                    }
                }
            });
            
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".category-item").length && !$(e.target).closest(".subcategories-dropdown").length) {
                    $(".subcategories-dropdown").removeClass("active");
                }
            });
        });
        </script>
        <?php
    }
}