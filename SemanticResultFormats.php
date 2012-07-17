<?php

/**
 * Main entry point for the SemanticResultFormats extension.
 * http://www.mediawiki.org/wiki/Extension:Semantic_Result_Formats
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file SemanticResultFormats.php
 * @ingroup SemanticResultFormats
 *
 * @licence GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * This documentation group collects source code files belonging to SemanticResultFormats.
 * 
 * @defgroup SemanticResultFormats SemanticResultFormats
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $wgVersion, '1.17c', '<' ) ) {
	die( '<b>Error:</b> This version of Semantic Result Formats requires MediaWiki 1.17 or above; use SRF 1.7.x or SRF 1.6.x for older versions.' );
}

// Show a warning if Semantic MediaWiki is not loaded.
if ( ! defined( 'SMW_VERSION' ) ) {
	die( '<b>Error:</b> You need to have <a href="http://semantic-mediawiki.org/wiki/Semantic_MediaWiki">Semantic MediaWiki</a> installed in order to use <a href="https://www.mediawiki.org/wiki/Extension:Semantic Result Formats">Semantic Result Formats</a>.<br />' );
}

if ( version_compare( SMW_VERSION, '1.8c', '<' ) ) {
	die( '<b>Error:</b> This version of Semantic Result Formats requires Semantic MediaWiki 1.8 or above; use Semantic Result Formats 1.7.x for SMW 1.7.x.' );
}

define( 'SRF_VERSION', '1.8 alpha' );

// Require the settings file.
require dirname( __FILE__ ) . '/SRF_Settings.php';

require dirname( __FILE__ ) . '/SRF_Resources.php';

// Initialize the formats later on, so the $srfgFormats setting can be manipulated in LocalSettings.
$wgExtensionFunctions[] = 'srffInitFormats';

$wgExtensionMessagesFiles['SemanticResultFormats'] = dirname( __FILE__ ) . '/SRF_Messages.php';
$wgExtensionMessagesFiles['SemanticResultFormatsMagic'] = dirname( __FILE__ ) . '/SRF_Magic.php';

$srfgScriptPath = ( $wgExtensionAssetsPath === false ? $wgScriptPath . '/extensions' : $wgExtensionAssetsPath ) . '/SemanticResultFormats'; 
$srfgIP = dirname( __FILE__ );

$wgExtensionCredits['semantic'][] = array(
	'path' => __FILE__,
	'name' => 'Semantic Result Formats',
	'version' => SRF_VERSION,
	// At least 14 people have contributed formats to this extension, so
	// it would be prohibitive to list them all in the credits. Instead,
	// the current rule is to list anyone who has created, or contributed
	// significantly to, at least three formats, or the overall extension.
	'author' => array(
		'James Hong Kong',
		'Stephan Gambke',
		'[http://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
		'Yaron Koren',
		'...'
	),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Semantic_Result_Formats',
	'descriptionmsg' => 'srf-desc'
);

$formatDir = dirname( __FILE__ ) . '/';

$wgAutoloadClasses['SRFExhibit'] = $formatDir . 'Exhibit/SRF_Exhibit.php';
$wgAutoloadClasses['SRFJitGraph'] = $formatDir . 'JitGraph/SRF_JitGraph.php';
$wgAutoloadClasses['SRFFiltered'] = $formatDir . 'Filtered/SRF_Filtered.php';

// Follows naming convention
$wgAutoloadClasses['SRFjqPlot']       = $formatDir . 'jqplot/SRF_jqPlot.php';
$wgAutoloadClasses['SRFjqPlotPie']    = $formatDir . 'jqplot/SRF_jqPlotPie.php';
$wgAutoloadClasses['SRFjqPlotBar']    = $formatDir . 'jqplot/SRF_jqPlotBar.php';
$wgAutoloadClasses['SRFjqPlotSeries'] = $formatDir . 'jqplot/SRF_jqPlotSeries.php';
$wgAutoloadClasses['SRFPloticusVBar'] = $formatDir . 'ploticus/SRF_PloticusVBar.php';
$wgAutoloadClasses['SRFD3Chart']   = $formatDir . 'd3/SRF_D3Chart.php';
$wgAutoloadClasses['SRFGraph']     = $formatDir . 'graphviz/SRF_Graph.php';
$wgAutoloadClasses['SRFProcess']   = $formatDir . 'graphviz/SRF_Process.php';
$wgAutoloadClasses['SRFCalendar']  = $formatDir . 'calendar/SRF_Calendar.php';
$wgAutoloadClasses['SRFArray']     = $formatDir . 'array/SRF_Array.php';
$wgAutoloadClasses['SRFHash']      = $formatDir . 'array/SRF_Hash.php';
$wgAutoloadClasses['SRFiCalendar'] = $formatDir . 'icalendar/SRF_iCalendar.php';
$wgAutoloadClasses['SRFGoogleBar'] = $formatDir . 'googlecharts/SRF_GoogleBar.php';
$wgAutoloadClasses['SRFGooglePie'] = $formatDir . 'googlecharts/SRF_GooglePie.php';
$wgAutoloadClasses['SRFOutline']   = $formatDir . 'outline/SRF_Outline.php';
$wgAutoloadClasses['SRFTime']      = $formatDir . 'time/SRF_Time.php';
$wgAutoloadClasses['SRFSlideShow'] = $formatDir . 'slideshow/SRF_SlideShow.php';
$wgAutoloadClasses['SRFTree']      = $formatDir . 'tree/SRF_Tree.php';
$wgAutoloadClasses['SRFGallery']   = $formatDir . 'gallery/SRF_Gallery.php';
$wgAutoloadClasses['SRFTagCloud']  = $formatDir . 'tagcloud/SRF_TagCloud.php';
$wgAutoloadClasses['SRFMath']      = $formatDir . 'math/SRF_Math.php';
$wgAutoloadClasses['SRFTimeline']  = $formatDir . 'timeline/SRF_Timeline.php';
$wgAutoloadClasses['SRFvCard']     = $formatDir . 'vcard/SRF_vCard.php';
$wgAutoloadClasses['SRFValueRank'] = $formatDir . 'valuerank/SRF_ValueRank.php';
$wgAutoloadClasses['SRFBibTeX']    = $formatDir . 'bibtex/SRF_BibTeX.php';

unset( $formatDir );

$wgAutoloadClasses['SRFParserFunctions'] = $srfgIP . '/SRF_ParserFunctions.php';
$wgAutoloadClasses['SRFHooks']           = $srfgIP . '/SRF_Hooks.php';
$wgAutoloadClasses['SRFUtils']           = $srfgIP . '/SRF_Utils.php';

$wgHooks['AdminLinks'][] = 'SRFHooks::addToAdminLinks';
$wgHooks['ParserFirstCallInit'][] = 'SRFParserFunctions::registerFunctions';

$wgAjaxExportList[] = 'SRFSlideShow::handleGetResult';

/**
 * Autoload the query printer classes and associate them with their formats in the $smwgResultFormats array.
 * 
 * @since 1.5.2
 */
function srffInitFormats() {
	global $srfgFormats, $smwgResultFormats, $smwgResultAliases;
	
	$formatClasses = array(
		'timeline' => 'SRFTimeline',
		'eventline' => 'SRFTimeline',
		'vcard' => 'SRFvCard',
		'icalendar' => 'SRFiCalendar',
		'bibtex' => 'SRFBibTeX',
		'calendar' => 'SRFCalendar',
		'outline' => 'SRFOutline',
		'sum' => 'SRFMath',
		'product' => 'SRFMath',
		'average' => 'SRFMath',
		'min' => 'SRFMath',
		'max' => 'SRFMath',
		'median' => 'SRFMath',
		'exhibit' => 'SRFExhibit',
		'googlebar' => 'SRFGoogleBar',
		'googlepie' => 'SRFGooglePie',
		'jitgraph' => 'SRFJitGraph',
		'jqplotpie' => 'SRFjqPlotPie',
		'jqplotbar' => 'SRFjqPlotBar',
		'jqplotseries' => 'SRFjqPlotSeries',		
		'graph' => 'SRFGraph',
		'process' => 'SRFProcess',
		'ploticusvbar' => 'SRFPloticusVBar',
		'gallery' => 'SRFGallery',
		'tagcloud' => 'SRFTagCloud',
		'valuerank' => 'SRFValueRank',
		'array' => 'SRFArray',
		'hash' => 'SRFHash',
		'd3chart' => 'SRFD3Chart',
		'tree' => 'SRFTree',
		'ultree' => 'SRFTree',
		'oltree' => 'SRFTree',
		'filtered' => 'SRFFiltered',
		'latest' => 'SRFTime',
		'earliest' => 'SRFTime',
		'slideshow' => 'SRFSlideShow',
	);

	$formatAliases = array(
		'tagcloud'  => array( 'tag cloud' ),
		'valuerank' => array( 'value rank' ),
		'd3chart'   => array( 'd3 chart' )		
	);
	
	foreach ( $srfgFormats as $format ) {
		if ( array_key_exists( $format, $formatClasses ) ) {
			$smwgResultFormats[$format] = $formatClasses[$format];
			
			if ( isset( $smwgResultAliases ) && array_key_exists( $format, $formatAliases ) ) {
				$smwgResultAliases[$format] = $formatAliases[$format];
			}
		}
		else {
			wfDebug( "There is no result format class associated with the '$format' format." );
		}
	}	
}
