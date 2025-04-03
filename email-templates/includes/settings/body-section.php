<?php
/**
 * Body settings section
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

do_action( 'mailtpl_sections_body_before_content', $wp_customize );

// background color.
$wp_customize->add_setting(
	'mailtpl_opts[email_body_bg]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['email_body_bg'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_email_body_bg',
		array(
			'label'       => __( 'Background Color', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'settings'    => 'mailtpl_opts[email_body_bg]',
			'description' => __( 'Choose email body background color', 'email-templates' ),
		)
	)
);
// text size.
$wp_customize->add_setting(
	'mailtpl_opts[body_text_size]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['body_text_size'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_text' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_body_text_size',
		array(
			'label'       => __( 'Text Size', 'email-templates' ),
			'type'        => 'mailtpl-range-control',
			'section'     => 'section_mailtpl_body',
			'settings'    => 'mailtpl_opts[body_text_size]',
			'description' => __( 'Slide to change text size', 'email-templates' ),
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	)
);

// text color.
$wp_customize->add_setting(
	'mailtpl_opts[body_text_color]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['body_text_color'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_body_text_color',
		array(
			'label'       => __( 'Text Color', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'settings'    => 'mailtpl_opts[body_text_color]',
			'description' => __( 'Choose body text color', 'email-templates' ),
		)
	)
);

// Links color.
$wp_customize->add_setting(
	'mailtpl_opts[body_href_color]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['body_href_color'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_body_href_color',
		array(
			'label'       => __( 'Links Color', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'settings'    => 'mailtpl_opts[body_href_color]',
			'description' => __( 'Choose links color', 'email-templates' ),
		)
	)
);

// Padding top.
$wp_customize->add_setting(
	'mailtpl_opts[body_padding_top]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => 20,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[body_padding_top]',
		array(
			'label'       => esc_attr__( 'Padding Top', 'email-templates' ),
			'description' => esc_html__( 'Adjust your top padding', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// Padding bottom.
$wp_customize->add_setting(
	'mailtpl_opts[body_padding_bottom]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => 20,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[body_padding_bottom]',
		array(
			'label'       => esc_attr__( 'Padding Bottom', 'email-templates' ),
			'description' => esc_html__( 'Adjust your bottom padding', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// padding Left/Right.
$wp_customize->add_setting(
	'mailtpl_opts[body_padding_left_right]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => 15,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[body_padding_left_right]',
		array(
			'label'       => esc_attr__( 'Padding Left/Right', 'email-templates' ),
			'description' => esc_html__( 'Adjust your Left/Right padding', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// Body font weight.
$wp_customize->add_setting(
	'mailtpl_opts[body_font_weight]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 100,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[body_font_weight]',
		array(
			'label'       => esc_attr__( 'Font Weight', 'email-templates' ),
			'description' => esc_html__( 'Select font weight', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'type'        => 'mailtpl-range-control',
			'input_attrs' => array(
				'min'  => 100,
				'max'  => 900,
				'step' => 100,
			),
		)
	)
);

// Font style.
$wp_customize->add_setting(
	'mailtpl_opts[body_font_family]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'arial',
	),
);
$wp_customize->add_control(
	'mailtpl_opts[body_font_family]',
	array(
		'label'       => esc_attr__( 'Font Family', 'email-templates' ),
		'description' => esc_html__( 'Select font family for your body text', 'email-templates' ),
		'section'     => 'section_mailtpl_body',
		'type'        => 'select',
		'choices'     => mailtpl_get_all_fonts(),
	)
);

do_action( 'mailtpl_sections_body_after_content', $wp_customize );
