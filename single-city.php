<?php get_header(); ?>
 <div class="container my-4">
            <div class="row">
<?php
// Получение ID текущего города из URL (например, '/city/moscow/')
$current_city_slug = basename( get_permalink() );

// Поиск города по его слагу
$current_city = get_posts( array(
   'post_type'       => 'city',
   'posts_per_page'  => 1,
   'name'            => $current_city_slug,
   'post_status'     => 'publish',
) );

if ( $current_city ) {
   // Получение ID текущего города
   $current_city_id = $current_city[0]->ID;

   // Поиск 10 последних объектов недвижимости для текущего города
   $properties = get_posts( array(
       'post_type'      => 'realty',
       'posts_per_page' => 10,
       'meta_key'       => 'selected_city',
       'meta_value'     => $current_city_id,
       'orderby'        => 'date',
       'order'          => 'DESC',
   ) );

   echo '<h2>' . $current_city[0]->post_title . '</h2>';
   if ( $properties ) {
       foreach ( $properties as $property ) {
           $gallery = explode( ',', get_post_meta( $property->ID, 'realty_gallery', true ) );
           $post_title = get_the_title( $property->ID );
           $post_link = get_permalink( $property->ID );
           $ploshad = get_post_meta( $property->ID, 'ploshad', true );
           $stoimost = get_post_meta( $property->ID, 'stoimost', true );
           $adress = get_post_meta( $property->ID, 'adress', true );
           $zhilploshad = get_post_meta( $property->ID, 'zhilploshad', true );
           $etazh = get_post_meta( $property->ID, 'etazh', true );
           $tip_terms = get_the_terms( $property->ID, 'tip' );
          
           echo '<div class="col-sm-6 col-md-3">';
           echo '<div class="card mb-4">';
           echo '<div class="card-body">';
           if ( $gallery ) {
               echo wp_get_attachment_image( $gallery[0], 'medium' );
           }
           echo '<a href="' . $post_link . '"><h5 class="card-title" style="margin-top:15px;">' . $post_title . '</h5></a>';
           if ( $ploshad || $stoimost || $adress || $zhilploshad || $etazh ) {
               echo '<ul class="list-unstyled mt-3">';
               if ( $ploshad ) {
                   echo '<li><strong>Площадь:</strong> ' . $ploshad . '</li>';
               }
               if ( $stoimost ) {
                   echo '<li><strong>Стоимость:</strong> ' . $stoimost . '</li>';
               }
               if ( $adress ) {
                   echo '<li><strong>Адрес:</strong> ' . $adress . '</li>';
               }
               if ( $zhilploshad ) {
                   echo '<li><strong>Жилая площадь:</strong> ' . $zhilploshad . '</li>';
               }
               if ( $etazh ) {
                   echo '<li><strong>Этаж:</strong> ' . $etazh . '</li>';
               }
               echo '</ul>';
           }
           if ( $tip_terms ) {
               echo '<div class="card-footer">';
               echo '<small class="text-muted">';
               foreach ( $tip_terms as $tip_term ) {
                   echo $tip_term->name;
               }
               echo '</small>';
               echo '</div>';
           }
           echo '</div>';
           echo '</div>';
           echo '</div>';
       }
   } else {
       echo 'Нет доступных объектов недвижимости для этого города.';
   }
} else {
   echo 'Город не найден.';
}
   ?>
	 </div>
</div>

<?php get_footer(); ?>