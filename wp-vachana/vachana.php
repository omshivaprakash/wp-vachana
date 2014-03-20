<?php
/*
Plugin Name: Vachana Sanchaya Daily Vachana
Plugin URI: https://github.com/omshivaprakash/wp-vachana
Description: This widget is disgned to display the daily vachana from the site vachana.sanchaya.net to wordpress blogs. 
Author: Omshivaprakash H L
Version: 1.0
Author URI: http://www.linuxaayana.net
*/

/*  This file is part of Vachana Sanchaya Daily Vachana plugin, developed by Omshivaprakash H L (email: naan@shivu.in)

    Vachana sahitya (Kannada: ವಚನ ಸಾಹಿತ್ಯ) is a form of rhythmic writing in Kannada 
    that evolved in the 11th Century C.E. and flourished in the 12th century, as a part of the Lingayatha 'movement'. 
    Vachana Sanchaya is a website dedicated for linquistic research on "Vachana Sahitya". It provides access to 
    21000 Vachana's (verses of poems) written by 258 Vachanakaara's (Poets/Creators of those Vachanas) in Unicode. 

    Vachana Sanchaya Daily Vachana Plugin is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Vachana Sanchaya Daily Vachana Plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Mankutimmana Kagga plugin. If not, see <http://www.gnu.org/licenses/>.
*/


function todays_vachana() {
	 $url_send='http://vachana.sanchaya.net/api/today_vachana';
                        $curl = curl_init();
                         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                         curl_setopt($curl, CURLOPT_POST, false);
                         curl_setopt($curl, CURLOPT_URL, $url_send);
                         curl_setopt($curl, CURLOPT_HEADER, false);
                         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                        $response = curl_exec($curl);
                        curl_close($curl);
                        $decoded_response=json_decode($response);

        $vachana_info = explode("\n", $decoded_response->vachana->vachana);
        $display = null;
        foreach ($vachana_info as $key=>$value)
                {
                        $display .= $value."<br />";
                }
        $display .=  ": <b>". $decoded_response->vachana->vachanakaara->name."</b>";

	// Display Vachana
	return $display;
}

function show_vachana() {
	$vachanas = todays_vachana();
	echo "<p id='vachana' class='vachana'>$vachanas</p>";
}

class Vachana_Sanchaya_Widget extends WP_Widget {
			
	function __construct() {
    	$widget_ops = array(
			'classname'   => 'vachana_sanchaya_widget', 
			'description' => __('Show todays\'s Vachana from Vachana Sanchaya Website. ವಚನ ಸಂಚಯ ವೆಬ್‌ಸೈಟ್‌ನಿಂದ ಇಂದಿನ ವಚನ ತೋರಿಸಿ.')
		);
    	parent::__construct('vachana-sanchaya-vachana', __('Vachana Sanchaya Daily Vachana'), $widget_ops);
	}
	
	function widget($args, $instance) {
           
			extract( $args );
		
			$title = apply_filters( 'widget_title', empty($instance['title']) ? 'ವಚನ ಸಂಚಯ: ಇಂದಿನ ವಚನ' : $instance['title'], $instance, $this->id_base);	
			
			echo $before_widget;
						
			// Widget title
			
			echo $before_title;
			
			echo $instance["title"];
			
			echo $after_title;
						
			// Call show_vachana function
			
    			show_vachana();

		echo $after_widget;

	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
	     
        		return $instance;
	}
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : 'ವಚನ ಸಂಚಯ: ಇಂದಿನ ವಚನ ';
		
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
             
<?php
	}
}

function mtk_register_widgets() {
	register_widget( 'Vachana_Sanchaya_Widget' );
}

add_action( 'widgets_init', 'mtk_register_widgets' );

// Donate link on manage plugin page
function mtk_pluginspage_links( $links, $file ) {

$plugin = plugin_basename(__FILE__);

// create links
if ( $file == $plugin ) {
return array_merge(
$links,
array( '<a href="http://vachana.sanchaya.net" target="_blank" title="Vachana Sanchaya Website">Vachana Sanchaya</a>',
'<a href="http://linuxaayana.net" target="_blank" title="View more FOSS plugins for Kannada">Linuxaayana</a>',
'<a href="http://twitter.com/omshivaprakash" target="_blank" title="Follow Omshivaprakash!">Follow on Twitter</a>'
 )
);
			}
return $links;

	}
add_filter( 'plugin_row_meta', 'mtk_pluginspage_links', 10, 2 );
?>
