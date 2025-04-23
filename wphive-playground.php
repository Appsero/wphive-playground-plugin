<?php
/**
 * Plugin Name: Hive Playground
 * Plugin URI: https://wphive.com
 * Description: Removes all default WordPress dashboard widgets and displays Dokan plugin promotion with FlyWP recommendation.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://wphive.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: hive-playground
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Main plugin class
 */
class WP_Hive_Playground {

	/**
	 * Recommended plugins data
	 */
	private $recommended_plugins = array();

	/**
	 * Initialize the plugin
	 */
	public function __construct() {
		// Set up recommended plugins
		$this->setup_recommended_plugins();

		// Hook into WordPress
		add_action( 'wp_dashboard_setup', array( $this, 'remove_default_dashboard_widgets' ), 1 );
		add_action( 'admin_head-index.php', array( $this, 'add_custom_styles' ) );

		// Add our content directly to the dashboard
		add_action( 'admin_notices', array( $this, 'add_dashboard_notice' ) );
		add_action( 'admin_head', array( $this, 'hide_default_dashboard_elements' ) );

		// Add top promotion bar for FlyWP on all admin pages
		add_action( 'admin_notices', array( $this, 'add_flywp_promo_bar' ), 1 );
		add_action( 'admin_head', array( $this, 'add_promo_bar_styles' ) );
	}

	/**
	 * Setup recommended plugins data
	 */
	private function setup_recommended_plugins() {
		$this->recommended_plugins = array(
			// Featured plugin (first in array)
			array(
				'name'        => 'Dokan',
				'logo'        => 'https://dokan.co/app/uploads/2024/02/dokan-new-white-logo.svg',
				'description' => 'Transform your WordPress site into a thriving marketplace',
				'features'    => array(
					'Create a marketplace like Amazon, Etsy, or eBay',
					'Let vendors manage their own shops with dedicated dashboards',
					'Multiple commission types and withdrawal methods',
					'Seamless integration with popular payment gateways',
				),
				'cta_text'    => 'Get Dokan Pro',
				'cta_url'     => 'https://dokan.co/wordpress/',
				'testimonial' => 'Trusted by 50,000+ entrepreneurs worldwide',
			),
			array(
				'name'        => 'WP User Frontend Pro',
				'logo'        => 'https://wedevs.com/img/wedevs/products/product-icons/product-wpuf.svg',
				'description' => 'The most customizable eCommerce platform for building your online business',
				'features'    => array(
					'Sell products and services online',
					'Flexible shipping options',
					'Secure payments',
					'WordPress integration',
				),
				'cta_text'    => 'Get WP User Frontend Pro',
				'cta_url'     => 'https://woocommerce.com/',
				'testimonial' => 'Powering over 30% of all online stores',
			),
			array(
				'name'        => 'WP ERP Pro',
				'logo'        => 'https://wedevs.com/img/wedevs/products/product-icons/product-wperp.svg',
				'description' => 'Complete HR solution with recruitment & job listings',
				'features'    => array(
					'Visual drag & drop editor',
					'Responsive design controls',
					'Extensive widget library',
					'Theme builder functionality',
				),
				'cta_text'    => 'Get WP ERP',
				'cta_url'     => 'https://wperp.com/',
				'testimonial' => 'Used by professional web creators worldwide',
			),
		);
	}

	/**
	 * Remove all default dashboard widgets
	 */
	public function remove_default_dashboard_widgets() {
		global $wp_meta_boxes;

		// Clear all dashboard widgets from all locations and contexts
		$wp_meta_boxes['dashboard'] = array(
			'normal' => array(
				'core' => array(),
				'high' => array(),
				'low'  => array(),
			),
			'side'   => array(
				'core' => array(),
				'high' => array(),
				'low'  => array(),
			),
		);
	}

	/**
	 * Hide default dashboard elements with CSS
	 */
	public function hide_default_dashboard_elements() {
		$screen = get_current_screen();

		// Only apply on dashboard
		if ( $screen && $screen->id === 'dashboard' ) {
			?>
			<style type="text/css">
				/* Hide welcome panel */
				#welcome-panel,
				#dashboard-widgets-wrap {
					display: none !important;
				}

				/* Hide screen options and help tabs */
				#screen-options-link-wrap,
				#contextual-help-link-wrap {
					display: none !important;
				}

				/* Hide "Thank you for creating with WordPress" footer */
				#wpfooter {
					display: none !important;
				}

				/* Add padding to top of dashboard */
				#wpcontent {
					padding-top: 20px;
				}

				/* Make page title smaller to save space */
				.wrap > h1 {
					display: none;
				}
			</style>
			<?php
		}
	}

	/**
	 * Add our dashboard notice that will contain our custom content
	 */
	public function add_dashboard_notice() {
		$screen = get_current_screen();

		// Only show on dashboard
		if ( $screen && $screen->id === 'dashboard' ) {
			// Close any existing notices
			echo '<div class="clear"></div>';

			// Render our recommendations page
			$this->render_recommendations_page();
		}
	}

	/**
	 * Render our recommendations page
	 */
	public function render_recommendations_page() {
		?>
		<div class="hive-recommendations-wrap">
			<div class="hive-recommendations-container">
				<!-- Left column: Recommended plugins -->
				<div class="hive-column hive-plugins-column">
					<h2>Recommended Plugins</h2>

					<?php
					// Featured plugin (first in array)
					$featured_plugin = $this->recommended_plugins[0];
					?>
					<div class="hive-featured-plugin">
						<div class="hive-plugin-logo">
							<img src="<?php echo esc_url( $featured_plugin['logo'] ); ?>" alt="<?php echo esc_attr( $featured_plugin['name'] ); ?>">
						</div>
						<div class="hive-plugin-content">
							<h3><?php echo esc_html( $featured_plugin['name'] ); ?></h3>
							<p class="hive-plugin-description"><?php echo esc_html( $featured_plugin['description'] ); ?></p>

							<ul class="hive-plugin-features">
								<?php foreach ( $featured_plugin['features'] as $feature ) : ?>
									<li><?php echo esc_html( $feature ); ?></li>
								<?php endforeach; ?>
							</ul>

							<p class="hive-plugin-cta">
								<a href="<?php echo esc_url( $featured_plugin['cta_url'] ); ?>" class="button button-primary" target="_blank">
									<?php echo esc_html( $featured_plugin['cta_text'] ); ?> →
								</a>
							</p>

							<p class="hive-plugin-testimonial"><?php echo esc_html( $featured_plugin['testimonial'] ); ?></p>
						</div>
					</div>

					<div class="hive-other-plugins">
						<?php
						// Loop through other plugins (skip the first one as it's featured)
						for ( $i = 1; $i < count( $this->recommended_plugins ); $i++ ) :
							$plugin = $this->recommended_plugins[ $i ];
							?>
							<div class="hive-plugin-card">
								<div class="hive-plugin-card-logo">
									<img src="<?php echo esc_url( $plugin['logo'] ); ?>" alt="<?php echo esc_attr( $plugin['name'] ); ?>">
								</div>
								<div class="hive-plugin-card-content">
									<h4><?php echo esc_html( $plugin['name'] ); ?></h4>
									<p><?php echo esc_html( $plugin['description'] ); ?></p>
									<a href="<?php echo esc_url( $plugin['cta_url'] ); ?>" class="button" target="_blank">
										<?php echo esc_html( $plugin['cta_text'] ); ?>
									</a>
								</div>
							</div>
						<?php endfor; ?>
					</div>
				</div>

				<!-- Right column: Recommended hosting -->
				<div class="hive-column hive-hosting-column">
					<h2>Recommended Hosting</h2>

					<div class="hive-hosting-card">
						<div class="hive-hosting-logo">
							<img src="https://flywp.com/wp-content/uploads/2025/01/Logo-full-main-png.png" alt="FlyWP">
						</div>

						<p class="hive-hosting-tagline">Premium WordPress Server Management</p>

						<div class="hive-hosting-features">
							<h4>Why choose FlyWP?</h4>
							<ul>
								<li>Lightning-fast performance with optimized server configuration</li>
								<li>Advanced security measures to protect your WordPress site</li>
								<li>Automatic backups and one-click restore points</li>
								<li>24/7 expert WordPress support</li>
								<li>Seamless scaling for high-traffic websites</li>
								<li>Perfect environment for Dokan marketplaces</li>
							</ul>
						</div>

						<div class="hive-hosting-pricing">
							<div class="hive-hosting-price">
								<span class="hive-price-amount">$25</span>
								<span class="hive-price-period">/month</span>
							</div>
							<p>Starting price for small sites</p>
						</div>

						<p class="hive-hosting-cta">
							<a href="https://flywp.com" class="button button-primary" target="_blank">
								Visit FlyWP →
							</a>
						</p>

						<div class="hive-hosting-testimonial">
							<blockquote>
								"FlyWP has significantly improved our site speed and reliability. Their support team is incredible."
							</blockquote>
							<cite>— WordPress Agency Owner</cite>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add FlyWP promotion bar to all admin pages
	 */
	public function add_flywp_promo_bar() {
		?>
		<div class="hive-flywp-promo-bar">
			<div class="hive-promo-bar-content">
				<div class="hive-promo-logo">
					<img src="https://flywp.com/wp-content/uploads/2025/01/Logo-full-main-png.png" alt="FlyWP">
				</div>
				<div class="hive-promo-message">
					<strong>Deploy lightning fast WordPress sites.</strong>
					<p class="hive-promo-details">WordPress Hosting for DigitalOcean, Vultr, Linode, AWS, GCP, Hetzner, Azure and custom servers.</p>
				</div>
				<div class="hive-promo-action">
					<a href="https://flywp.com/?" class="button button-primary" target="_blank">TRY FLYWP TODAY</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add styles for the FlyWP promotion bar
	 */
	public function add_promo_bar_styles() {
		?>
		<style type="text/css">
			/* FlyWP Promo Bar Styles */
			.hive-flywp-promo-bar {
				background: linear-gradient(90deg, #5d6cff 0%, #787cff 100%);
				color: #fff;
				display: block;
				box-sizing: border-box;
				margin: 0;
				padding: 0;
				clear: both;
			}

			/* Remove the gap by adjusting margin and position */
			#wpcontent .hive-flywp-promo-bar {
				margin-left: -20px;
				width: calc(100% - 160px);
				position: fixed;
				z-index: 100;
			}

			#wpcontent {
				padding-top: 0 !important;
			}

			.wrap {
				margin-top: 60px;
			}

			.folded #wpcontent .hive-flywp-promo-bar {
				margin-left: -12px;
				width: calc(100% + 12px);
			}

			.hive-promo-bar-content {
				display: flex;
				align-items: center;
				width: 100%;
				box-sizing: border-box;
				padding: 8px 20px;
			}

			.hive-promo-logo {
				margin-right: 15px;
				flex: 0 0 auto;
				display: flex;
				align-items: center;
			}

			.hive-promo-logo img {
				height: 30px;
				width: auto;
				vertical-align: middle;
				border-radius: 4px;
				background: white;
				padding: 4px;
				display: block;
			}

			.hive-promo-message {
				flex: 1;
				font-size: 14px;
				line-height: 1.4;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}

			.hive-promo-details {
				opacity: 0.9;
				margin: 4px 0 0 0;
				font-size: 11px;
			}

			.hive-promo-action {
				margin-left: 20px;
				flex: 0 0 auto;
			}

			.hive-promo-action .button-primary {
				background: white !important;
				color: #5d6cff !important;
				border-color: white !important;
				font-weight: 600;
				text-transform: uppercase;
				font-size: 12px;
				letter-spacing: 0.5px;
				box-shadow: none;
				height: auto;
				line-height: 26px;
				padding: 0 15px;
				margin: 0;
			}

			/* Mobile adjustments */
			@media screen and (max-width: 782px) {
				#wpcontent .hive-flywp-promo-bar {
					margin-left: -10px;
					width: calc(100% + 10px);
				}

				.hive-promo-details {
					display: none;
				}

				.hive-promo-message {
					font-size: 13px;
				}

				.hive-promo-action .button-primary {
					padding: 0 10px;
					font-size: 11px;
				}
			}

			@media screen and (max-width: 600px) {
				.hive-promo-logo img {
					height: 24px;
				}
			}
		</style>
		<?php
	}

	/**
	 * Add custom CSS for our recommendations page
	 */
	public function add_custom_styles() {
		?>
		<style type="text/css">
			/* Main container */
			.hive-recommendations-wrap {
				margin: 70px 20px 0 2px;
				padding: 0;
			}

			.hive-recommendations-container {
				display: flex;
				gap: 30px;
				margin-top: 20px;
			}

			/* Columns */
			.hive-column {
				box-sizing: border-box;
			}

			.hive-plugins-column {
				flex: 2;
			}

			.hive-hosting-column {
				flex: 1;
			}

			/* Featured Plugin */
			.hive-featured-plugin {
				display: flex;
				background: #fff;
				border-radius: 5px;
				box-shadow: 0 2px 5px rgba(0,0,0,0.1);
				overflow: hidden;
				margin-bottom: 30px;
			}

			.hive-plugin-logo {
				flex: 0 0 180px;
				background: #1a1a1a;
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 30px;
			}

			.hive-plugin-logo img {
				max-width: 100%;
				height: auto;
			}

			.hive-plugin-content {
				flex: 1;
				padding: 30px;
			}

			.hive-plugin-content h3 {
				margin-top: 0;
				font-size: 22px;
				color: #23282d;
			}

			.hive-plugin-description {
				font-size: 16px;
				color: #50575e;
				margin-bottom: 20px;
			}

			.hive-plugin-features {
				list-style-type: disc;
				margin-left: 18px;
				margin-bottom: 20px;
			}

			.hive-plugin-features li {
				margin-bottom: 8px;
				color: #50575e;
			}

			.hive-plugin-cta {
				margin-bottom: 15px;
			}

			.hive-plugin-cta .button-primary {
				padding: 8px 16px;
				font-size: 14px;
				height: auto;
			}

			.hive-plugin-testimonial {
				font-size: 13px;
				color: #777;
				font-style: italic;
			}

			/* Other Plugins Cards */
			.hive-other-plugins {
				display: flex;
				flex-wrap: wrap;
				gap: 20px;
			}

			.hive-plugin-card {
				background: #fff;
				border-radius: 5px;
				box-shadow: 0 2px 5px rgba(0,0,0,0.1);
				padding: 20px;
				flex: 1 1 calc(50% - 10px);
				min-width: 250px;
				display: flex;
				align-items: center;
			}

			.hive-plugin-card-logo {
				flex: 0 0 60px;
				margin-right: 15px;
			}

			.hive-plugin-card-logo img {
				max-width: 100%;
				width: 80%;
				height: auto;
			}

			.hive-plugin-card-content {
				flex: 1;
			}

			.hive-plugin-card-content h4 {
				margin-top: 0;
				margin-bottom: 10px;
				font-size: 16px;
			}

			.hive-plugin-card-content p {
				margin-bottom: 15px;
				font-size: 13px;
				color: #50575e;
			}

			/* Hosting Card */
			.hive-hosting-card {
				background: #fff;
				border-radius: 5px;
				box-shadow: 0 2px 5px rgba(0,0,0,0.1);
				padding: 30px;
				text-align: center;
			}

			.hive-hosting-logo {
				max-width: 180px;
				margin: 0 auto 20px;
			}

			.hive-hosting-logo img {
				max-width: 100%;
				height: auto;
			}

			.hive-hosting-card h3 {
				margin-top: 0;
				font-size: 22px;
				color: #23282d;
				margin-bottom: 5px;
			}

			.hive-hosting-tagline {
				font-size: 16px;
				color: #787cff;
				margin-bottom: 25px;
			}

			.hive-hosting-features {
				text-align: left;
				margin-bottom: 25px;
				padding: 15px;
				background: #f9f9f9;
				border-radius: 5px;
			}

			.hive-hosting-features h4 {
				margin-top: 0;
				margin-bottom: 10px;
				color: #23282d;
			}

			.hive-hosting-features ul {
				list-style-type: disc;
				margin-left: 18px;
			}

			.hive-hosting-features li {
				margin-bottom: 8px;
				color: #50575e;
				font-size: 14px;
			}

			.hive-hosting-pricing {
				margin-bottom: 25px;
			}

			.hive-hosting-price {
				margin-bottom: 10px;
			}

			.hive-price-amount {
				font-size: 32px;
				font-weight: bold;
				color: #23282d;
			}

			.hive-price-period {
				font-size: 16px;
				color: #50575e;
			}

			.hive-hosting-pricing p {
				font-size: 14px;
				color: #777;
				margin: 0;
			}

			.hive-hosting-cta {
				margin-bottom: 25px;
			}

			.hive-hosting-cta .button-primary {
				padding: 8px 16px;
				font-size: 14px;
				height: auto;
				background: #787cff;
				border-color: #6267ff;
			}

			.hive-hosting-cta .button-primary:hover,
			.hive-hosting-cta .button-primary:focus {
				background: #6267ff;
				border-color: #5257ff;
			}

			.hive-hosting-testimonial {
				background: #f9f9f9;
				padding: 15px;
				border-radius: 5px;
				text-align: left;
			}

			.hive-hosting-testimonial blockquote {
				margin: 0 0 10px;
				font-style: italic;
				color: #50575e;
				padding-left: 10px;
				border-left: 3px solid #e5e5e5;
			}

			.hive-hosting-testimonial cite {
				display: block;
				text-align: right;
				font-size: 13px;
				color: #777;
			}

			/* Button Styles */
			.hive-plugin-cta .button-primary {
				background: #e23b7b;
				border-color: #e23b7b;
			}

			.hive-plugin-cta .button-primary:hover,
			.hive-plugin-cta .button-primary:focus {
				background: #df2069;
				border-color: #df2069;
			}

			/* Section headings */
			.hive-recommendations-wrap h1 {
				font-size: 24px;
				margin: 0 0 20px;
				padding: 0 0 10px;
				border-bottom: 1px solid #eee;
			}

			.hive-column h2 {
				font-size: 18px;
				margin: 0 0 15px;
				color: #23282d;
			}

			/* Responsive adjustments */
			@media screen and (max-width: 1200px) {
				.hive-recommendations-container {
					flex-direction: column;
				}

				.hive-featured-plugin {
					flex-direction: column;
				}

				.hive-plugin-logo {
					flex: 0 0 auto;
					padding: 20px;
				}

				.hive-plugin-card {
					flex: 1 1 100%;
				}

				.hive-hosting-column {
					margin-top: 30px;
				}
			}

			/* Hide WP notices that might interfere with our dashboard */
			.notice:not(.hive-notice) {
				display: none !important;
			}
		</style>
		<?php
	}
}

// Initialize the plugin
new WP_Hive_Playground();
