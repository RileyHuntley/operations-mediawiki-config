<?php
# WARNING: This file is publicly viewable on the web. Do not put private data here.

# This file holds per-wiki overrides specific to Beta Cluster.
# For overrides common to all wikis, see CommonSettings-labs.php.
#
# This for BETA and MUST NOT be loaded for production.
#
# Usage:
# - Prefix a setting key with '-' to override all values from
#   production InitialiseSettings.php.
# - Please wrap your code in functions to avoid tainting the global scope.
#
# Effective load order:
# - multiversion
# - mediawiki/DefaultSettings.php
# - wmf-config/*Services.php
# - wmf-config/InitialiseSettings.php
# - wmf-config/InitialiseSettings-labs.php [THIS FILE]
# - wmf-config/CommonSettings.php
# - wmf-config/CommonSettings-labs.php
#
# Included from: wmf-config/CommonSettings.php.
#

/**
 * Override site settings as needed for Beta Cluster.
 *
 * @param array[] $settings wgConf-style settings array
 * @return array
 */
function wmfApplyLabsOverrideSettings( array $settings ) : array {
	// Override (or add) settings that we need within the labs environment,
	// but not in production.
	foreach ( wmfGetLabsOverrideSettings() as $key => $value ) {
		if ( substr( $key, 0, 1 ) == '-' ) {
			// Settings prefixed with - are completely overriden
			$settings[substr( $key, 1 )] = $value;
		} elseif ( isset( $settings[$key] ) ) {
			$settings[$key] = array_merge( $settings[$key], $value );
		} else {
			$settings[$key] = $value;
		}
	}

	return $settings;
}

/**
 * Get overrides for Beta Cluster settings. This is used by wmfLabsOverride().
 *
 * Keys that start with a hyphen will completely override the prodution settings
 * from InitializeSettings.php.
 *
 * Keys that don't start with a hyphen will have their settings merged with
 * the production settings.
 *
 * @return array
 */
function wmfGetLabsOverrideSettings() {
	return [

		'wgParserCacheType' => [
			'default' => CACHE_MEMCACHED,
		],

		'wgLanguageCode' => [
			'deploymentwiki' => 'en',
		],

		'wgSitename' => [
			'deploymentwiki' => 'Deployment',
			'wikivoyage'     => 'Wikivoyage',
		],

		'-wgServer' => [
			'wiktionary'	=> 'https://$lang.wiktionary.beta.wmflabs.org',
			'wikipedia'     => 'https://$lang.wikipedia.beta.wmflabs.org',
			'wikiversity'	=> 'https://$lang.wikiversity.beta.wmflabs.org',
			'wikisource'	=> 'https://$lang.wikisource.beta.wmflabs.org',
			'wikiquote'	=> 'https://$lang.wikiquote.beta.wmflabs.org',
			'wikinews'	=> 'https://$lang.wikinews.beta.wmflabs.org',
			'wikibooks'     => 'https://$lang.wikibooks.beta.wmflabs.org',
			'wikivoyage'    => 'https://$lang.wikivoyage.beta.wmflabs.org',

			'commonswiki'   => 'https://commons.wikimedia.beta.wmflabs.org',
			'deploymentwiki'      => 'https://deployment.wikimedia.beta.wmflabs.org',
			'loginwiki'     => 'https://login.wikimedia.beta.wmflabs.org',
			'metawiki'      => 'https://meta.wikimedia.beta.wmflabs.org',
			'testwiki'      => 'https://test.wikimedia.beta.wmflabs.org',
			'wikidatawiki'  => 'https://wikidata.beta.wmflabs.org',
			'en_rtlwiki' => 'https://en-rtl.wikipedia.beta.wmflabs.org',
		],

		'-wgCanonicalServer' => [
			'wikipedia'     => 'https://$lang.wikipedia.beta.wmflabs.org',
			'wikibooks'     => 'https://$lang.wikibooks.beta.wmflabs.org',
			'wikiquote'	=> 'https://$lang.wikiquote.beta.wmflabs.org',
			'wikinews'	=> 'https://$lang.wikinews.beta.wmflabs.org',
			'wikisource'	=> 'https://$lang.wikisource.beta.wmflabs.org',
			'wikiversity'     => 'https://$lang.wikiversity.beta.wmflabs.org',
			'wiktionary'     => 'https://$lang.wiktionary.beta.wmflabs.org',
			'wikivoyage'    => 'https://$lang.wikivoyage.beta.wmflabs.org',

			'metawiki'      => 'https://meta.wikimedia.beta.wmflabs.org',
			'commonswiki'	=> 'https://commons.wikimedia.beta.wmflabs.org',
			'deploymentwiki'      => 'https://deployment.wikimedia.beta.wmflabs.org',
			'loginwiki'     => 'https://login.wikimedia.beta.wmflabs.org',
			'testwiki'      => 'https://test.wikimedia.beta.wmflabs.org',
			'wikidatawiki'  => 'https://wikidata.beta.wmflabs.org',
			'en_rtlwiki' => 'https://en-rtl.wikipedia.beta.wmflabs.org',
		],

		'-wgUploadPath' => [
			'default' => 'https://upload.beta.wmflabs.org/$site/$lang',
			'private' => '/w/img_auth.php',
		// 'wikimania2005wiki' => 'http://upload..org/wikipedia/wikimania', // back compat
			'commonswiki' => 'https://upload.beta.wmflabs.org/wikipedia/commons',
			'metawiki' => 'https://upload.beta.wmflabs.org/wikipedia/meta',
			'testwiki' => 'https://upload.beta.wmflabs.org/wikipedia/test',
		],

		'-wgThumbnailBuckets' => [
			'default' => [ 256, 512, 1024, 2048, 4096 ],
		],

		'-wgThumbnailMinimumBucketDistance' => [
			'default' => 32,
		],

		'-wmgDefaultMonologHandler' => [
			'default' => 'wgDebugLogFile',
		],

		// Use eventgate-main as default EventService in beta
		// TODO: this will be replaced by wgEventStreams once
		// EventStreamConfig extension is deployed. See:
		// https://phabricator.wikimedia.org/T233634 and
		// https://phabricator.wikimedia.org/T229863
		'wgEventServiceStreamConfig' => [
			'default' => [
				'default' => [
					'EventServiceName' => 'eventgate-main',
				],
			],
		],
		// Event stream configuration is a list of stream configurations.
		// Each item in the list must have a 'stream' setting of either the specific
		// stream name or a regex patterns to matching stream names.
		// Each item must also at minimum include the schema_title of the
		// JSONSchema that events in the stream must conform to.
		// This is used by the EventStreamConfig extension
		// to allow for remote configuration of streams.  It
		// is used by the EventLogging extension to vary e.g.
		// sample rate that browsers use when producing events,
		// as well as the eventgate-analytics-external service
		// to dynamically add event streams that it should accept
		// and validate.
		// See https://gerrit.wikimedia.org/r/plugins/gitiles/mediawiki/extensions/EventStreamConfig/#mediawiki-config
		'wgEventStreams' => [
			'default' => [
				// test.event stream; used to test that event stream
				// services are working properly.
				[
					'stream' => 'test.event',
					// https://schema.wikimedia.org/repositories/primary/jsonschema/test/event/current.yaml
					'schema_title' => 'test/event'
				],
			],
		],

		// Additional log channels for beta cluster
		'-wmgMonologChannels' => [
			'default' => [
				'api-request' => [
					'udp2log' => false,
					'logstash' => false,
					'eventbus' => 'debug',
					'buffer' => true,
				],
				'authentication' => 'info',
				'CentralAuthVerbose' => 'debug',
				'CirrusSearch' => 'info',
				'cirrussearch-request' => [
					'udp2log' => false,
					'logstash' => false,
					'eventbus' => 'debug',
					'buffer' => true,
				],
				'csp' => 'info',
				'csp-report-only' => 'info',
				'dnsblacklist' => 'debug',
				'EventBus' => 'debug',
				'JobExecutor' => [ 'logstash' => 'debug' ],
				'MessageCache' => 'debug',
				'runJobs' => [ 'logstash' => 'info' ],
				'squid' => 'debug',
			],
		],

		'wmgApplyGlobalBlocks' => [ // T123936
			'default' => true,
			'metawiki' => true,
			'deploymentwiki' => false
		],

		'wmgSiteLogo1x' => [
			'default' => '/static/images/project-logos/betawiki.png',
			'commonswiki' => '/static/images/project-logos/betacommons.png',
			'metawiki' => '/static/images/project-logos/betametawiki.png', // T125942
			'wikibooks' => '/static/images/project-logos/betacommons.png',
			'wikidata' => '/static/images/project-logos/betawikidata.png',
			'wikinews' => '/static/images/project-logos/betacommons.png',
			'wikiquote' => '/static/images/project-logos/betacommons.png',
			'wikisource' => '/static/images/project-logos/betacommons.png',
			'wikiversity' => '/static/images/project-logos/betawikiversity.png',
			'wikivoyage' => '/static/images/project-logos/betacommons.png',
			'wiktionary' => '/static/images/project-logos/betacommons.png',
		],

		'wgFavicon' => [
			'dewiki' => 'https://upload.wikimedia.org/wikipedia/commons/1/14/Favicon-beta-wikipedia.png',
		],

		'-wmgEnableCaptcha' => [
			'default' => true,
		],

		'-wmgEchoCluster' => [
			'default' => false,
		],

		'wgEchoPollForUpdates' => [
			'enwiki' => 60,
			'cawiki' => 60,
		],

		# FIXME: make that settings to be applied
		'-wgShowExceptionDetails' => [
			'default' => true,
		],

		'-wgUseContributionTracking' => [
			'default' => false,
		],

		# To help fight spam, makes rules maintained on deploymentwiki
		# to be available on all beta wikis.
		'-wmgAbuseFilterCentralDB' => [
			'default' => 'deploymentwiki',
		],
		'-wmgUseGlobalAbuseFilters' => [
			'default' => true,
		],

		// T39852
		'wmgUseWikimediaShopLink' => [
			'default'    => false,
			'enwiki'     => true,
			'simplewiki' => true,
		],

		'-wgKartographerEnableMapFrame' => [
			'default'	=> true,
		],
		'wgWMECitationUsagePopulationSize' => [
			'enwiki' => 1  // 100% — T213969
		],
		'wgWMECitationUsagePageLoadPopulationSize' => [
			'enwiki' => 3  // 33.3% — T213969
		],

		'wgWMEInukaPageViewEnabled' => [
			'default' => true
		],
		'wgWMEInukaPageViewCookiesDomain' => [
			'default' => 'wmflabs.org'
		],
		// Enable Mediawiki client side (browser) Javascript error logging.
		// This is the publicly accessible endpoint for eventgate-logging-external.
		'wgWMEClientErrorIntakeURL' => [
			'default' => 'https://eventgate-logging.wmflabs.org/v1/events?hasty=true'
		],
		'wgMFAdvancedMobileContributions' => [
			'default' => true,
		],

		'wgMFAmcOutreach' => [
			'default' => true
		],

		'wgMFAmcOutreachMinEditCount' => [
			'default' => 0
		],

		'wgMobileUrlTemplate' => [
			'default' => '%h0.m.%h1.%h2.%h3.%h4',
			'wikidatawiki' => 'm.%h0.%h1.%h2.%h3', // T87440
		],

		'wgMinervaShowShareButton' => [
			'default' => [
				'base' => false,
				'beta' => true
			]
		],
		'wgMFPhotoUploadEndpoint' => [
			'default' => 'https://commons.wikimedia.beta.wmflabs.org/w/api.php',
		],
		'wgMFSpecialCaseMainPage' => [
			// We don't want to support this
			// See https://www.mediawiki.org/wiki/Recommendations_for_mobile_friendly_articles_on_Wikimedia_wikis#Make_sure_your_main_page_is_mobile_friendly
			'default' => false,
		],
		'wgMFUseDesktopSpecialWatchlistPage' => [
			'default' => [
				'base' => false,
				'beta' => false,
				'amc' => true,
			]
		],
		'wgMinervaCountErrors' => [
			'default' => true
		],
		# Do not run any A/B tests on beta cluster (T206179)
		'-wgMinervaABSamplingRate' => [
			'default' => 0,
		],

		//
		// Vector
		//
		'wgVectorShowSkinPreferences' => [
			'default' => true,
		],
		// Skin versions are strings not numbers. See skins/Vector/skin.json.
		'wgVectorDefaultSkinVersion' => [
			'default' => '2', // Latest Vector
		],
		'wgVectorDefaultSkinVersionForExistingAccounts' => [
			'default' => '2', // Latest Vector
		],
		'wgVectorDefaultSkinVersionForNewAccounts' => [
			'default' => '2', // Latest Vector
		],

		'wmgULSPosition' => [
			# Beta-specific
			'deploymentwiki' => 'personal',
		],

		'wmgCommonsMetadataForceRecalculate' => [
			'default' => true,
		],

		///
		/// ----------- BetaFeatures start ----------
		///

		// Enable all Beta Features in Beta Labs, even if not in production whitelist
		'wgBetaFeaturesWhitelist' => [
			'default' => false,
		],

		'wgVisualEditorRebaserURL' => [
			'default' => false,
			'deploymentwiki' => 'https://visualeditor-realtime.wmflabs.org/',
		],

		///
		/// ------------ BetaFeatures end -----------
		///

		// Whether to enable true section editing. false, true, 'mobile', or 'mobile-ab'
		'wmgVisualEditorEnableVisualSectionEditing' => [
			'default' => 'mobile',
		],

		'wmgUseRSSExtension' => [
			'dewiki' => true,
		],
		'wmgRSSUrlWhitelist' => [
			'dewiki' => [ 'http://de.planet.wikimedia.org/atom.xml' ],
		],

		'wmgContentTranslationCluster' => [
			'default' => false,
		],
		'wmgContentTranslationCampaigns' => [
			'default' => [ 'newarticle' ],
		],

		// Whether Compact Links is enabled for new accounts *by default*
		'wmgULSCompactLinksForNewAccounts' => [
			'default' => true,
		],

		// Whether Compact Links is enabled for anonymous users *by default*
		'wmgULSCompactLinksEnableAnon' => [
			'default' => true,
		],

		'wgSearchSuggestCacheExpiry' => [
			'default' => 300,
		],

		'-wmgUseFlow' => [
			'default' => false,
			'flow-labs' => true,
		],
		# No separate Flow DB or cluster (yet) for labs.
		'-wmgFlowDefaultWikiDb' => [
			'default' => false,
		],
		'-wmgFlowCluster' => [
			'default' => false,
		],
		'wmgFlowEnableOptInBetaFeature' => [
			'enwiki' => true,
			'hewiki' => true,
		],

		'wgWPBBannerProperty' => [
			'default' => 'P751',
		],

		'wmgUseRelatedArticles' => [
			'default' => true,
		],

		'wmgExtraLanguageNames' => [
			'default' => [ 'en-rtl' => 'English (rtl)' ],
			'commonswiki' => [
				'smn' => 'anarâškielâ',	  // T222309
				'sms' => 'sääʹmǩiõll',	  // T222309
			],
		],

		'wmgUseQuickSurveys' => [
			'hewiki' => true, // T225819
		],

		'wgQuickSurveysConfig' => [
			'default' => [
				[
					'name' => 'drink-survey',
					'type' => 'internal',
					'question' => 'anne-survey-question',
					'answers' => [
						'anne-survey-answer-one',
						'anne-survey-answer-two',
						'anne-survey-answer-three',
						'anne-survey-answer-four'
					],
					'schema' => 'QuickSurveysResponses',
					'enabled' => true,
					'coverage' => 0,
					'description' => 'anne-survey-description',
					'platforms' => [
						'desktop' => [ 'stable' ],
						'mobile' => [ 'stable', 'beta' ],
					],
				],
				[
					'name' => 'internal example survey',
					'type' => 'internal',
					'question' => 'ext-quicksurveys-example-internal-survey-question',
					'answers' => [
						'ext-quicksurveys-example-internal-survey-answer-positive',
						'ext-quicksurveys-example-internal-survey-answer-neutral',
						'ext-quicksurveys-example-internal-survey-answer-negative',
					],
					'schema' => 'QuickSurveysResponses',
					'enabled' => true,
					'coverage' => 0,
					'description' => 'ext-quicksurveys-example-internal-survey-description',
					'platforms' => [
						'desktop' => [ 'stable' ],
						'mobile' => [ 'stable', 'beta' ],
					],
				],
				[
					'name' => 'external example survey',
					'type' => 'external',
					'question' => 'ext-quicksurveys-example-external-survey-question',
					'description' => 'ext-quicksurveys-example-external-survey-description',
					'link' => 'ext-quicksurveys-example-external-survey-link',
					'privacyPolicy' => 'ext-quicksurveys-example-external-survey-privacy-policy',
					'coverage' => 0,
					'enabled' => true,
					'platforms' => [
						'desktop' => [ 'stable' ],
						'mobile' => [ 'stable', 'beta' ],
					],
				],
			],
			'enwiki' => [
				// T209882
				[
					'enabled' => true,
					'type' => 'external',
					'name' => 'Reader-trust-survey-en-v1',
					'description' => 'Reader-trust-1-description',
					'link' => 'Reader-trust-1-link',
					'instanceTokenParameterName' => 'token',
					'question' => 'Reader-trust-1-message',
					'privacyPolicy' => 'Reader-trust-1-privacy',
					'coverage' => 1,  // 1 out of 1
					'platforms' => [
						'desktop' => [ 'stable' ],
						'mobile' => [ 'stable' ],
					],
				],
				// T217171
				[
					'enabled' => true,
					'name' => 'reader-demographics-en-pilot',
					'type' => 'external',
					'description' => 'Reader-demographics-1-description',
					'link' => 'Reader-demographics-1-link',
					'question' => 'Reader-demographics-1-message',
					'privacyPolicy' => 'Reader-demographics-1-privacy',
					'coverage' => 1, // 1 out of 1
					'instanceTokenParameterName' => 'entry.1791119923',
					'platforms' => [
						'desktop' => [ 'stable' ],
						'mobile' => [ 'stable' ]
					],
				],
				// T225819
				[
					'enabled' => true,
					"name" => "reader-demographics-en",
					"type" => "external",
					"description" => "Reader-demographics-1-description",
					"link" => "Reader-demographics-1-link",
					"question" => "Reader-demographics-1-message",
					"privacyPolicy" => "Reader-demographics-1-privacy",
					"coverage" => 1,
					"instanceTokenParameterName" => "entry.1791119923",
					"platforms" => [
						"desktop" => [ "stable" ],
						"mobile" => [ "stable" ]
					],
				],
				// T225042
				[
					"enabled" => true,
					"type" => "internal",
					"name" => "editor-gender-1-en",
					"question" => "Editor-gender-1-message",
					"description" => "Editor-gender-1-description",
					"answers" => [
						"Editor-gender-1-answer-man",
						"Editor-gender-1-answer-woman",
					],
					"freeformTextLabel" => "Editor-gender-1-free-form-text-label",
					"privacyPolicy" => "Editor-gender-1-privacy",
					"coverage" => 1,
					"audience" => [
						"anons" => false,
					],
					"platforms" => [
						"desktop" => [ "stable" ],
						"mobile" => [ "stable" ]
					],
				]
			],
			'hewiki' => [
				// T225819
				[
					'enabled' => true,
					"name" => "reader-demographics-he",
					"type" => "external",
					"description" => "Reader-demographics-1-description",
					"link" => "Reader-demographics-1-link",
					"question" => "Reader-demographics-1-message",
					"privacyPolicy" => "Reader-demographics-1-privacy",
					"coverage" => 1,
					"instanceTokenParameterName" => "entry.1791119923",
					"platforms" => [
						"desktop" => [ "stable" ],
						"mobile" => [ "stable" ]
					],
				]
			]
		],

		'wmgUseSentry' => [
			'default' => true,
		],
		'wgSentryDsn' => [
			'default' => 'https://c357be0613e24340a96aeaa28dde08ad@sentry-beta.wmflabs.org/4',
		],
		'wgSentryEventGateUri' => [
			'default' => 'https://eventgate-logging.wmflabs.org/v1/events',
		],

		'-wgScorePath' => [
			'default' => "//upload.beta.wmflabs.org/score",
		],

		'wgRateLimitsExcludedIPs' => [
			'default' => [
				'198.73.209.0/24', // T87841 Office IP
				'162.222.72.0/21', // T126585 Sauce Labs IP range for browser tests
				'66.85.48.0/21', // also Sauce Labs
				'172.16.0.0/12', // browser tests run by Jenkins instances - T167432
			],
		],

		'wmgUseCapiunto' => [
			'default' => true,
		],

		'wmgUseCheckUser' => [
			'default' => false,
		],

		'wgMediaViewerNetworkPerformanceSamplingFactor' => [
			'default' => 1,
		],

		'wgMediaViewerUseThumbnailGuessing' => [
			'default' => false, // T69651
		],

		'wmgUseArticlePlaceholder' => [
			'default' => false,
			'wikidataclient' => true,
		],

		'wgArticlePlaceholderRepoApiUrl' => [
			'default' => 'https://wikidata.beta.wmflabs.org/w/api.php',
		],

		'wmgUseEntitySchema' => [
			'default' => false,
			'wikidatawiki' => true,
		],

		'wgLexemeLanguageCodePropertyId' => [
			'default' => null,
			'wikidatawiki' => 'P218',
		],

		'-wmgWikibaseSearchIndexProperties' => [
			'default' => []
		],

		'-wmgWikibaseSearchIndexPropertiesExclude' => [
			'default' => []
		],

		'wmgWikibaseTmpItemTermsMigrationStage' => [
			'default' => [
				'max' => MIGRATION_NEW,
			],
			'wikidatawiki' => [
				'max' => MIGRATION_NEW,
			],
		],

		'-wmgWikibaseSearchStatementBoosts' => [
			'default' => []
		],

		'-wmgWikibaseDisabledDataTypes' => [
			'default' => []
		],

		// For better testing of Structured Data on Commons edits
		'wmgEnhancedRecentChanges' => [
			'commonswiki' => true,
		],

		// … and $wgUploadWizardConfig
		'wmgMediaInfoEnableUploadWizardStatements' => [
			'commonswiki' => true,
		],

		// Depicts-related Beta Cluster Commons configuration
		'wgMediaInfoProperties' => [
			'commonswiki' => [
				'depicts' => 'P245962',
			]
		],

		'wgMediaInfoExternalEntitySearchBaseUri' => [
			'commonswiki' => 'https://wikidata.beta.wmflabs.org/w/api.php',
		],

		'wgMediaInfoHelpUrls' => [
			'commonswiki' => [
				'P245962' => 'https://commons.wikimedia.org/wiki/Special:MyLanguage/Commons:Depicts',
			],
		],

		'wgSearchMatchRedirectPreference' => [
			'commonswiki' => true,
		],

		// Test the extension Collection in other languages for book creator,
		// which avoids the bugs related to the PDF generator.
		'wmgUseCollection' => [
			'zhwiki' => true, // T128425
		],

		// Affects URL uploads and chunked uploads (experimental).
		// Limit on other web uploads is enforced by PHP.
		'wgMaxUploadSize' => [
			'default' => 1024 * 1024 * 4096, // 4 GB (i.e. equals 2^31 - 1)
			'ptwiki' => 1024 * 500, // 500 KB - T25186
		],

		'wmgUseNewsletter' => [
			'default' => true,  // T127297
		],

		// Ensure ?action=credits isn't break and allow to work
		// to cache this information. See T130820.
		'wgMaxCredits' => [
			'default' => -1,
		],

		// Test PageAssessments. See T125551.
		'wmgUsePageAssessments' => [
			'default' => true,
		],

		// Test PerformanceInspector
		'wmgUsePerformanceInspector' => [
			'default' => true,
		],

		// The LOWER the value, the MORE requests will be logged.
		// 100 = 1 of every 100 (1%), 1 = 1 of every 1 (100%).
		'wgNavigationTimingSamplingFactor' => [
			// Beta Cluster: 10% of page loads
			'default' => 10,
		],

		'wmgUseFileImporter' => [
			'default' => false,
			'commonswiki' => 'FileImporter-WikimediaSitesTableSite',
			'testwiki' => 'FileImporter-Site-DefaultMediaWiki',
		],

		'wmgUseFileExporter' => [
			'default' => false,
			'metawiki' => true,
			'deploymentwiki' => true,
			'wikipedia' => true,
		],
		'wgMFContentProviderClass' => [
			'default' => 'MobileFrontend\ContentProviders\DefaultContentProvider',
			// T207508
			'enwiki' => 'MobileFrontend\ContentProviders\MwApiContentProvider',
			// For testing T216961
			'dewiki' => 'MobileFrontend\ContentProviders\MwApiContentProvider',
		],
		'wgMFMwApiContentProviderBaseUri' => [
			'default' => 'https://en.wikipedia.org/w/api.php',
			'dewiki' => 'https://de.wikipedia.org/w/api.php',
		],

		// Test numeric sorting. See T8948.
		'wgCategoryCollation' => [
			'default' => 'uppercase',
			'dewiki' => 'uca-de-u-kn', // T128806
			'enwiki' => 'uca-default-u-kn',
			'fawiki' => 'uca-fa',
		],

		// Test Quiz. See T142692
		'wmgUseQuiz' => [
			'cawiki' => true,
		],

		// Test gallery settings; see T141349
		'wmgGalleryOptions' => [
			'default' => [
				'imagesPerRow' => 0,
				'imageWidth' => 120,
				'imageHeight' => 120,
				'captionLength' => true,
				'showBytes' => true,
				'mode' => 'traditional',
			],
			'+enwiki' => [
				'mode' => 'packed', // T141349
			],
		],

		'wgLinterSubmitterWhitelist' => [
			'default' => [
				'172.16.1.115' => true, // deployment-parsoid11.deployment-prep.eqiad.wmflabs
			]
		],
		'wgLinterStatsdSampleFactor' => [
			'default' => 10,
		],

		// T152115
		'wgPageImagesLeadSectionOnly' => [
			'default' => true,
		],

		// Probably no point in blocking Tool Labs edits to Beta Labs
		'wmgAllowLabsAnonEdits' => [
			'default' => true,
		],

		// Enable page previews for everyone in labs (T162672)
		//
		// Note well that the Popups extension is only loaded when either
		// wmgUsePopups or wmgPopupsBetaFeature is truthy.
		'-wmgUsePopups' => [
			'default' => true,
		],

		// T171853: Enable PP EventLogging instrumentation on enwiki only so that we
		// can prove the killswitch works.
		'-wgPopupsEventLogging' => [
			'default' => false,
			'enwiki' => true,
		],
		// T184793: Enable the VirtualPageViews on beta so we can easily test and track
		// Popups visible for more than 1s as a virtual page views
		'wgPopupsVirtualPageViews' => [
			'default' => true
		],

		// T165018: Make Page Previews (Popups) use RESTBase's page summary endpoint
		// and consume the HTML returned.
		'-wgPopupsGateway' => [
			'default' => 'restbaseHTML',
		],

		'-wgPopupsStatsvSamplingRate' => [
			'default' => 1,
		],

		'wgPopupsReferencePreviews' => [
			'default' => true,
		],

		'wmgUseCollaborationKit' => [
			'default' => false,
			'enwiki' => true,
		],

		'wmgEnableDashikiData' => [
			'default' => true,
		],

		'wgCognateReadOnly' => [
			'default' => false,
		],

		'wgReadingListsCluster' => [
			'default' => false,
		],
		'wgReadingListsDatabase' => [
			'default' => 'metawiki',
		],

		'-wgPageCreationLog' => [
			'default' => true, // T196400
		],

		#####################################################
		# Cirrus-related tweaks specific for the Beta cluster
		#####################################################

		// Use a constant MLR model for all wikis. It's not ideal, but
		// no models were trained specifically for data in labs anyways.
		'-wmgCirrusSearchMLRModel' => [
			'default' => '20171023_enwiki_v1'
		],

		'-wgCirrusSearchFallbackProfile' => [
			'default' => 'phrase_suggest',
		],

		'wgCirrusSearchEnableCrossProjectSearch' => [
			'enwiki' => true,
		],

		# We don't have enough nodes to support these settings in beta so just turn
		# them off.
		'-wgCirrusSearchMaxShardsPerNode' => [
			'default' => []
		],

		# Override prod configuration, there is only one cluster in beta
		'-wgCirrusSearchDefaultCluster' => [
			'default' => 'eqiad'
		],

		# Don't specially configure cluster for more like queries in beta
		'-wgCirrusSearchClusterOverrides' => [
			'default' => []
		],

		# write to all configured clusters, there should only be one in labs
		'-wgCirrusSearchWriteClusters' => [
			'default' => [ 'eqiad' ],
		],

		'-wgCirrusSearchLanguageToWikiMap' => [
			'default' => [
				'ar' => 'ar',
				'de' => 'de',
				'en' => 'en',
				'es' => 'es',
				'fa' => 'fa',
				'he' => 'he',
				'hi' => 'hi',
				'ja' => 'ja',
				'ko' => 'ko',
				'ru' => 'ru',
				'sq' => 'sq',
				'uk' => 'uk',
				'zh-cn' => 'zh',
				'zh-tw' => 'zh',
			]
		],

		# Force the number of replicas to 1 max for the beta cluster
		'-wgCirrusSearchReplicas' => [
			'default' => '0-1'
		],

		// Restore to default behaviour, unlike production config
		'-wgCirrusSearchPrivateClusters' => [
			'default' => null
		],

		'wgActorTableSchemaMigrationStage' => [
			'default' => SCHEMA_COMPAT_NEW,
		],

		'wgCommentTableSchemaMigrationStage' => [
			'default' => MIGRATION_NEW,
		],

		'wgChangeTagsSchemaMigrationStage' => [
			'default' => MIGRATION_WRITE_BOTH,
		],

		'wgMultiContentRevisionSchemaMigrationStage' => [
			'default' => SCHEMA_COMPAT_NEW,
		],

		'wgTagStatisticsNewTable' => [
			'default' => true,
		],

		'wmgUseJADE' => [
			'default' => true,
		],

		'wgOresUiEnabled' => [
			'default' => true,
			'wikidatawiki' => false,
		],
		'wgOresModels' => [
			'default' => [
				'damaging' => [ 'enabled' => true ],
				'goodfaith' => [ 'enabled' => true ],
				'reverted' => [ 'enabled' => false ],
				'articlequality' => [ 'enabled' => false, 'namespaces' => [ 0 ], 'cleanParent' => true ],
				'draftquality' => [ 'enabled' => false, 'namespaces' => [ 0 ], 'types' => [ 1 ] ],
			],
			'enwiki' => [
				'damaging' => [ 'enabled' => true, 'excludeBots' => true ],
				'goodfaith' => [ 'enabled' => true, 'excludeBots' => true ],
				'reverted' => [ 'enabled' => false ],
				'articlequality' => [ 'enabled' => true, 'namespaces' => [ 0, 118 ], 'cleanParent' => true ],
				'draftquality' => [ 'enabled' => true, 'namespaces' => [ 0, 118 ], 'types' => [ 1 ], 'excludeBots' => true, 'cleanParent' => true ],
			],
		],
		'wgOresExcludeBots' => [
			'default' => true,
			'enwiki' => false,
		],
		// T184668
		'wmgUseGlobalPreferences' => [
			// Explicitly disabled on non-CentralAuth wikis in CommonSettings.php
			'default' => true,
		],
		'wmgLocalAuthLoginOnly' => [
			// Explicitly disabled on non-CentralAuth wikis in CommonSettings.php
			'default' => true,
		],
		'wgPageTriageDraftNamespaceId' => [
			'enwiki' => 118,
		],
		'wgPageTriageEnableOresFilters' => [
			'default' => false,
			'enwiki' => true,
		],
		'wgPageTriageEnableCopyvio' => [
			'default' => false,
			'enwiki' => true,
		],
		'wmgWikibaseCachePrefix' => [
			'commonswiki' => 'commonswiki',
		],
		'wgWMEUnderstandingFirstDay' => [
			'default' => false,
			'enwiki' => true,
			'kowiki' => true,
		],
		'wgWMEUnderstandingFirstDaySensitiveNamespaces' => [
			'default' => [ 0, 1, 6, 7 ],
			'enwiki' => [ 0, 1, 6, 7, 100, 101, 118, 119 ],
			'kowiki' => [ 0, 1, 6, 7, 100, 101, 118, 119 ],
		],
		'wmgUseGrowthExperiments' => [
			'enwiki' => true,
			'kowiki' => true,
			'cawiki' => true,
			'arwiki' => true,
		],
		'wgWelcomeSurveyEnabled' => [
			'enwiki' => true,
			'kowiki' => true,
			'arwiki' => true,
		],
		'wgGEHelpPanelEnabled' => [
			'enwiki' => true,
			'kowiki' => true,
			'cawiki' => true,
			'arwiki' => true,
		],
		'wgGEHelpPanelLoggingEnabled' => [
			'enwiki' => true,
			'kowiki' => true,
			'cawiki' => true,
			'arwiki' => true,
		],
		'wgGEHelpPanelHelpDeskTitle' => [
			'enwiki' => 'Wikipedia:Help_desk',
			'kowiki' => '위키백과:도움말',
			'cawiki' => 'Viquipèdia:Potřebuji_pomoc',
			'arwiki' => 'مساعدة',
		],
		'wgGEHelpPanelViewMoreTitle' => [
			'enwiki' => 'Help:Contents',
			'kowiki' => '위키백과:도움말',
			'cawiki' => 'Ajuda:Obsah',
			'arwiki' => 'مساعدة',
		],
		'wgGEHelpPanelLinks' => [
			'enwiki' => [
				[
					'title' => 'Help:Contents',
					'text' => 'The most helpful help link',
					'id' => 'example',
				],
				[
					'title' => 'Wikipedia:Community_portal',
					'text' => 'Community Portal',
					'id' => 'community',
				],
				[
					'title' => 'Wikipedia:Village_pump',
					'text' => 'Village pump',
					'id' => 'village-pump',
				],
				[
					'title' => 'Portal:Contents',
					'text' => 'Portal contents',
					'id' => 'portal-contents',
				],
				[
					'title' => 'Wikipedia:File_Upload_Wizard',
					'text' => 'File upload wizard',
					'id' => 'file-upload-wizard',
				]
			],
			'kowiki' => [
				[
					'title' => '위키백과:도움말',
					'text' => '도움말',
					'id' => 'example',
				]
			],
			'cawiki' => [
				[
					'title' => 'mw:Help:Contents',
					'text' => 'Ajuda',
					'id' => 'help',
				]
			],
			'arwiki' => [
				[
					'title' => 'mw:Help:Contents',
					'text' => 'مساعدة',
					'id' => 'help',
				]
			],
		],
		'wgGEHelpPanelReadingModeNamespaces' => [
			'default' => [ 2, 3, 4, 12 ]
		],
		'wgGEHelpPanelSearchForeignAPI' => [
			'default' => 'https://en.wikipedia.org/w/api.php',
			'arwiki' => 'https://ar.wikipedia.org/w/api.php',
			'cawiki' => 'https://ca.wikipedia.org/w/api.php',
			'cswiki' => 'https://cs.wikipedia.org/w/api.php',
			'kowiki' => 'https://ko.wikipedia.org/w/api.php',
			'viwiki' => 'https://vi.wikipedia.org/w/api.php',
		],
		'wgWelcomeSurveyPrivacyPolicyUrl' => [
			'kowiki' => 'https://foundation.wikimedia.org/wiki/%EC%83%88_%EC%82%AC%EC%9A%A9%EC%9E%90_%ED%99%98%EC%98%81_%EC%84%A4%EB%AC%B8_%EA%B0%9C%EC%9D%B8_%EC%A0%95%EB%B3%B4_%EB%B3%B4%ED%98%B8_%EC%A0%95%EC%B1%85',
		],
		'wgWelcomeSurveyExperimentalGroups' => [
			'kowiki' => [
				'beta_no_survey' => [
					'range' => '0-3',
					'questions' => [],
				],
				'beta_specialpage' => [
					'format' => 'specialpage',
					'range' => '4-6',
					'questions' => [
						'reason',
						'reason-other',
						'edited',
						'topics',
						'topics-other-nojs',
						'topics-other-js',
						'email',
						'mentor-info',
						'mentor',
					],
				],
				'beta_popup' => [
					'format' => 'popup',
					'range' => '7-9',
					'nojs-fallback' => 'beta_specialpage',
					'questions' => [
						'reason',
						'edited',
						'topics',
						'topics-other-js',
						'email',
						'mentor-info',
						'mentor',
					]
				]
			]
		],
		'wgGEHomepageEnabled' => [
			'default' => true,
		],
		'wgGEHomepageSuggestedEditsRequiresOptIn' => [
			'default' => false,
		],
		'wgGEHomepageNewAccountEnablePercentage' => [
			'default' => 80,
		],
		'wgGEHomepageSuggestedEditsNewAccountInitiatedPercentage' => [
			'default' => 50,
		],
		'wgGEHomepageMentorsList' => [
			'default' => '',
			'enwiki' => 'Wikipedia:Mentors',
			'arwiki' => 'Wikipedia:Mentors',
			'kowiki' => '위키백과:새_사용자_경험/새_사용자_멘토',
		],
		'wgGEHomepageTutorialTitle' => [
			'default' => '',
			'enwiki' => 'Help:Getting started',
			'arwiki' => 'Help:Tutorial',
		],
		'wgGEConfirmEmailEnabled' => [
			'default' => true,
		],
		'wgGENewcomerTasksRemoteApiUrl' => [
			'enwiki' => 'https://en.wikipedia.org/w/api.php',
			'arwiki' => 'https://ar.wikipedia.org/w/api.php',
			'cswiki' => 'https://cs.wikipedia.org/w/api.php',
			'kowiki' => 'https://ko.wikipedia.org/w/api.php',
			'viwiki' => 'https://vi.wikipedia.org/w/api.php',
		],
		'wgGENewcomerTasksTopicType' => [
			'default' => 'morelike',
			'enwiki' => 'ores',
			'arwiki' => 'ores',
			'cswiki' => 'ores',
			'kowiki' => 'ores',
			'viwiki' => 'ores',
		],
		'wgGENewcomerTasksGuidanceEnabled' => [
			'default' => true,
		],
		'wgGERestbaseUrl' => [
			'default' => false,
		],
		'wgGEHomepageSuggestedEditsEnableTopics' => [
			'default' => true,
			'cawiki' => false,
		],
		'wgEnablePartialBlocks' => [
			'default' => false,
			'enwiki' => true,
			'wikidatawiki' => true,
			'enwiktionary' => true,
			'enwikibooks' => true,
			'enwikisource' => true,
			'commonswiki' => true,
		],
		'wgEnableSpecialMute' => [
			'default' => true,
			'eswiki' => false,
		],
		'wgPropertySuggesterClassifyingPropertyIds' => [
			'wikidatawiki' => [ 694 ],
		],
		'wgPropertySuggesterInitialSuggestions' => [
			'wikidatawiki' => [ 694 ],
		],
		'wgPropertySuggesterDeprecatedIds' => [
			'wikidatawiki' => [ 107 ],
		],
		'wmgWikibaseRepoBadgeItems' => [
			'wikidatawiki' => [
				'Q49444' => 'wb-badge-goodarticle',
				'Q49447' => 'wb-badge-featuredarticle',
				'Q49448' => 'wb-badge-recommendedarticle', // T72268
				'Q49449' => 'wb-badge-featuredlist', // T72332
				'Q49450' => 'wb-badge-featuredportal', // T75193
				'Q98649' => 'wb-badge-notproofread', // T97014 - Wikisource badges
				'Q98650' => 'wb-badge-problematic',
				'Q98658' => 'wb-badge-proofread',
				'Q98651' => 'wb-badge-validated'
			],
		],
		'wmgWikibaseClientBadgeClassNames' => [
			'default' => [
				'Q49444' => 'badge-goodarticle',
				'Q49447' => 'badge-featuredarticle',
				'Q49448' => 'badge-recommendedarticle', // T72268
				'Q49449' => 'badge-featuredlist', // T72332
				'Q49450' => 'badge-featuredportal', // T75193
				'Q98649' => 'badge-notproofread', // T97014 - Wikisource badges
				'Q98650' => 'badge-problematic',
				'Q98658' => 'badge-proofread',
				'Q98651' => 'badge-validated'
			],
		],
		'wgWikimediaBadgesCommonsCategoryProperty' => [
			'default' => 'P725',
			'commonswiki' => null,
		],
		'wmgUseEntitySourceBasedFederation' => [
			'default' => true,
		],
		'wmgWikibaseEntitySources' => [
			'default' => [
				'wikidata' => [
					'entityNamespaces' => [
						'item' => 0,
						'property' => 120,
						'lexeme' => 146,
					],
					'repoDatabase' => 'wikidatawiki',
					'baseUri' => 'https://wikidata.beta.wmflabs.org/entity/',
					'rdfNodeNamespacePrefix' => 'wd',
					'rdfPredicateNamespacePrefix' => '',
					'interwikiPrefix' => 'd',
				],
			],
			'commonswiki' => [
				'wikidata' => [
					'entityNamespaces' => [
						'item' => 0,
						'property' => 120,
						'lexeme' => 146,
					],
					'repoDatabase' => 'wikidatawiki',
					'baseUri' => 'https://wikidata.beta.wmflabs.org/entity/',
					'rdfNodeNamespacePrefix' => 'wd',
					'rdfPredicateNamespacePrefix' => '',
					'interwikiPrefix' => 'd',
				],
				'commons' => [
					'entityNamespaces' => [
						'mediainfo' => '6/mediainfo',
					],
					'repoDatabase' => 'commonswiki',
					'baseUri' => 'https://commons.wikimedia.beta.wmflabs.org/entity/',
					'rdfNodeNamespacePrefix' => 'sdc',
					'rdfPredicateNamespacePrefix' => 'sdc',
					'interwikiPrefix' => 'c',
				],
			],
		],
		'wmgWikibaseLocalEntitySourceName' => [
			'default' => 'wikidata',
			'commonswiki' => 'commons',
		],
		'wmgWBRepoFormatterUrlProperty' => [
			'wikidatawiki' => 'P9094',
		],
		'wmgWBRepoPreferredGeoDataProperties' => [
			'wikidatawiki' => [
				'P740',
				'P477',
			],
		],
		'wmgWBRepoPreferredPageImagesProperties' => [
			'wikidatawiki' => [
				'P448',
				'P715',
				'P723',
				'P733',
				'P964',
			],
		],
		'-wmgWBRepoConceptBaseUri' => [
			'commonswiki' => 'https://commons.wikimedia.beta.wmflabs.org/entity/',
			'wikidatawiki' => 'https://wikidata.beta.wmflabs.org/entity/',
		],
		'wgArticlePlaceholderImageProperty' => [
			'default' => 'P964',
		],
		'wmgWikibaseRepoEnableRefTabs' => [
			'wikidatawiki' => true,
		],
		'wmgWikibaseClientEchoIcon' => [
			'default' => [ 'path' => '/static/images/wikibase/echoIcon.svg' ],
		],
		'wmgWBRepoCanonicalUriProperty' => [
			'default' => null,
			'wikidata' => 'P174944',
		],
		'wmgWikibaseClientUseTermsTableSearchFields' => [
			'default' => true,
		],
		'wmgWikibaseClientPropertyOrderUrl' => [
			'default' => null,
		],
		'-wmgWikibaseClientRepoUrl' => [
			'default' => 'https://wikidata.beta.wmflabs.org',
		],
		'-wmgWikibaseClientRepoConceptBaseUri' => [
			'default' => 'http://wikidata.beta.wmflabs.org/entity/'
		],
		'-wmgWikibaseClientRepositories' => [
			'default' => [
			],
		],
		'-wmgWikibaseClientChangesDatabase' => [
			'default' => 'wikidatawiki',
		],

		'-wmgWikibaseClientRepoDatabase' => [
			'default' => 'wikidatawiki',
		],

		// T209143
		'wmgWikibaseUseSSRTermbox' => [
			'default' => false,
			'wikidatawiki' => true,
		],

		'wmgWikibaseSSRTermboxServerUrl' => [
			'default' => '',
			'wikidatawiki' => 'https://ssr-termbox.wmflabs.org/termbox',
		],

		//T232191
		'wmgWikibaseTaintedReferencesEnabled' => [
			'default' => false,
			'wikidatawiki' => true,
		],

		'wmgWikibaseTmpPropertyTermsMigrationStage' => [
			'default' => MIGRATION_NEW,
			'wikidatawiki' => MIGRATION_NEW // Override production
		],

		// T235033
		'wmgWikibaseRepoDataBridgeEnabled' => [
			'default' => false,
			'wikidatawiki' => true,
		],

		// T226816
		'wmgWikibaseClientDataBridgeEnabled' => [
			'default' => true,
		],

		'wmgWikibaseClientWellKnownReferencePropertyIds' => [
			'default' => [
				'referenceUrl' => 'P709',
				'title' => 'P160020',
				'statedIn' => 'P764',
				'author' => 'P253075',
				'publisher' => 'P760',
				'publicationDate' => 'P766',
				'retrievedDate' => 'P761',
			],
		],

		'wmgWikibaseClientDataBridgeHrefRegExp' => [
			'default' => 'https://wikidata\.beta\.wmflabs\.org/wiki/((Q[1-9][0-9]*)).*#(P[1-9][0-9]*)',
		],

		// T232582
		'wmgWikibaseClientDataBridgeEditTags' => [
			'default' => [ 'Data Bridge' ],
		],

		'wmgWikibaseRepoForeignRepositories' => [
			'default' => [],
		],
		'wmgWikibaseEntityDataFormats' => [
			'default' => [
				'json',
				'php',
				'rdfxml',
				'n3',
				'turtle',
				'ntriples',
				'html',
				'jsonld',
			],
		],

		// If set to "false": No alternate links will be added to desktop pages,
		// and MobileFrontend won't add a canonical tag
		// If set to "true": Alternate link will be added, MF will add a canonical tag
		'wgMFNoindexPages' => [
			'default' => true
		],

		// Needed by browser tests for Minerva otherwise those will fail
		'wgMFDisplayWikibaseDescriptions' => [
			'enwiki' => [
				'search' => true, 'nearby' => true, 'watchlist' => true, 'tagline' => true
			]
		],

		'wmgUseWikibaseQuality' => [
			'commonswiki' => true,
		],

		'wgWBQualityConstraintsEnableConstraintsCheckJobsRatio' => [
			'default' => 100, // 100% of edits trigger post edit job run constraint checks
		],

		// T209957 configure WikibaseQualityConstraints extension on beta
		// the default values are entity IDs on production Wikidata,
		// the custom values are corresponding entities on Beta Wikidata
		// (most of them were automatically imported from production Wikidata)
		'wgWBQualityConstraintsInstanceOfId' => [
			'wikibaserepo' => 'P694',
		],
		'wgWBQualityConstraintsSubclassOfId' => [
			'wikibaserepo' => 'P279',
		],
		'wgWBQualityConstraintsPropertyConstraintId' => [
			'wikibaserepo' => 'P241048',
		],
		'wgWBQualityConstraintsExceptionToConstraintId' => [
			'wikibaserepo' => 'P241049',
		],
		'wgWBQualityConstraintsConstraintStatusId' => [
			'wikibaserepo' => 'P241050',
		],
		'wgWBQualityConstraintsMandatoryConstraintId' => [
			'wikibaserepo' => 'Q505095',
		],
		'wgWBQualityConstraintsSuggestionConstraintId' => [
			'wikibaserepo' => 'Q524851',
		],
		'wgWBQualityConstraintsDistinctValuesConstraintId' => [
			'wikibaserepo' => 'Q505096',
		],
		'wgWBQualityConstraintsMultiValueConstraintId' => [
			'wikibaserepo' => 'Q505097',
		],
		'wgWBQualityConstraintsUsedAsQualifierConstraintId' => [
			'wikibaserepo' => 'Q505098',
		],
		'wgWBQualityConstraintsSingleValueConstraintId' => [
			'wikibaserepo' => 'Q505099',
		],
		'wgWBQualityConstraintsSymmetricConstraintId' => [
			'wikibaserepo' => 'Q505100',
		],
		'wgWBQualityConstraintsTypeConstraintId' => [
			'wikibaserepo' => 'Q505101',
		],
		'wgWBQualityConstraintsValueTypeConstraintId' => [
			'wikibaserepo' => 'Q505102',
		],
		'wgWBQualityConstraintsInverseConstraintId' => [
			'wikibaserepo' => 'Q505103',
		],
		'wgWBQualityConstraintsItemRequiresClaimConstraintId' => [
			'wikibaserepo' => 'Q505104',
		],
		'wgWBQualityConstraintsValueRequiresClaimConstraintId' => [
			'wikibaserepo' => 'Q505105',
		],
		'wgWBQualityConstraintsConflictsWithConstraintId' => [
			'wikibaserepo' => 'Q505106',
		],
		'wgWBQualityConstraintsOneOfConstraintId' => [
			'wikibaserepo' => 'Q505107',
		],
		'wgWBQualityConstraintsMandatoryQualifierConstraintId' => [
			'wikibaserepo' => 'Q505108',
		],
		'wgWBQualityConstraintsAllowedQualifiersConstraintId' => [
			'wikibaserepo' => 'Q505109',
		],
		'wgWBQualityConstraintsRangeConstraintId' => [
			'wikibaserepo' => 'Q505110',
		],
		'wgWBQualityConstraintsDifferenceWithinRangeConstraintId' => [
			'wikibaserepo' => 'Q505111',
		],
		'wgWBQualityConstraintsCommonsLinkConstraintId' => [
			'wikibaserepo' => 'Q505112',
		],
		'wgWBQualityConstraintsContemporaryConstraintId' => [
			'wikibaserepo' => 'Q505113',
		],
		'wgWBQualityConstraintsFormatConstraintId' => [
			'wikibaserepo' => 'Q505114',
		],
		'wgWBQualityConstraintsUsedForValuesOnlyConstraintId' => [
			'wikibaserepo' => 'Q505115',
		],
		'wgWBQualityConstraintsUsedAsReferenceConstraintId' => [
			'wikibaserepo' => 'Q505116',
		],
		'wgWBQualityConstraintsNoBoundsConstraintId' => [
			'wikibaserepo' => 'Q505117',
		],
		'wgWBQualityConstraintsAllowedUnitsConstraintId' => [
			'wikibaserepo' => 'Q505118',
		],
		'wgWBQualityConstraintsSingleBestValueConstraintId' => [
			'wikibaserepo' => 'Q505119',
		],
		'wgWBQualityConstraintsAllowedEntityTypesConstraintId' => [
			'wikibaserepo' => 'Q505120',
		],
		'wgWBQualityConstraintsCitationNeededConstraintId' => [
			'wikibaserepo' => 'Q505121',
		],
		'wgWBQualityConstraintsPropertyScopeConstraintId' => [
			'wikibaserepo' => 'Q505122',
		],
		'wgWBQualityConstraintsClassId' => [
			'wikibaserepo' => 'P241051',
		],
		'wgWBQualityConstraintsRelationId' => [
			'wikibaserepo' => 'P241052',
		],
		'wgWBQualityConstraintsInstanceOfRelationId' => [
			'wikibaserepo' => 'Q505123',
		],
		'wgWBQualityConstraintsSubclassOfRelationId' => [
			'wikibaserepo' => 'Q505124',
		],
		'wgWBQualityConstraintsInstanceOrSubclassOfRelationId' => [
			'wikibaserepo' => 'Q505125',
		],
		'wgWBQualityConstraintsPropertyId' => [
			'wikibaserepo' => 'P694',
		],
		'wgWBQualityConstraintsQualifierOfPropertyConstraintId' => [
			'wikibaserepo' => 'P241054',
		],
		'wgWBQualityConstraintsMinimumQuantityId' => [
			'wikibaserepo' => 'P241055',
		],
		'wgWBQualityConstraintsMaximumQuantityId' => [
			'wikibaserepo' => 'P241056',
		],
		'wgWBQualityConstraintsMinimumDateId' => [
			'wikibaserepo' => 'P241057',
		],
		'wgWBQualityConstraintsMaximumDateId' => [
			'wikibaserepo' => 'P241058',
		],
		'wgWBQualityConstraintsNamespaceId' => [
			'wikibaserepo' => 'P241059',
		],
		'wgWBQualityConstraintsFormatAsARegularExpressionId' => [
			'wikibaserepo' => 'P241060',
		],
		'wgWBQualityConstraintsSyntaxClarificationId' => [
			'wikibaserepo' => 'P241061',
		],
		'wgWBQualityConstraintsConstraintScopeId' => [
			'wikibaserepo' => 'P241062',
		],
		'wgWBQualityConstraintsSeparatorId' => [
			'wikibaserepo' => 'P241063',
		],
		'wgWBQualityConstraintsConstraintCheckedOnMainValueId' => [
			'wikibaserepo' => 'Q505126',
		],
		'wgWBQualityConstraintsConstraintCheckedOnQualifiersId' => [
			'wikibaserepo' => 'Q505127',
		],
		'wgWBQualityConstraintsConstraintCheckedOnReferencesId' => [
			'wikibaserepo' => 'Q505128',
		],
		'wgWBQualityConstraintsNoneOfConstraintId' => [
			'wikibaserepo' => 'Q505129',
		],
		'wgWBQualityConstraintsIntegerConstraintId' => [
			'wikibaserepo' => 'Q505130',
		],
		'wgWBQualityConstraintsWikibaseItemId' => [
			'wikibaserepo' => 'Q505131',
		],
		'wgWBQualityConstraintsWikibasePropertyId' => [
			'wikibaserepo' => 'Q505132',
		],
		'wgWBQualityConstraintsWikibaseLexemeId' => [
			'wikibaserepo' => 'Q505133',
		],
		'wgWBQualityConstraintsWikibaseFormId' => [
			'wikibaserepo' => 'Q505134',
		],
		'wgWBQualityConstraintsWikibaseSenseId' => [
			'wikibaserepo' => 'Q505135',
		],
		'wgWBQualityConstraintsPropertyScopeId' => [
			'wikibaserepo' => 'P241064',
		],
		'wgWBQualityConstraintsAsMainValueId' => [
			'wikibaserepo' => 'Q505136',
		],
		'wgWBQualityConstraintsAsQualifiersId' => [
			'wikibaserepo' => 'Q505137',
		],
		'wgWBQualityConstraintsAsReferencesId' => [
			'wikibaserepo' => 'Q505138',
		],

		// T215684: Load WikibaseCirrusSearch on Beta Cluster
		'wmgUseWikibaseCirrusSearch' => [
			'wikidatawiki' => true,
			'commonswiki' => true,
		],
		// T215684: Enable WikibaseCirrusSearch on Beta Cluster
		'wmgNewWikibaseCirrusSearch' => [
			'wikidatawiki' => true,
			'commonswiki' => true,
		],

		// T216206: Enable WikibaseLexemeCirrusSearch on Beta Cluster
		'wmgUseWikibaseLexemeCirrusSearch' => [
			'wikidatawiki' => true,
		],

		// T216206: Enable WikibaseLexemeCirrusSearch on Beta Cluster
		'wmgNewWikibaseLexemeCirrusSearch' => [
			'wikidatawiki' => true,
		],

		'wgMusicalNotationEnableWikibaseDataType' => [
			'default' => false,
			'wikidatawiki' => true,
		],
		'wgScoreTrim' => [
			'default' => true
		],
		'wgWikibaseMusicalNotationLineWidthInches' => [
			'default' => 8,
			'wikidatawiki' => 3,
		],
		'wmgUseWikisource' => [
			'wikisource' => true,
		],
		'wgWikisourceWikibaseEditionProperty' => [
			'wikisource' => 'P253108'
		],
		'wgWikisourceWikibaseEditionOfProperty' => [
			'wikisource' => 'P253107'
		],
		'wmgUseWikimediaEditorTasks' => [
			'default' => false,
			'wikidatawiki' => true,
			'commonswiki' => true,
		],
		'wgWikimediaEditorTasksUserCountsCluster' => [
			'default' => false,
		],
		'wgWikimediaEditorTasksUserCountsDatabase' => [
			'default' => false,
		],
		'wgWikimediaEditorTasksEnableEditStreaks' => [
			'default' => true,
		],
		'wgWikimediaEditorTasksEnableRevertCounts' => [
			'default' => true,
		],
		'wgWikimediaEditorTasksEnabledCounters' => [
			'default' => [
				[
					'class' => 'MediaWiki\\Extension\\WikimediaEditorTasks\\WikipediaAppDescriptionEditCounter',
					'counter_key' => 'app_description_edits',
				],
				[
					'class' => 'MediaWiki\\Extension\\WikimediaEditorTasks\\WikipediaAppCaptionEditCounter',
					'counter_key' => 'app_caption_edits',
				],
				[
					'class' => 'MediaWiki\\Extension\\WikimediaEditorTasks\\WikipediaAppImageDepictsEditCounter',
					'counter_key' => 'app_depicts_edits',
				],
			],
		],
		'wmgUseMachineVision' => [
			'default' => false,
			'commonswiki' => true,
		],
		'wgMachineVisionTestersOnly' => [
			'default' => false,
		],
		'wgMachineVisionShowUploadWizardCallToAction' => [
			'default' => true,
		],
		'wgMachineVisionNewUploadLabelingJobDelay' => [
			'default' => 0,
		],
		'wgMachineVisionRequestLabelsFromWikidataPublicApi' => [
			'default' => true,
		],
		'-wgSpecialSearchFormOptions' => [
			'wikidatawiki' => [ 'showDescriptions' => true ],
		],
		'wmgUseTheWikipediaLibrary' => [
			'default' => true,
		],
		'wgTwlEditCount' => [
			'default' => 100,
		],
		'wgWBCitoidFullRestbaseURL' => [
			'wikidatawiki' => 'https://en.wikipedia.beta.wmflabs.org/api/rest_',
		],

		'wgRestAPIAdditionalRouteFiles' => [
			'default' => [ 'includes/Rest/coreDevelopmentRoutes.json' ],
		],

		'wgAllowRequiringEmailForResets' => [
			'default' => true,
		],

		'wmgUseCSP' => [
			'default' => true,
		],

		// Domains that go in script-src and default-src for CSP.
		// This also includes the prod domains for the sole purpose
		// of allowing users to test gadgets at beta.
		// beta itself should never call prod.
		'wmgApprovedContentSecurityPolicyDomains' => [
			'default' => [
				'*.wikimedia.beta.wmflabs.org',
				'*.wikipedia.beta.wmflabs.org',
				'*.wikinews.beta.wmflabs.org',
				'*.wiktionary.beta.wmflabs.org',
				'*.wikibooks.beta.wmflabs.org',
				'*.wikiversity.beta.wmflabs.org',
				'*.wikisource.beta.wmflabs.org',
				// No multilingual wikisource on beta.
				'*.wikiquote.beta.wmflabs.org',
				// Unlike production, wikidata does not use www. on beta
				'wikidata.beta.wmflabs.org',
				'm.wikidata.beta.wmflabs.org',
				'*.wikivoyage.beta.wmflabs.org',
				'*.mediawiki.beta.wmflabs.org',

				// Prod domains, to allow easier gadget testing
				'*.wikimedia.org',
				'*.wikipedia.org',
				'*.wikinews.org',
				'*.wiktionary.org',
				'*.wikibooks.org',
				'*.wikiversity.org',
				'*.wikisource.org',
				'wikisource.org',
				'*.wikiquote.org',
				'*.wikidata.org',
				'*.wikivoyage.org',
				'*.mediawiki.org',
			],
		],

		'wgLegacyJavaScriptGlobals' => [
			'default' => false,
		],

		'wmgUseDiscussionTools' => [
			'arwiki' => true,
			'enwiki' => true,
			'nlwiki' => true,
		],

		'-wgDiscussionToolsEnable' => [
			'default' => true,
		],

		'-wgDiscussionToolsBeta' => [
			'default' => false,
		],

		'wgWatchlistExpiry' => [
			'default' => true,
		],

		// Force the videojs player for all users and disable the beta feature
		'-wmgTmhWebPlayer' => [
			'default' => 'videojs',
		],
		'-wgTmhUseBetaFeatures' => [
			'default' => false,
		],

	];
} # wmflLabsSettings()
