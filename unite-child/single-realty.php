<?php get_header(); ?>

<div class="container">
    <div class="col-sm-12">
        <div class="col-md-6">
            <h2><?php the_title(); ?></h2>

            <?php             
            $cur_terms = get_the_terms( get_the_ID(), 'realty_type' );
            if( is_array( $cur_terms ) ):?>
                <div>
                    <b>Тип недвижимости:</b>
                    <?php foreach( $cur_terms as $key => $cur_term ) {
                        if($key == 0){
                            echo $cur_term->name;
                        } else {
                            echo ', ' . $cur_term->name;
                        }
                    } 
                    ?>
                </div>
            <?php endif; ?>

            <?php if( get_field('realty_price') ): ?>
                <div>
                    <b>Цена:</b> <?php the_field('realty_price'); ?> $
                </div>
            <?php endif; ?> 

            <?php if( get_field('realty_square') ): ?>
                <div>
                    <b>Площадь:</b> <?php the_field('realty_square'); ?> м<sup>2</sup>
                </div>
            <?php endif; ?> 

            <?php if( get_field('realty_living_space') ): ?>
                <div>
                    <b>Жилая площадь:</b> <?php the_field('realty_living_space'); ?> м<sup>2</sup>
                </div>
            <?php endif; ?> 

            <?php if( get_field('realty_floor') ): ?>
                <div>
                    <b>Этаж:</b> <?php the_field('realty_floor'); ?>
                </div>
            <?php endif; ?> 

            <?php if( get_field('realty_address') ): ?>
                <div>
                    <b>Адрес:</b> <?php the_field('realty_address'); ?>
                </div>
            <?php endif; ?> 
        </div>             
    </div>
</div>

<?php get_footer(); ?>