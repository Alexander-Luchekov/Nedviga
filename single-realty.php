<?php get_header(); ?>
 <div class="container my-4">
            <div class="row">

<div class="col-sm-6 col-md-3">  
    <div class="card mb-4">  
        <div class="card-body">  
            <?php 
$gallery = explode( ',', get_post_meta( get_the_ID(), 'realty_gallery', true ) );
if ( $gallery ) : 
    foreach ( $gallery as $image_id ) {
        echo wp_get_attachment_image( $image_id, 'medium' );
    }
endif; 
?> 
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

 </div>
</div>

<?php get_footer(); ?>