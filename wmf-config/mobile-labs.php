<?php
# WARNING: This file is publicly viewable on the web. Do not put private data here.

// T114552 - important that it's different to avoid confusion of login screen with production.
$wgMobileFrontendLogo = $wmgSiteLogo1x;

// T49647
$wgHooks['EnterMobileMode'][] = function () {
	global $wgCentralAuthCookieDomain, $wgHooks;
	$domainRegexp = '/(?<!\.m)\.wikimedia\.beta\.wmflabs\.org$/';
	$mobileDomain = '.m.wikimedia.beta.wmflabs.org';

	if ( preg_match( $domainRegexp, $wgCentralAuthCookieDomain ) ) {
		$wgCentralAuthCookieDomain = preg_replace( $domainRegexp, $mobileDomain, $wgCentralAuthCookieDomain );
	}
	$wgHooks['WebResponseSetCookie'][] = function ( &$name, &$value, &$expire, &$options ) use ( $domainRegexp, $mobileDomain ) {
		if ( isset( $options['domain'] ) && preg_match( $domainRegexp, $options['domain'] ) ) {
			$options['domain'] = preg_replace( $domainRegexp, $mobileDomain, $options['domain'] );
		}
	};
};
