<?php
/**
 * All mail functions will go in here
 *
 * @link       https://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Mailtpl
 * @subpackage Mailtpl/includes
 * @author     wpexperts
 */

if ( ! class_exists( 'Mailtpl_Mailer' ) ) {
	/**
	 * Class Mailtpl_Mailer.
	 */
	class Mailtpl_Mailer {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * dynamic property
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      mixed  
		 */
		private $opts;


		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name       The name of this plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;
			$this->opts        = Mailtpl::opts();
		}

		/**
		 * Send html emails instead of text plain
		 *
		 * @param string $type Type.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function set_content_type( $type ) {
			if ( 'text/html' !== $type ) {
				// If not html, work with content and filter it.
				add_filter( 'mailtpl_email_content', 'wp_kses_post', 50 );
				$this->add_content_filters();
			}
			return 'text/html';
		}

		/**
		 * Send Email to All the SMTP Plugins
		 *
		 * @param array $args Expected args.
		 *
		 * @since 1.0.0
		 */
		public function send_email( $args ) {

			do_action( 'mailtpl_send_email', $args, $this );
			$temp_message    = $this->add_template( apply_filters( 'mailtpl_email_content', $args['message'] ) );
			$user_email      = isset( $args['to'] ) ? $args['to'] : get_option( 'admin_email' );
			$args['message'] = $this->replace_placeholders( $temp_message, $user_email );

			return $args;
		}

		/**
		 * Add content filters
		 */
		private function add_content_filters() {
			add_filter( 'mailtpl_email_content', 'wptexturize' );
			add_filter( 'mailtpl_email_content', 'convert_chars' );
			add_filter( 'mailtpl_email_content', 'wpautop' );
		}

		/**
		 * Send a test email to admin email
		 *
		 * @since 1.0.0
		 */
		public function send_test_email() {
			if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'mailtpl-send-test-mail' ) ) {
				if ( isset( $_POST['email_type'] ) ) {
					if ( 'wordpress_standard_email' === sanitize_text_field( wp_unslash( $_POST['email_type'] ) ) ) {
						ob_start();
						include_once apply_filters( 'mailtpl_customizer_template_message', MAILTPL_PLUGIN_DIR . 'templates/default/includes/email-body.php' );
						$message = ob_get_contents();
						ob_end_clean();
						$subject      = __( 'WP Email Templates', 'email-templates' );
						$email_sanded = wp_mail(
							get_bloginfo( 'admin_email' ),
							$subject,
							$message
						);

						if ( $email_sanded ) {
							wp_send_json_success(
								array(
									'email_sanded' => 'true',
									'message'      => __( 'Email sent successfully', 'email-templates' ),
								),
								200
							);
						}
					}

					$email_type    = sanitize_text_field( wp_unslash( $_POST['email_type'] ) );
					$preview_order = isset( $_POST['preview_order'] ) ? sanitize_text_field( wp_unslash( $_POST['preview_order'] ) ) : '';

					if ( class_exists( 'Mailtpl_Woomail_Preview' ) ) {
						$content = Mailtpl_Woomail_Preview::get_preview_email( true, get_bloginfo( 'admin_email' ), $email_type, $preview_order );
					
						if ( $content ) {
							wp_send_json_success(
								array(
									'email_sanded' => 'true',
									'message'      => __( 'Email sent successfully', 'email-templates' ),
								),
								200
							);
						}
					} else {
						// Optional: respond with failure if class doesn't exist
						wp_send_json_error(
							array(
								'email_sanded' => 'false',
								'message'      => __( 'Mailtpl_Woomail_Preview class not found. Email not sent.', 'email-templates' ),
							),
							400
						);
					}
					
				}
			}
		}

		/**
		 * Add template to plain mail
		 *
		 * @param string $email Mail to be send.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		private function add_template( $email ) {
			do_action( 'mailtpl_add_template', $email, $this );

			$template_file = apply_filters( 'mailtpl_customizer_template', MAILTPL_PLUGIN_DIR . "/admin/templates/default.php" );
			ob_start();
			include $template_file;
			$template = ob_get_contents();
			ob_end_clean();
			return apply_filters( 'mailtpl_return_template', str_replace( '%%MAILCONTENT%%', $email, $template ) );
		}

		/**
		 * Replace placeholders
		 *
		 * @param string $email Mail to be send.
		 * @param string $user_email Get destination email.
		 * Passed to the filters in case users needs something.
		 *
		 * @return string
		 */
		private function replace_placeholders( $email, $user_email = '' ) {

			$to_replace = apply_filters(
				'emailtpl_placeholders',
				array(
					'##SITEURL###'         => get_option( 'siteurl' ),
					'%%BLOG_URL%%'         => get_option( 'siteurl' ),
					'%%HOME_URL%%'         => get_option( 'home' ),
					'%%BLOG_NAME%%'        => get_option( 'blogname' ),
					'%%BLOG_DESCRIPTION%%' => get_option( 'blogdescription' ),
					'%%ADMIN_EMAIL%%'      => get_option( 'admin_email' ),
					'%%DATE%%'             => date_i18n( get_option( 'date_format' ) ),
					'%%TIME%%'             => date_i18n( get_option( 'time_format' ) ),
					'%%USER_EMAIL%%'       => $user_email,
				),
				$user_email
			);

			foreach ( $to_replace as $placeholder => $var ) {
				if ( is_array( $var ) ) {
					do {
						$var = reset( $var );
					} while ( is_array( $var ) );
				}
				$email = str_replace( $placeholder, $var, $email );
			}

			return $email;
		}

		/**
		 * Sets email's From email
		 *
		 * @param string $email Email.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function set_from_email( $email ) {
			if ( empty( $this->opts['from_email'] ) ) {
				return $email;
			}
			return $this->opts['from_email'];
		}

		/**
		 * Sets email's From name
		 *
		 * @param string $name Name.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function set_from_name( $name ) {
			if ( empty( $this->opts['from_name'] ) ) {
				return $name;
			}
			return $this->opts['from_name'];
		}

		/**
		 * Clear retrieve password message for wrong html tag
		 *
		 * @param string $message Message.
		 *
		 * @return mixed
		 */
		public function clean_retrieve_password( $message ) {
			return make_clickable( preg_replace( '@<(http[^> ]+)>@', '$1', $message ) );
		}

		/**
		 * This way we fully removed html added by gravity forms. Only possible on versions  2.2.1.5 or above
		 *
		 * @since 1.2.2
		 * @return string
		 */
		public function gform_template() {
			return '{message}';
		}
	}
}
