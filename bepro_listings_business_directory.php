<?php
/*
Plugin Name: BePro Listings Business Directory
Plugin Script: bepro_listings_business_directory.php
Plugin URI: http://www.beprosoftware.com/shop
Description: Create your wordpress business or staff directory with our various listing layouts. Showcase information like, contact , location, description. Also showcase media like photos, videos, and documents
Version: 1.0.0
License: GPL V3
Author: BePro Software Team
Author URI: http://www.beprosoftware.com


Copyright 2012 [Beyond Programs LTD.](http://www.beyondprograms.com/)

Commercial users are requested to, but not required to contribute, promotion, 
know-how, or money to plug-in development or to www.beprosoftware.com. 

This file is part of BePro Listings.

BePro Listings is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

BePro Listings is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with BePro Listings.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( !defined( 'ABSPATH' ) ) exit;

class Bl_business_directory{

	/**
	 * Welcome to BePro Listings Business Directory, part of the BePro Software collection.
	*/
	 
	//Start
	function __construct() {
		add_action("wp_head",array($this,"start_header"));
		add_filter("bl_start_bepro_listing_template", array($this,"start_listing"));
		add_filter("bl_before_bepro_listing_template", array($this,"before_listing"), 10, 3);
		add_filter("bl_after_bepro_listing_template", array($this,"after_listing"), 10, 3);
		add_filter("bl_end_bepro_listing_template", array($this,"end_listing"));
		add_shortcode("bl_alpha_list", array( $this, "alpha_list"));
	}
	
	function start_header(){
		echo '
			<style type="text/css">
				.alpha_list{clear:both;display:block;height: 20px;}
				.alpha_list li{float:left; list-style-type:none;margin-left: 10px;}
			</style>
		';
	}
	
	function alpha_list(){
		$return_str = "";
		$alpha_list = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$return_str.= '<ul class="alpha_list">';
		foreach($alpha_list as $item){
			$return_str.= '<li><a href="#'.$item.'">'.$item.'</a></li>';
		}
		$return_str.= '</ul>';
		
		return $return_str;
	}
	
	function start_listing($type){
		
		if($type == "bl2")
			$return_str.= "<table class='bl_business_listings_list_2'>";
			
		if($type == "bl2b")
			$return_str.= "<div class='bl_business_listings_list_2b'>";
		
		return $return_str;
	}
	
	function before_listing($type,$result, $previous){
		$return_str = "";
		if(($type == "bl1")){
			if(empty($result->post_title)) return;
			$return_str.= '<div class="bl_directory_1">';
			$first = ucfirst(substr($result->post_title, 0, 1));
			$last = ucfirst(substr($previous->post_title, 0, 1));
			if(!empty($previous) && ($first != $last)){
				if($first != "A")
					$return_str.= "<span class='return_begin'><a href='#begin'>Back to Top</a></span>";
				$return_str.= "<h2><a name='".$first."'>".$first."</a></h2>";
			}else if(empty($previous)){
				$return_str.= "<h2><a name='".$first."'>".$first."</a></h2>";
			}
		}else if(($type == "bl3") || ($type == "bl4")){
			if(empty($result->post_title)) return;
			$first = ucfirst(substr($result->post_title, 0, 1));
			$last = ucfirst(substr($previous->post_title, 0, 1));
			if(!empty($previous) && ($first != $last)){
				if($first != "A")
					$return_str.= "<span class='return_begin'><a href='#begin'>Back to Top</a></span>";
				$return_str.= "<h2><a name='".$first."'>".$first."</a></h2>";
			}else if(empty($previous)){
				$return_str.= "<h2><a name='".$first."'>".$first."</a></h2>";
			}
		}else if(($type == "bl1b") || ($type == "bl2b")){
			if(empty($result->last_name)) return;
			$return_str.= '<div class="bl_directory_1">';
			$first = ucfirst(substr($result->last_name, 0, 1));
			$last = ucfirst(substr($previous->last_name, 0, 1));
			if(!empty($previous) && ($first != $last)){
				if($first != "A")
					$return_str.= "<span class='return_begin'><a href='#begin'>Back to Top</a></span>";
				$return_str.= "<h2><a name='".$first."'>".$first."</a></h2>";
			}else if(empty($previous)){
				$return_str.= "<h2><a name='".$first."'>".$first."</a></h2>";
			}
		}else if($type == "bl2"){
			$first = ucfirst(substr($result->last_name, 0, 1));
			$last = ucfirst(substr($previous->last_name, 0, 1));
			$return_str .= "<tr><td>";
			if(!empty($last) && ($first != $last)){
					$return_str.= "<a name='".$first."'></a>";
			}
		}else if($type == "bl2b"){
			$return_str.= "<div id='contact_info_bl2b'>";
		}	
		return $return_str;
	}
	
	function after_listing($type,$result, $previous){
		if(($type == "bl1") || ($type == "bl1b")){
			if(empty($result->last_name))return;
			$return_str .= "</div>";
		}	
			
		if($type == "bl2")
			$return_str .= "</td></tr>";
			
		if($type == "bl2b")
			$return_str .= "</div>";
			
		return $return_str;	
	}
	function end_listing($type){
			
		if(($type == "bl1") || ($type == "bl1b"))
			$return_str.= "<span class='return_begin'><a href='#begin'>Back to Top</a></span>";
			
		if($type == "bl2")
			$return_str .= "</table>";
			
		if($type == "bl2b")
			$return_str .= "</div>";
		
		return $return_str;			
	}
	
	function bepro_listings_list_business_contact_name($bp_listing){
		if(empty($bp_listing->last_name))return;
		$permalink = get_permalink( $bp_listing->post_id );
		echo "<span class='result_contact'><a href='$permalink'>".$bp_listing->first_name." ".$bp_listing->last_name."</a></span>";
	}
	function bepro_listings_list_business_contact_item($bp_listing){
		if(empty($bp_listing->last_name))return;
		$permalink = get_permalink( $bp_listing->post_id );
		echo "<span class='result_contact'><a href='$permalink'>".$bp_listing->post_title."</a></span>";
	}
	
	function spawn(){
		$data = get_option("bepro_listings");
		
		$data['bepro_listings_list_template_bl1'] = array(
		"bepro_listings_list_title" => array("Bl_business_directory","bepro_listings_list_business_contact_item"),
		"style" => plugins_url("css/bl_business_directory_1.css", __FILE__ ), 
		"template_file" => plugin_dir_path( __FILE__ ).'/templates/bl_directory_1.php');
		
		$data['bepro_listings_list_template_bl1b'] = array(
		"bepro_listings_list_title" => array("Bl_business_directory","bepro_listings_list_business_contact_name"),
		"style" => plugins_url("css/bl_business_directory_1.css", __FILE__ ), 
		"template_file" => plugin_dir_path( __FILE__ ).'/templates/bl_directory_1.php');
		
		$data['bepro_listings_list_template_bl2'] = array(
		"bepro_listings_list_title" => array("Bl_business_directory","bepro_listings_list_business_contact_item"),
		"bepro_listings_list_below_title" => "bepro_listings_list_geo_template",
		"bepro_listings_list_image" => "bepro_listings_list_phone_template",
		"bepro_listings_list_after_image" => "bepro_listings_list_email_template",
		"bepro_listings_list_content" => "bepro_listings_list_category_template",
		"bepro_listings_list_end" => "bepro_listings_list_links_template",
		"style" => plugins_url("css/bl_business_directory_2.css", __FILE__ ),
		"template_file" => plugin_dir_path( __FILE__ ).'/templates/bl_directory_2.php');
		
		$data['bepro_listings_list_template_bl2b'] = array(
		"bepro_listings_list_title" => array("Bl_business_directory","bepro_listings_list_business_contact_name"),
		"bepro_listings_list_below_title" => "bepro_listings_list_geo_template",
		"bepro_listings_list_image" => "bepro_listings_list_phone_template",
		"bepro_listings_list_after_image" => "bepro_listings_list_email_template",
		"bepro_listings_list_content" => "bepro_listings_list_category_template",
		"bepro_listings_list_end" => "bepro_listings_list_links_template",
		"style" => plugins_url("css/bl_business_directory_2.css", __FILE__ ),
		"template_file" => plugin_dir_path( __FILE__ ).'/templates/bl_directory_2.php');
		
		$data['bepro_listings_list_template_bl3'] = array(
		"bepro_listings_list_title" => "bepro_listings_list_title_template",
		"bepro_listings_list_below_title" => "bepro_listings_list_geo_template",
		"bepro_listings_list_image" => "bepro_listings_list_phone_template",
		"bepro_listings_list_after_image" => "bepro_listings_list_email_template",
		"bepro_listings_list_content" => "bepro_listings_list_category_template",
		"bepro_listings_list_end" => "bepro_listings_list_links_template",
		"style" => plugins_url("css/bl_business_directory_3.css", __FILE__ ),
		"template_file" => WP_PLUGIN_DIR.'/bepro-listings/templates/listings/generic_1.php'
		);
		
		$data['bepro_listings_list_template_bl4'] = array(
		"bepro_listings_list_title" => "bepro_listings_list_title_template",
		"bepro_listings_list_above_image" => "bepro_listings_list_featured_template",
		"bepro_listings_list_below_title" => "bepro_listings_list_category_template",
		"bepro_listings_list_above_title" => "bepro_listings_list_image_template",
		"bepro_listings_list_image" => "bepro_listings_list_geo_template",
		"bepro_listings_list_content" => "bepro_listings_list_phone_template",
		"bepro_listings_after_content" => "bepro_listings_list_email_template",
		"bepro_listings_list_end" => "bepro_listings_list_links_template", 
		"style" => plugins_url("css/bl_business_directory_4.css", __FILE__ ), 
		"template_file" => WP_PLUGIN_DIR.'/bepro-listings/templates/listings/generic_2.php');
		
		
		
		update_option("bepro_listings", $data);
	}

}

register_activation_hook( __FILE__, array( 'Bl_business_directory', 'spawn' ) );
$suit_up = new Bl_business_directory();