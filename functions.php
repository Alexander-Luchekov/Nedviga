<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );


  // Ваш код должен быть в файле functions.php вашей темы или в плагине
   function city_meta_box() {
       add_meta_box(
           'city_field',
           'Выберите город',
           'city_field_callback',
           'realty',
           'normal',
           'default'
       );
   }

   function city_field_callback( $post ) {
       $selected_city = get_post_meta( $post->ID, 'selected_city', true );
       $cities = get_posts( array(
           'post_type'   => 'city',
           'numberposts' => -1,
       ) );
       ?>
       <select name="selected_city">
           <option value="">-- Выберите город --</option>
           <?php foreach ( $cities as $city ) {
               printf(
                   '<option value="%s" %s>%s</option>',
                   $city->ID,
                   selected( $selected_city, $city->ID, false ),
                   $city->post_title
               );
           } ?>
       </select>
       <?php
   }

   add_action( 'add_meta_boxes', 'city_meta_box' );

 function save_city_meta( $post_id ) {
       if ( isset( $_POST['selected_city'] ) ) {
           update_post_meta( $post_id, 'selected_city', $_POST['selected_city'] );
       }
   }

   add_action( 'save_post', 'save_city_meta' );


function enqueue_scripts() {
    // Подключение jQuery и AJAX скриптов
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom-script.js', array( 'jquery' ), '1.0', true );

    // Передача параметров в AJAX скрипт
    wp_localize_script(
        'custom-script',
        'ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
}

add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );

// Создание нового объекта недвижимости через AJAX
// Обработчик AJAX запроса для добавления объекта недвижимости
add_action( 'wp_ajax_add_property', 'add_property_callback' );
add_action( 'wp_ajax_nopriv_add_property', 'add_property_callback' );

function add_property_callback() {
    if ( isset( $_POST['property_title'] ) ) {
        $new_property = array(
            'post_title'   => $_POST['property_title'],
            'post_type'    => 'realty',
            'post_status'  => 'publish',
            'post_content' => '',
        );

        $property_id = wp_insert_post( $new_property );

        if ( $property_id ) {
            if ( isset( $_POST['property_city'] ) ) {
                update_post_meta( $property_id, 'selected_city', $_POST['property_city'] );
            }

            if ( isset( $_POST['property_tip'] ) ) {
                wp_set_post_terms( $property_id, $_POST['property_tip'], 'tip', false );
            }
            
            if ( isset( $_POST['ploshad'] ) ) {
                update_post_meta( $property_id, 'ploshad', $_POST['ploshad'] );
            }

            if ( isset( $_POST['stoimost'] ) ) {
                update_post_meta( $property_id, 'stoimost', $_POST['stoimost'] );
            }

            if ( isset( $_POST['adress'] ) ) {
                update_post_meta( $property_id, 'adress', $_POST['adress'] );
            }

            if ( isset( $_POST['zhilploshad'] ) ) {
                update_post_meta( $property_id, 'zhilploshad', $_POST['zhilploshad'] );
            }

            if ( isset( $_POST['etazh'] ) ) {
                update_post_meta( $property_id, 'etazh', $_POST['etazh'] );
            }

            echo 'Объект недвижимости успешно добавлен! Выбранный тип: ' . get_term( $_POST['property_tip'], 'tip' )->name;
        } else {
            echo 'Ошибка при добавлении объекта недвижимости.';
        }

        exit;
    }
}



// Регистрация метабокса галереи
function add_realty_gallery_metabox() {
    add_meta_box(
        'realty_gallery_metabox',
        'Галерея',
        'render_realty_gallery_metabox',
        'realty',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'add_realty_gallery_metabox' );

// Отображение метабокса галереи
function render_realty_gallery_metabox( $post ) {
    $gallery = explode( ',', get_post_meta( $post->ID, 'realty_gallery', true ) );
    wp_nonce_field( basename( __FILE__ ), 'realty_gallery_nonce' );
    ?>
    <div class="meta-field">
        <input type="button" id="additional-images" class="button" value="Добавить изображения">
        <ul id="realty-gallery-preview">
            <?php if ( $gallery ) : ?>
                <?php foreach ( $gallery as $image_id ) : ?>
                    <li><?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <input type="hidden" name="realty_gallery" id="realty-gallery" value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>">
    </div>
    <?php
}

// Сохранение галереи при сохранении записи
function save_realty_gallery( $post_id ) {
    if ( ! isset( $_POST['realty_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['realty_gallery_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    if ( 'realty' === $_POST['post_type'] && current_user_can( 'edit_post', $post_id ) ) {
        if ( isset( $_POST['realty_gallery'] ) ) {
            $gallery = sanitize_text_field( $_POST['realty_gallery'] );
            update_post_meta( $post_id, 'realty_gallery', $gallery );
        }
    }
}
add_action( 'save_post', 'save_realty_gallery' );

// Подключение стилей и скриптов
function enqueue_realty_gallery_scripts( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_script( 'realty-gallery-script', get_template_directory_uri() . '/js/realty-gallery.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_style( 'realty-gallery-style', get_template_directory_uri() . '/css/realty-gallery.css' );
    }
}
add_action( 'admin_enqueue_scripts', 'enqueue_realty_gallery_scripts' );