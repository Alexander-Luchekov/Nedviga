<?php get_header(); ?>

<div id="content" class="site-content">
    <main id="main" class="site-main" role="main">

        <div class="container my-5">
            <div class="row">

                   
                    <?php
                    $args = array(
                        'post_type' => 'city',
                        'posts_per_page' => -1 // Все записи
                    );

                    $query = new WP_Query($args);

                    if ($query->have_posts()) :
                        while ($query->have_posts()) :
                            $query->the_post(); ?>
						<div class="col-sm-6 col-md-2">
                            <div class="card mb-4">
                                
								<?php if (has_post_thumbnail()): ?>
            <?php the_post_thumbnail('thumbnail', ['class' => 'card-img-top']); ?>
        <?php endif; ?>
                                <div class="card-body">
									<a href="<?php the_permalink(); ?>"><h4 class="card-title"><?php the_title(); ?></h4></a>
                                    <?php the_content(); ?>
                                </div>
                            </div>
				        </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    else :
                        _e('No records found', 'understrap');
                    endif;
                    ?>
 
            </div>
        </div>

        <div class="container my-4">
            <div class="row">
                <?php
                $args = array(
                    'post_type' => 'realty',
                    'posts_per_page' => -1 // Все записи
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) :
                        $query->the_post(); ?>

                     
<div class="col-sm-6 col-md-3">  
    <div class="card mb-4">  
        <div class="card-body">  
            <?php $gallery = explode( ',', get_post_meta( get_the_ID(), 'realty_gallery', true ) ); ?>  
            <?php if ( $gallery ) : ?>  
                <?php echo wp_get_attachment_image( $gallery[0], 'medium' ); ?>  
            <?php endif; ?>  
			<a href="<?php the_permalink(); ?>"><h5 class="card-title" style="margin-top:15px;"><?php the_title(); ?></h5></a>
            
            <?php $ploshad = get_post_meta( get_the_ID(), 'ploshad', true ); ?>
            <?php $stoimost = get_post_meta( get_the_ID(), 'stoimost', true ); ?>
            <?php $adress = get_post_meta( get_the_ID(), 'adress', true ); ?>
            <?php $zhilploshad = get_post_meta( get_the_ID(), 'zhilploshad', true ); ?>
            <?php $etazh = get_post_meta( get_the_ID(), 'etazh', true ); ?>
            <?php if ( $ploshad || $stoimost || $adress || $zhilploshad || $etazh ) : ?>
                <ul class="list-unstyled mt-3">
                    <?php if ( $ploshad ) : ?>
                        <li><strong>Площадь:</strong> <?php echo $ploshad; ?></li>
                    <?php endif; ?>
                    <?php if ( $stoimost ) : ?>
                        <li><strong>Стоимость:</strong> <?php echo $stoimost; ?></li>
                    <?php endif; ?>
                    <?php if ( $adress ) : ?>
                        <li><strong>Адрес:</strong> <?php echo $adress; ?></li>
                    <?php endif; ?>
                    <?php if ( $zhilploshad ) : ?>
                        <li><strong>Жилая площадь:</strong> <?php echo $zhilploshad; ?></li>
                    <?php endif; ?>
                    <?php if ( $etazh ) : ?>
                        <li><strong>Этаж:</strong> <?php echo $etazh; ?></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php $tip_terms = get_the_terms( get_the_ID(), 'tip' ); ?>
        <?php if ( $tip_terms ) : ?>
            <div class="card-footer">
                <small class="text-muted">
                    <?php foreach ( $tip_terms as $tip_term ) : ?>
                        <?php echo $tip_term->name; ?>
                    <?php endforeach; ?>
                </small>
            </div>
        <?php endif; ?>
    </div>  
</div>
			
                    <?php endwhile;
                    wp_reset_postdata();
                else :
                    _e('No records found', 'understrap');
                endif;
                ?>
            </div>
        </div>

    </main><!-- #main -->
</div><!-- #content -->


<!-- ADD FORM -->
<?php
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

        // Добавьте сохранение значений ACF полей
        if ( isset( $_POST['ploshad'] ) ) {
            update_field( 'ploshad', $_POST['ploshad'], $property_id );
        }

        if ( isset( $_POST['stoimost'] ) ) {
            update_field( 'stoimost', $_POST['stoimost'], $property_id );
        }

        if ( isset( $_POST['adress'] ) ) {
            update_field( 'adress', $_POST['adress'], $property_id );
        }

        if ( isset( $_POST['zhilploshad'] ) ) {
            update_field( 'zhilploshad', $_POST['zhilploshad'], $property_id );
        }

        if ( isset( $_POST['etazh'] ) ) {
            update_field( 'etazh', $_POST['etazh'], $property_id );
        }

        echo 'Объект недвижимости успешно добавлен! Выбранный тип: ' . get_term( $_POST['property_tip'], 'tip' )->name;
    } else {
        echo 'Ошибка при добавлении объекта недвижимости.';
    }

    exit;
}
?>

<div class="container">
    <form id="add-property-form">
        <div class="form-group col-sm-6">
            <label for="property-title">Заголовок объекта недвижимости:</label>
            <input type="text" name="property_title" id="property-title" required class="form-control">
        </div>
        <div class="form-group col-sm-6">
            <label for="property-city">Город:</label>
            <select name="property_city" id="property-city" required class="form-control">
                <option value="">-- Выберите город --</option>
                <?php
                $cities = get_posts( array( 'post_type' => 'city', 'numberposts' => -1 ) );
                foreach ( $cities as $city ) {
                    echo '<option value="' . $city->ID . '">' . $city->post_title . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="property-tip">Тип объекта недвижимости:</label>
            <select name="property_tip" id="property-tip" required class="form-control">
                <option value="">-- Выберите тип --</option>
                <?php
                $terms = get_terms( array( 'taxonomy' => 'tip', 'hide_empty' => false ) );
                foreach ( $terms as $term ) {
                    echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="ploshad">Площадь:</label>
            <input type="text" name="ploshad" id="ploshad" class="form-control">
        </div>
        <div class="form-group col-sm-6">
            <label for="stoimost">Стоимость:</label>
            <input type="text" name="stoimost" id="stoimost" class="form-control">
        </div>
        <div class="form-group col-sm-6">
            <label for="adress">Адрес:</label>
            <input type="text" name="adress" id="adress" class="form-control">
        </div>
        <div class="form-group col-sm-6">
            <label for="zhilploshad">Жилая площадь:</label>
            <input type="text" name="zhilploshad" id="zhilploshad" class="form-control">
        </div>
        <div class="form-group col-sm-6">
            <label for="etazh">Этаж:</label>
            <input type="text" name="etazh" id="etazh" class="form-control">
        </div>
        <div class="form-group col-sm-12">
            <label for="gallery-images">Фотографии галереи:</label>
            <input type="file" name="gallery_images[]" id="gallery-images" multiple class="form-control">
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" value="Добавить" class="btn btn-primary">
        </div>
    </form>
</div>

<script>
    jQuery(document).ready(function ($) {
        $('#add-property-form').submit(function (e) {
            e.preventDefault();

            var form = $(this);
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData,
                success: function (response) {
                    alert(response);
                    form.trigger('reset');
                }
            });
        });
    });
</script>


<?php get_footer(); ?>
