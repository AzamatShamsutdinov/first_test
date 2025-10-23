<?php
/**
 * Пример кода для functions.php WordPress темы
 * Добавьте этот код в файл functions.php вашей темы
 */

// Автоматическое создание категорий при активации темы
function create_heating_categories() {
    // Проверяем, установлен ли WooCommerce
    if (!class_exists('WooCommerce')) {
        return;
    }

    $categories = array(
        'Отопление' => array(
            'description' => 'Системы отопления для дома и офиса',
            'icon' => 'catalog-12'
        ),
        'Электрика и свет' => array(
            'description' => 'Электрооборудование и освещение',
            'icon' => 'catalog-20'
        ),
        'Котлы и печи' => array(
            'description' => 'Котлы отопления, печи, камины',
            'icon' => 'catalog-32'
        ),
        'Радиаторы' => array(
            'description' => 'Радиаторы отопления всех типов',
            'icon' => 'catalog-26'
        ),
        'Теплые полы' => array(
            'description' => 'Системы теплого пола',
            'icon' => 'catalog-13'
        ),
        'Термостаты' => array(
            'description' => 'Терморегуляторы и термостаты',
            'icon' => 'catalog-2693'
        ),
        'Трубы и фитинги' => array(
            'description' => 'Трубы и соединительные элементы',
            'icon' => 'catalog-19'
        ),
        'Насосы' => array(
            'description' => 'Циркуляционные и другие насосы',
            'icon' => 'catalog-25'
        ),
        'Вентиляция' => array(
            'description' => 'Вентиляционное оборудование',
            'icon' => 'catalog-30'
        ),
        'Кондиционеры' => array(
            'description' => 'Кондиционеры и климатическая техника',
            'icon' => 'catalog-28'
        ),
        'Обогреватели' => array(
            'description' => 'Электрические и газовые обогреватели',
            'icon' => 'catalog-15'
        ),
        'Климат-контроль' => array(
            'description' => 'Системы управления климатом',
            'icon' => 'catalog-16'
        ),
        'Изоляция' => array(
            'description' => 'Теплоизоляционные материалы',
            'icon' => 'catalog-14'
        ),
        'Аксессуары для отопления' => array(
            'description' => 'Комплектующие и аксессуары',
            'icon' => 'catalog-2699'
        ),
    );

    foreach ($categories as $cat_name => $cat_data) {
        // Проверяем, существует ли категория
        $term = term_exists($cat_name, 'product_cat');
        
        if (!$term) {
            // Создаем категорию
            $term_data = wp_insert_term(
                $cat_name,
                'product_cat',
                array(
                    'description' => $cat_data['description'],
                    'slug' => sanitize_title($cat_name)
                )
            );
            
            // Сохраняем иконку в метаданные (если нужно)
            if (!is_wp_error($term_data)) {
                update_term_meta($term_data['term_id'], 'category_icon', $cat_data['icon']);
            }
        }
    }
}

// Запускаем создание категорий при активации темы
add_action('after_switch_theme', 'create_heating_categories');


/**
 * Получение иконки категории из метаданных
 */
function get_category_icon($term_id) {
    $icon = get_term_meta($term_id, 'category_icon', true);
    return $icon ? $icon : 'catalog-12'; // Дефолтная иконка
}


/**
 * Фильтр для изменения текста поиска (если используется WordPress поиск)
 */
function custom_search_placeholder($text) {
    return 'Товары для уюта и тепла';
}
add_filter('get_search_form', 'custom_search_placeholder');


/**
 * Добавление пользовательского поля для выбора иконки в админке категорий
 */
function add_category_icon_field($term) {
    $icon = get_term_meta($term->term_id, 'category_icon', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="category_icon">Иконка категории</label>
        </th>
        <td>
            <input type="text" name="category_icon" id="category_icon" value="<?php echo esc_attr($icon); ?>">
            <p class="description">Введите ID иконки (например: catalog-12)</p>
        </td>
    </tr>
    <?php
}
add_action('product_cat_edit_form_fields', 'add_category_icon_field');


/**
 * Сохранение пользовательского поля иконки
 */
function save_category_icon_field($term_id) {
    if (isset($_POST['category_icon'])) {
        update_term_meta($term_id, 'category_icon', sanitize_text_field($_POST['category_icon']));
    }
}
add_action('edited_product_cat', 'save_category_icon_field');
add_action('created_product_cat', 'save_category_icon_field');


/**
 * Шорткод для вывода каталога
 * Использование: [heating_catalog]
 */
function heating_catalog_shortcode() {
    ob_start();
    include(get_template_directory() . '/templates/home.php');
    return ob_get_clean();
}
add_shortcode('heating_catalog', 'heating_catalog_shortcode');
