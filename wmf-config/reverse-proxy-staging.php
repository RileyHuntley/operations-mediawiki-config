<?php
# WARNING: This file is publicly viewable on the web. Do not put private data here.

# our text CDN in beta labs gets forwarded requests
# from the ssl terminator, to 127.0.0.1, so adding that
# address to the NoPurge list so that XFF headers for it
# will be stripped; purge requests should get to it
# on the address in the CdnServers list

# The beta cluster does not have multicast, hence we route the purge
# requests directly to the varnish instances.  They have varnishhtcpd listening
# on UDP port 4827.

$wgCdnServersNoPurge = [ '127.0.0.1',
	'172.16.4.21',  # deployment-cache-text04
	'172.16.5.14',  # deployment-cache-upload04
];

$wgHTCPRouting = [
	'|^https?://upload\.beta\.wmflabs\.org|' => [
		'host' => '172.16.5.14',  # deployment-cache-upload04
		'port' => 4827,
	],

	# Fallback  (text)
	'' => [
		[
			'host' => '172.16.4.21',  # deployment-cache-text04
			'port' => 4827,
		],
	],
];
