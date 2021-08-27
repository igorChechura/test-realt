<?php get_header(); ?>

<div class="container">
    <div class="col-sm-12 col-md-3">
        <h2>Агентства</h2>

        <?php
        $agencies = get_posts(array(
            'post_type'=>'agency',
            'posts_per_page'=>-1,
            'orderby'=>'post_title',
            'order'=>'ASC'
        ));
        
        if( $agencies ): ?>
        <ul class="list-group">
        <?php foreach( $agencies as $agency ): ?>
            <li class="list-group-item card" style="margin-bottom:10px">
                <div class="card-body">
                    <h4 class="card-title"><a href="#" class="agency-item" data-agency="<?php echo $agency->ID; ?>"><?php echo $agency->post_title; ?></a></h4>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

    <div class="col-sm-12 col-md-9">
        <h2>Объекты недвижимости</h2>
        
        <div class="realty-wrapper">
        <?php
            agency_render();
        ?>
        </div>
        
    </div>
</div>

<script>

    jQuery( document ).ready(function() {
      
		function startFilter(e){
            e.preventDefault();

			var agency_item = jQuery(this).attr('data-agency');

			jQuery.ajax({
				url: '<?php echo admin_url("admin-ajax.php") ?>',
				type: 'POST',
				data: {
					action: 'agency_filter',
					agency_item: agency_item
				},
				beforeSend: function( xhr ) {

				},
				success: function( data ) {
					jQuery('.realty-wrapper').html(data);
				}
			});
		}

        jQuery('.agency-item').click(startFilter)
    
    }); // end ready

</script>

<?php get_footer(); ?>

