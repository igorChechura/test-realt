<?php
// agency post type
add_action( 'init', 'agency_post_type_init' );
 
function agency_post_type_init() {
	$labels = array(
		'name' => 'Агентство',
		'singular_name' => 'Агентство',
		'add_new' => 'Добавить агентство',
		'add_new_item' => 'Добавить агентство',
		'edit_item' => 'Редактировать агентство',
		'new_item' => 'Новое агентство',
		'all_items' => 'Все агентства',
		'view_item' => 'Просмотр агентств на сайте',
		'search_items' => 'Искать агентства',
		'not_found' =>  'Агентства не найдены.',
		'not_found_in_trash' => 'В корзине нет агентств.',
		'set_featured_image' => 'Установить изображение агентства',
		'remove_featured_image' => 'Удалить изображение агентства',
		'menu_name' => 'Агентства'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'has_archive' => true, 
		'menu_position' => 20,
		'menu_icon' => 'dashicons-businessperson',
		'supports' => array( 'title', 'editor')
	);
	register_post_type('agency', $args);
}



// realty post type
add_action( 'init', 'realty_post_type_init' );
 
function realty_post_type_init() {
	$labels = array(
		'name' => 'Недвижимость',
		'singular_name' => 'Недвижимость',
		'add_new' => 'Добавить недвижимость',
		'add_new_item' => 'Добавить недвижимость',
		'edit_item' => 'Редактировать недвижимость',
		'new_item' => 'Новая недвижимость',
		'all_items' => 'Вся недвижимость',
		'view_item' => 'Просмотр недвижимости на сайте',
		'search_items' => 'Искать недвижимость',
		'not_found' =>  'Недвижимость не найдена.',
		'not_found_in_trash' => 'В корзине нет недвижимости.',
		'set_featured_image' => 'Установить изображение недвижимости',
		'remove_featured_image' => 'Удалить изображение недвижимости',
		'menu_name' => 'Недвижимость'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'has_archive' => true, 
		'menu_position' => 21,
		'menu_icon' => 'dashicons-admin-multisite',
		'supports' => array( 'title', 'editor')
	);
	register_post_type('realty', $args);
}



// realty type taxonomy
add_action( 'init', 'create_realty_type_taxonomy', 0 );

function create_realty_type_taxonomy(){
    $labels = array(
        'name' => _x( 'Тип недвижимости', 'taxonomy general name' ),
        'singular_name' => _x( 'Тип недвижимости', 'taxonomy singular name' ),
        'search_items' =>  __( 'Найти тип недвижимости' ),
        'all_items' => __( 'Все типы недвижимости' ),
        'parent_item' => __( 'Родительский тип недвижимости' ),
        'parent_item_colon' => __( 'Родительский тип недвижимости' ),
        'edit_item' => __( 'Редактировать тип недвижимости' ),
        'update_item' => __( 'Обновить тип недвижимости' ),
        'add_new_item' => __( 'Добавить новый тип недвижимости' ),
        'new_item_name' => __( 'Название нового типа недвижимости' ),
        'menu_name' => __( 'Типы недвижимости' ),
    );

    register_taxonomy('realty_type', array('realty'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true
    ));

}



// agency and realty post types connection
add_action('add_meta_boxes', function () {
	add_meta_box( 'realty_agency', 'Агентство недвижимости', 'realty_agency_metabox', 'realty', 'side', 'low'  );
}, 1);

function realty_agency_metabox( $post ){
	$agencies = get_posts(array(
        'post_type'=>'agency',
        'posts_per_page'=>-1,
        'orderby'=>'post_title',
        'order'=>'ASC'
    ));

	if( $agencies ){

		echo '
		<div style="max-height:200px; overflow-y:auto;">
			<ul>
		';

		foreach( $agencies as $agency ){
			echo '
			<li><label>
				<input type="radio" name="post_parent" value="'. $agency->ID .'" '. checked($agency->ID, $post->post_parent, 0) .'> '. esc_html($agency->post_title) .'
			</label></li>
			';
		}

		echo '
			</ul>
		</div>';
	}
	else
		echo 'Агентств нет...';
}



// render realty front page

add_action( 'wp_ajax_agency_filter', 'agency_render' );
add_action( 'wp_ajax_nopriv_agency_filter', 'agency_render' );

function agency_render(){
	$agency_ID = isset( $_POST[ 'agency_item' ] ) ? $_POST[ 'agency_item' ] : false;

	$posts = get_transient( 'special_query_' . $agency_ID );

	if ( false === $posts ) {

		if(!$agency_ID) {		

			$args = array(
				'post_type' => 'realty',
				'posts_per_page' => -1,
				'orderby' => 'post_title',
				'order' => 'ASC'
			);
		} else {		
			$args = array(
				'post_type' => 'realty',
				'post_parent' => $agency_ID,
				'posts_per_page' => -1,
				'orderby' => 'post_title',
				'order' => 'ASC'
			);
		}

		$posts = new WP_Query( $args );

		set_transient( 'special_query_' . $agency_ID, $posts);

	}

	if( $posts->have_posts() ):
	?>

	<ul class="list-group">

		<?php 
		while( $posts->have_posts() ):
			$posts->the_post();
		?>
		
			<li class="list-group-item card" style="margin-bottom:10px">
				<div class="card-body">
					<h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="card-text">
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
					<a href="<?php the_permalink(); ?>" class="btn btn-primary" style="margin-top:10px">Подробнее</a>
				</div>
			</li>
		
		<?php endwhile; ?>
		
	</ul>
	<?php endif;

	if ( $agency_ID ) {
		die; 
	}
}