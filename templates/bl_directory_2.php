<?php
	do_action( 'bepro_listings_list_title', $result);
	do_action( 'bepro_listings_list_below_title', $result);
	do_action( 'bepro_listings_list_after_image', $result);
	do_action("bepro_listings_list_content", $result);
	do_action("bepro_listings_list_end", $result);
?>