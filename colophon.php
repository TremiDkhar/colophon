<?php

/**
 * A colophon-generating method for WordPress Special Projects Sites.
 *
 *     team51_credits( 'separator= | ' );
 *
 * @param array $args {
 *     Optional. An array of arguments.
 *     
 *     @type string $separator The separator to inject between links.
 *                             Default ' '
 *     @type string $wpcom     The link text to use for WordPress.com.
 *                             Default 'Proudly powered by WordPress.'
 *     @type string $pressable The link text to use for Pressable.
 *                             Default 'Hosted by Pressable.'
 * }
 */
function team51_credits( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'separator'      => ' ',
			/* translators: %s: WordPress. */
			'wpcom'          => sprintf( __( 'Proudly powered by %s.', 'team51' ), 'WordPress' ),
			/* translators: %s: Pressable. */
			'pressable'      => sprintf( __( 'Hosted by %s.', 'team51' ), 'Pressable' ),
		)
	);

	$credit_links = array();

	if ( $args['wpcom'] ) {
		$partner_domain = wp_parse_url( get_site_url(), PHP_URL_HOST );
		$wpcom_link = apply_filters(
			'team51_credits_link_wpcom',
			add_query_arg(
				array(
					'partner_domain' => $partner_domain,
					'utm_source'     => 'Automattic',
					'utm_medium'     => 'colophon',
					'utm_campaign'   => 'Concierge Referral',
					'utm_term'       => $partner_domain,
				),
				'https://wordpress.com/wp/'
			)
		);
		$credit_links['wpcom'] = sprintf(
			'<a href="%1$s" class="imprint">%2$s</a>',
			esc_url( $wpcom_link ),
			esc_html( $args['wpcom'] )
		);
	}

	if ( $args['pressable'] ) {
		$pressable_link = apply_filters(
			'team51_credits_link_pressable',
			add_query_arg(
				array(
					'utm_source'   => 'Automattic',
					'utm_medium'   => 'rpc',
					'utm_campaign' => 'Concierge Referral',
					'utm_term'     => 'concierge',
				),
				'https://pressable.com/'
			)
		);
		$credit_links['pressable'] = sprintf(
			'<a href="%1$s" class="imprint">%2$s</a>',
			esc_url( $pressable_link ),
			esc_html( $args['pressable'] )
		);
	}

	/**
	 * Filter the output links.
	 *
	 * This will enable folks to add additional links, remove links, or
	 * reroute links to internationalized versions if needed.
	 *
	 * @param array $credit_links The associative array of credit links.
	 * @param array $args         The parsed arguments used by `team51_credits()`.
	 */
	$credit_links = apply_filters( 'team51_credit_links', $credit_links, $args );

	echo implode(
		esc_html( $args['separator'] ),
		$credit_links
	);
}
add_action( 'team51_credits', 'team51_credits', 10, 1 );

// add register_shortcode
