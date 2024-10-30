<?php
/*******************************
 * Plugin Name:  Cron Time
 * Plugin URI:   https://ziscom.today/2019/06/22/cron-time/
 * Description:  See all the cron active in your WordPress site along with their hook name.
 * Version:      1.0
 * Author:       Ziscom
 * Author URI:   https://ziscom.today
 * License:      GPL2 or later
 *******************************/

// व्यवस्थापक मेनू जोड़ने के लिए हुक
add_action('admin_menu', 'ct_cron_panel_page');

// ऊपर हुक के लिए कार्रवाई समारोह
if ( !function_exists( 'ct_cron_panel_page' ) ) {
	function ct_cron_panel_page() {
		// सेटिंग्स के तहत एक नया मेनू जोड़ें
		add_menu_page(__('ct_cron_panel', 'cron-panel'), __('Cron Panel', 'cron-panel'), 'manage_options', 'ct_cron_panel', 'ct_cron_panel');
	}
}

// प्लगइन डैश बोर्ड पृष्ठ
function ct_cron_panel() {
	?>
<h1> Cron list </h1>
    <?php
//echo '<pre>'; print_r( _get_cron_array() ); echo '</pre>';
$cron = _get_cron_array();
$crons  = _get_cron_array();
		$events = array();

		if ( empty( $crons ) ) {
			return new WP_Error(
				'no_events',
				__( 'You currently have no scheduled cron events.', 'wp-crontrol' )
			);
		}
		?>
					<style>
					table, th, td {
					  border: 1px solid black;
					  border-collapse: collapse;
					}
					th, td {
					  padding: 15px;
					}
					</style>
					
					<table style="width:100%;">
					  <tr>
						<th>WP Hook Name</th>
						<th>Schedule</th> 
						<th>Interval</th>						
					  </tr>
					  <?
		foreach ( $crons as $time => $cron ) {
			foreach ( $cron as $hook => $dings ) {
				foreach ( $dings as $sig => $data ) {

					// This is a prime candidate for a Crontrol_Event class but I'm not bothering currently.
					$events[ "$hook-$sig-$time" ] = (object) array(
						'hook'     => $hook,
						'time'     => $time,
						'sig'      => $sig,
						'args'     => $data['args'],
						'schedule' => $data['schedule'],
						'interval' => isset( $data['interval'] ) ? $data['interval'] : null,
					);

					?>
					  <tr>
						<td><?php echo $hook; ?></td>
						<td><?php echo $data['schedule']; ?></td> 
						<td><?php echo $data['interval']; ?></td>						
					  </tr>
					<?                      
				}
			}
		}
		?>
							</table>
<?

		return $events;

	}



