<?php

/**
 * Printer class for creating BibTeX exports
 *
 * For details on availble keys see the README
 *
 * Example of a book :
 *
 * @Book{abramowitz1964homf,
 *   author =     "Milton Abramowitz and Irene A. Stegun",
 *   title =     "Handbook of Mathematical Functions",
 *   publisher =     "Dover",
 *   year =     1964,
 *   address =     "New York",
 *   edition =     "ninth Dover printing, tenth GPO printing"
 * }
 *
 * @file
 * @ingroup SemanticResultFormats
 *
 * @author Markus Krötzsch
 * @author Denny Vrandecic
 * @author Frank Dengler
 * @author Steren Giannini
 * @ingroup SemanticResultFormats
 */
class SRFBibTeX extends SMWExportPrinter {

	protected $m_title = '';
	protected $m_description = '';

	/**
	 * @see SMWIExportPrinter::getMimeType
	 *
	 * @since 1.8
	 *
	 * @param SMWQueryResult $queryResult
	 *
	 * @return string
	 */
	public function getMimeType( SMWQueryResult $queryResult ) {
		return 'text/bibtex';
	}

	/**
	 * @see SMWIExportPrinter::getFileName
	 *
	 * @since 1.8
	 *
	 * @param SMWQueryResult $queryResult
	 *
	 * @return string|boolean
	 */
	public function getFileName( SMWQueryResult $queryResult ) {
		if ( $this->getSearchLabel( SMW_OUTPUT_WIKI ) != '' ) {
			return str_replace( ' ', '_', $this->getSearchLabel( SMW_OUTPUT_WIKI ) ) . '.bib';
		} else {
			return 'BibTeX.bib';
		}
	}

	public function getQueryMode( $context ) {
		return ( $context == SMWQueryProcessor::SPECIAL_PAGE ) ? SMWQuery::MODE_INSTANCES : SMWQuery::MODE_NONE;
	}

	public function getName() {
		return wfMessage( 'srf_printername_bibtex' )->text();
	}

	protected function getResultText( SMWQueryResult $res, $outputmode ) {
		global $wgSitename;
		$result = '';

		if ( $outputmode == SMW_OUTPUT_FILE ) { // make file
			if ( $this->m_title == '' ) {
				$this->m_title = $wgSitename;
			}

			$items = [];

			while ( $row = $res->getNext() ) {
				$items[] = $this->getItemForResultRow( $row )->text();
			}

			$result = implode( '', $items );
		} else { // just make link to export
			if ( $this->getSearchLabel( $outputmode ) ) {
				$label = $this->getSearchLabel( $outputmode );
			} else {
				$label = wfMessage( 'srf_bibtex_link' )->inContentLanguage()->text();
			}

			$link = $res->getQueryLink( $label );
			$link->setParameter( 'bibtex', 'format' );

			if ( $this->getSearchLabel( SMW_OUTPUT_WIKI ) != '' ) {
				$link->setParameter( $this->getSearchLabel( SMW_OUTPUT_WIKI ), 'searchlabel' );
			}

			$result .= $link->getText( $outputmode, $this->mLinker );
			$this->isHTML = ( $outputmode == SMW_OUTPUT_HTML ); // yes, our code can be viewed as HTML if requested, no more parsing needed
		}

		return $result;
	}

	/**
	 * Gets a SMWBibTeXEntry for the row.
	 *
	 * @since 1.6
	 *
	 * @param $row array of SMWResultArray
	 *
	 * @return SMWBibTeXEntry
	 */
	protected function getItemForResultRow( array /* of SMWResultArray */
	$row ) {
		$address = '';
		$annote = '';
		$author = '';
		$booktitle = '';
		$chapter = '';
		$crossref = '';
		$doi = '';
		$edition = '';
		$editor = '';
		$eprint = '';
		$howpublished = '';
		$institution = '';
		$isbn = '';
		$issn = '';
		$journal = '';
		$key = '';
		$month = '';
		$note = '';
		$number = '';
		$organization = '';
		$pages = '';
		$publisher = '';
		$school = '';
		$series = '';
		$title = '';
		$type = '';
		$url = '';
		$volume = '';
		$year = '';

		foreach ( $row as /* SMWResultArray */
				  $field ) {
			$req = $field->getPrintRequest();
			$label = strtolower( $req->getLabel() );
			$var = false;

			switch ( $label ) {
				case 'type':
					$var =& $type;
					break;
				case 'address':
					$var =& $address;
					break;
				case 'annote':
					$var =& $annote;
					break;
				case 'booktitle':
					$var =& $booktitle;
					break;
				case 'chapter':
					$var =& $chapter;
					break;
				case 'crossref':
					$var =& $crossref;
					break;
				case 'doi':
					$var =& $doi;
					break;
				case 'edition':
					$var =& $edition;
					break;
				case 'eprint':
					$var =& $eprint;
					break;
				case 'howpublished':
					$var =& $howpublished;
					break;
				case 'institution':
					$var =& $institution;
					break;
				case 'isbn':
					$var =& $isbn;
					break;
				case 'issn':
					$var =& $issn;
					break;
				case 'journal':
					$var =& $journal;
					break;
				case 'key':
					$var =& $key;
					break;
				case 'note':
					$var =& $note;
					break;
				case 'number':
					$var =& $number;
					break;
				case 'organization':
					$var =& $organization;
					break;
				case 'pages':
					$var =& $pages;
					break;
				case 'publisher':
					$var =& $publisher;
					break;
				case 'school':
					$var =& $school;
					break;
				case 'series':
					$var =& $series;
					break;
				case 'title':
					$var =& $title;
					break;
				case 'url':
					$var =& $url;
					break;
				case 'year':
					$var =& $year;
					break;
				case 'month':
					$var =& $month;
					break;
				case 'volume':
				case 'journal_volume':
					$var =& $volume;
					break;
			}

			if ( $var !== false ) {
				$dataValue = $field->getNextDataValue();

				if ( $dataValue !== false ) {
					$var = $dataValue->getShortWikiText();
				}

				unset( $var );
			} else {
				switch ( $label ) {
					case 'author':
					case 'authors':
					case 'editor' :
					case 'editors':
						$wikiTexts = [];
						while ( ( /* SMWDataValue */
							$dataValue = $field->getNextDataValue() ) !== false ) {
							$wikiTexts[] = $dataValue->getShortWikiText();
						}
						$wikiText = implode(" and ", $wikiTexts);
						if ( $label == 'author' || $label == 'authors' ) {
							$author = $wikiText;
						} else {
							$editor = $wikiText;
						}
						break;
					case 'date':
						$dataValue = $field->getNextDataValue();

						if ( $dataValue !== false && get_class( $dataValue ) == 'SMWTimeValue' ) {
							$year = $dataValue->getYear();
							$month = $dataValue->getMonth();
						}
						break;
				}
			}
		}

		return new SMWBibTeXEntry(
			$type,
			$address,
			$annote,
			$author,
			$booktitle,
			$chapter,
			$crossref,
			$doi,
			$edition,
			$editor,
			$eprint,
			$howpublished,
			$institution,
			$isbn,
			$issn,
			$journal,
			$key,
			$month,
			$note,
			$number,
			$organization,
			$pages,
			$publisher,
			$school,
			$series,
			$title,
			$url,
			$volume,
			$year
		);
	}
}

/**
 * Represents a single entry in an BibTeX
 *
 * @ingroup SMWQuery
 */
class SMWBibTeXEntry {

	private $bibTeXtype;
	private $URI;
	private $fields = [];

	public function __construct( $type, $address, $annote, $author, $booktitle, $chapter, $crossref, $doi, $edition, $editor, $eprint, $howpublished, $institution, $isbn, $issn, $journal, $key, $month, $note, $number, $organization, $pages, $publisher, $school, $series, $title, $url, $volume, $year ) {
		if ( $type ) {
			$this->bibTeXtype = ucfirst( $type );
		} else {
			$this->bibTeXtype = 'Book';
		}

		$fields = [];

		if ( $address ) {
			$fields['address'] = $address;
		}
		if ( $annote ) {
			$fields['annote'] = $annote;
		}
		if ( $author ) {
			$fields['author'] = $author;
		}
		if ( $booktitle ) {
			$fields['booktitle'] = $booktitle;
		}
		if ( $chapter ) {
			$fields['chapter'] = $chapter;
		}
		if ( $crossref ) {
			$fields['crossref'] = $crossref;
		}
		if ( $doi ) {
			$fields['doi'] = $doi;
		}
		if ( $edition ) {
			$fields['edition'] = $edition;
		}
		if ( $editor ) {
			$fields['editor'] = $editor;
		}
		if ( $eprint ) {
			$fields['eprint'] = $eprint;
		}
		if ( $howpublished ) {
			$fields['howpublished'] = $howpublished;
		}
		if ( $institution ) {
			$fields['institution'] = $institution;
		}
		if ( $isbn ) {
			$fields['isbn'] = $isbn;
		}
		if ( $issn ) {
			$fields['issn'] = $issn;
		}
		if ( $journal ) {
			$fields['journal'] = $journal;
		}
		if ( $key ) {
			$fields['key'] = $key;
		}
		if ( $month ) {
			$fields['month'] = $month;
		}
		if ( $note ) {
			$fields['note'] = $note;
		}
		if ( $number ) {
			$fields['number'] = $number;
		}
		if ( $organization ) {
			$fields['organization'] = $organization;
		}
		if ( $pages ) {
			$fields['pages'] = $pages;
		}
		if ( $publisher ) {
			$fields['publisher'] = $publisher;
		}
		if ( $school ) {
			$fields['school'] = $school;
		}
		if ( $series ) {
			$fields['series'] = $series;
		}
		if ( $title ) {
			$fields['title'] = $title;
		}
		if ( $url ) {
			$fields['url'] = $url;
		}
		if ( $volume ) {
			$fields['volume'] = $volume;
		}
		if ( $year ) {
			$fields['year'] = $year;
		}

		// fix Umlaute and other non-ascii chars
		foreach ($fields as $key=>$val){
			$fields[$key]=SMWBibTeXEntry::BibTeXCharReplace(utf8_decode($val));
			$this->fields = $fields;
		}

		// generating the URI: last name of first author + year + first letters of title(special characters will be filtered)
		$URI = '';
		if ( $author ) {
		 	$arrayAuthor = explode( ' and ', $author );
			$arrayAuthor = explode( ' ', $arrayAuthor[0] );
			$URI .= SMWBibTeXEntry::BibTeXURIReplace(utf8_decode(end ( $arrayAuthor )));
		}

		if ( $year ) {
			$URI .= $year;
		}

		if ( $title ) {
 			foreach ( explode( ' ', $title ) as $titleWord ) {
				if( preg_match( '/[A-Za-z]/', $titleWord, $charsTitleWord )){
					$URI .= $charsTitleWord[0];
				}
			} 
		}

		$this->URI = strtolower( $URI );
	}

	/**
	 * Creates the BibTeX output for a single item.
	 */
	public function text() {
		$text = '@' . $this->bibTeXtype . '{' . $this->URI . ",\r\n";

		foreach ( $this->fields as $key => $value ) {
			$text .= '  ' . $key . ' = "' . $value . '", ' . "\r\n";
		}

		$text .= "}\r\n\r\n";

		return $text;
	}
	/**
	 * Replaces Ä,ä,Ö,ö,Ü,ü,ß in URI and every other sign will be deleted
	 */
	public static function BibTeXURIReplace($text) {
		$latex_equivalents = array (
			0x00c4 => 'Ae',
			0x00e4 => 'ae',
			0x00d6 => 'Oe',
			0x00f6 => 'oe',
			0x00dc => 'Ue',
			0x00fc => 'ue',
			0x00df => 'ss',
		);
			
	$textarray=str_split($text);
	$output="";
	foreach ($textarray as $i) {
	   $output.=($latex_equivalents[ord($i)]?"".$latex_equivalents[ord($i)]."":$i);
	}
	$output = preg_replace ( '/[^a-z]/i', '', $output );
	return $output;
	}
	
	/**
	 * Replaces special characters into latex language .
	 */
	public static function BibTeXCharReplace($text) {

		$latex_equivalents = array (
		    // Zusätzliche Zeichen -- mm
		    ord('$') => '\$',
		    ord('_') => '\_',
		    ord('{') => '\{',
		    ord('}') => '\}',
		    ord('\\') => "\\textbackslash",
		    ord('%') => '\%',
		
		    // aus dem Python Skript
		    0x0009 => ' ',
		    0x000a => '\n',
		    0x0023 => '\#',
		    0x0026 => '\&',
		    0x00a0 => '~',
		    0x00a1 => '!`',
		    0x00a2 => '\\not{c}',
		    0x00a3 => '\\pounds',
		    0x00a7 => '\\S',
		    0x00a8 => '\\"{}',
		    0x00a9 => '\\textcopyright',
		    0x00af => '\\={}',
		    0x00ac => '\\neg',
		    0x00ad => '\\-',
			0x00ae => '\\textregistered',
		    0x00b0 => '\\mbox{$^\\circ$}',
		    0x00b1 => '\\mbox{$\\pm$}',
		    0x00b2 => '\\mbox{$^2$}',
		    0x00b3 => '\\mbox{$^3$}',
		    0x00b4 => "\\'{",
		    0x00b5 => '\\mbox{$\\mu$}',
		    0x00b6 => '\\P',
		    0x00b7 => '\\mbox{$\\cdot$}',
		    0x00b8 => '\\c{}',
		    0x00b9 => '\\mbox{$^1$}',
		    0x00bf => '?`',
		    0x00c0 => '\\`A',
		    0x00c1 => "\\'A",
		    0x00c2 => '\\^A',
		    0x00c3 => '\\~A',
		    0x00c4 => '\\"A',
		    0x00c5 => '\\AA',
		    0x00c6 => '\\AE',
		    0x00c7 => '\\c{C}',
		    0x00c8 => '\\`E',
		    0x00c9 => "\\'E",
		    0x00ca => '\\^E',
		    0x00cb => '\\"E',
		    0x00cc => '\\`I',
		    0x00cd => "\\'I",
		    0x00ce => '\\^I',
		    0x00cf => '\\"I',
		    0x00d1 => '\\~N',
		    0x00d2 => '\\`O',
		    0x00d3 => "\\'O",
		    0x00d4 => '\\^O',
		    0x00d5 => '\\~O',
		    0x00d6 => '\\"O',
		    0x00d7 => '\\mbox{$\\times$}',
		    0x00d8 => '\\O',
		    0x00d9 => '\\`U',
		    0x00da => "\\'U",
		    0x00db => '\\^U',
		    0x00dc => '\\"U',
		    0x00dd => "\\'Y",
		    0x00df => '\\ss',
		    0x00e0 => '\\`a',
		    0x00e1 => "\\'a",
		    0x00e2 => '\\^a',
		    0x00e3 => '\\~a',
		    0x00e4 => '\\"a',
		    0x00e5 => '\\aa',
		    0x00e6 => '\\ae',
		    0x00e7 => '\\c{c}',
		    0x00e8 => '\\`e',
		    0x00e9 => "\\'e",
		    0x00ea => '\\^e',
		    0x00eb => '\\"e',
		    0x00ec => '\\`\\i',
		    0x00ed => "\\'\\i",
		    0x00ee => '\\^\\i',
		    0x00ef => '\\"\\i',
		    0x00f1 => '\\~n',
		    0x00f2 => '\\`o',
		    0x00f3 => "\\'o",
		    0x00f4 => '\\^o',
		    0x00f5 => '\\~o',
		    0x00f6 => '\\"o',
		    0x00f7 => '\\mbox{$\\div$}',
		    0x00f8 => '\\o',
		    0x00f9 => '\\`u',
		    0x00fa => "\\'u",
		    0x00fb => '\\^u',
		    0x00fc => '\\"u',
		    0x00fd => "\\'y",
		    0x00ff => '\\"y',
	
		    0x0100 => '\\=A',
		    0x0101 => '\\=a',
		    0x0102 => '\\u{A}',
		    0x0103 => '\\u{a}',
		    0x0104 => '\\c{A}',
		    0x0105 => '\\c{a}',
		    0x0106 => "\\'C",
		    0x0107 => "\\'c",
		    0x0108 => "\\^C",
		    0x0109 => "\\^c",
		    0x010a => "\\.C",
		    0x010b => "\\.c",
		    0x010c => "\\v{C",
		    0x010d => "\\v{c",
		    0x010e => "\\v{D",
		    0x010f => "\\v{d",
		    0x0112 => '\\=E',
		    0x0113 => '\\=e',
		    0x0114 => '\\u{E}',
		    0x0115 => '\\u{e}',
		    0x0116 => '\\.E',
		    0x0117 => '\\.e',
		    0x0118 => '\\c{E}',
		    0x0119 => '\\c{e}',
		    0x011a => "\\v{E",
    0x011b => "\\v{e",
    0x011c => '\\^G',
    0x011d => '\\^g',
    0x011e => '\\u{G}',
    0x011f => '\\u{g}',
    0x0120 => '\\.G',
    0x0121 => '\\.g',
    0x0122 => '\\c{G}',
    0x0123 => '\\c{g}',
    0x0124 => '\\^H',
    0x0125 => '\\^h',
    0x0128 => '\\~I',
    0x0129 => '\\~\\i',
    0x012a => '\\=I',
    0x012b => '\\=\\i',
    0x012c => '\\u{I}',
    0x012d => '\\u\\i',
    0x012e => '\\c{I}',
    0x012f => '\\c{i}',
    0x0130 => '\\.I',
    0x0131 => '\\i',
    0x0132 => 'IJ',
    0x0133 => 'ij',
    0x0134 => '\\^J',
    0x0135 => '\\^\\j',
    0x0136 => '\\c{K}',
    0x0137 => '\\c{k}',
    0x0139 => "\\'L",
    0x013a => "\\'l",
    0x013b => "\\c{L",
    0x013c => "\\c{l",
    0x013d => "\\v{L",
    0x013e => "\\v{l",
    0x0141 => '\\L',
    0x0142 => '\\l',
    0x0143 => "\\'N",
    0x0144 => "\\'n",
    0x0145 => "\\c{N",
    0x0146 => "\\c{n",
    0x0147 => "\\v{N",
    0x0148 => "\\v{n",
    0x014c => '\\=O',
    0x014d => '\\=o',
    0x014e => '\\u{O}',
    0x014f => '\\u{o}',
    0x0150 => '\\H{O}',
    0x0151 => '\\H{o}',
    0x0152 => '\\OE',
    0x0153 => '\\oe',
    0x0154 => "\\'R",
    0x0155 => "\\'r",
    0x0156 => "\\c{R",
    0x0157 => "\\c{r",
    0x0158 => "\\v{R",
    0x0159 => "\\v{r",
    0x015a => "\\'S",
    0x015b => "\\'s",
    0x015c => "\\^S",
    0x015d => "\\^s",
    0x015e => "\\c{S",
    0x015f => "\\c{s",
    0x0160 => "\\v{S",
    0x0161 => "\\v{s",
    0x0162 => "\\c{T",
    0x0163 => "\\c{t",
    0x0164 => "\\v{T",
    0x0165 => "\\v{t",
    0x0168 => "\\~U",
    0x0169 => "\\~u",
    0x016a => "\\=U",
    0x016b => "\\=u",
    0x016c => "\\u{U",
    0x016d => "\\u{u",
    0x016e => "\\r{U",
    0x016f => "\\r{u",
    0x0170 => "\\H{U",
    0x0171 => "\\H{u",
    0x0172 => "\\c{U",
    0x0173 => "\\c{u",
    0x0174 => "\\^W",
    0x0175 => "\\^w",
    0x0176 => "\\^Y",
    0x0177 => "\\^y",
    0x0178 => '\\"Y',
    0x0179 => "\\'Z",
    0x017a => "\\'Z",
    0x017b => "\\.Z",
    0x017c => "\\.Z",
    0x017d => "\\v{Z",
    0x017e => "\\v{z",

    0x01c4 => "D\\v{Z",
    0x01c5 => "D\\v{z",
    0x01c6 => "d\\v{z",
    0x01c7 => "LJ",
    0x01c8 => "Lj",
    0x01c9 => "lj",
    0x01ca => "NJ",
    0x01cb => "Nj",
    0x01cc => "nj",
    0x01cd => "\\v{A",
    0x01ce => "\\v{a",
    0x01cf => "\\v{I",
    0x01d0 => "\\v\\i",
    0x01d1 => "\\v{O",
    0x01d2 => "\\v{o",
    0x01d3 => "\\v{U",
    0x01d4 => "\\v{u",
    0x01e6 => "\\v{G",
    0x01e7 => "\\v{g",
    0x01e8 => "\\v{K",
    0x01e9 => "\\v{k",
    0x01ea => "\\c{O",
    0x01eb => "\\c{o",
    0x01f0 => "\\v\\j",
    0x01f1 => "DZ",
    0x01f2 => "Dz",
    0x01f3 => "dz",
    0x01f4 => "\\'G",
    0x01f5 => "\\'g",
    0x01fc => "\\'\\AE",
    0x01fd => "\\'\\ae",
    0x01fe => "\\'\\O",
    0x01ff => "\\'\\o",

    0x02c6 => '\\^{}',
    0x02dc => '\\~{}',
    0x02d8 => '\\u{}',
    0x02d9 => '\\.{}',
    0x02da => "\\r{",
    0x02dd => '\\H{}',
    0x02db => '\\c{}',
    0x02c7 => '\\v{}',

    0x03c0 => '\\mbox{$\\pi$}',
    # consider adding more Greek here

    0xfb01 => 'fi',
    0xfb02 => 'fl',

    0x2013 => '--',
    0x2014 => '---',
    0x2018 => '`',
    0x2019 => "'",
    0x201c => '``',
    0x201d => "''",
    0x2020 => '\\dag',
    0x2021 => '\\ddag',
    0x2122 => '\\texttrademark',
    0x2022 => '\\mbox{$\\bullet$',
    0x2026 => '\\ldots',
    0x2202 => '\\mbox{$\\partial$',
    0x220f => '\\mbox{$\\prod$',
    0x2211 => '\\mbox{$\\sum$',
    0x221a => '\\mbox{$\\surd$',
    0x221e => '\\mbox{$\\infty$',
    0x222b => '\\mbox{$\\int$',
    0x2248 => '\\mbox{$\\approx$',
    0x2260 => '\\mbox{$\\neq$',
    0x2264 => '\\mbox{$\\leq$',
    0x2265 => '\\mbox{$\\geq$'
		);
	
	$textarray=str_split($text);
	$output="";
	foreach ($textarray as $i) 
	   $output.=($latex_equivalents[ord($i)]?"{".$latex_equivalents[ord($i)]."}":$i);
	return $output;

	}

}
