<?php
namespace Cornerstone\Theme;
?>
<div class="input-wrapper neighborhoods-input-wrapper">
	<div class="dropdown multiselect neighborhoods">
		<button name="neighborhoods" class="btn dropdown-toggle" type="button" data-toggle="dropdown" data-reset='Neighborhoods' aria-haspopup="true" aria-expanded="false">Neighborhoods</button>
		<div class="dropdown-menu checkboxes">
			<div class="checkbox-container">
			<?php
			$neighborhoods = Neighborhoods::getNeighborhoods();

			foreach ( $neighborhoods as $neighborhood ) :
                $in = isset($_REQUEST['neighborhoods']) ? in_array($neighborhood->ID, $_REQUEST['neighborhoods']) : false;

				echo sprintf(
					'<div class="cbox %s"><input type="checkbox" name="neighborhoods[]" id="neighborhoods_%s" value="%s" %s /> <label for="neighborhoods_%s">%s</label></div>',
					$in ? 'checked' : '',
					$neighborhood->ID,
					$neighborhood->ID,
					checked( $in, true, false ),
					$neighborhood->ID,
					$neighborhood->post_title
				);
			endforeach;
			?>
			</div>

			<?php get_template_part('template-parts/apartment-search/filter','clear-apply'); ?>
		</div>
	</div>
</div>
