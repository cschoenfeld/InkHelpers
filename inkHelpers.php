<?php
/* 
	INK HELPERS 
	
	Ink is a terrific framework for building responsive e-mails, 
	but it can be difficult to scan the HTML and figure out where you are. 
	Using PHP classes to build your rows and columns makes the code a little more readable, 
	or at least that's the intention here.

	Example usage, showing some of the functionality:	

	$row = new Row();
	$row->setClass('myRow anotherClass');
	$col1 = new Columns(8, 'left');
	$col2 = new Columns(4, 'both');
	$col1->setContent("<p>Content of my main column.</p>\n");
	$col2->setContent("<p>Content of my sidebar.</p>");
	$col1->setClass('mainColumn');
	$col1->setWrapperClass('mainColumnWrapper');
	$col2->setClass('sidebar');
	$col2->setWrapperClass('sidebarWrapper');
	$row1->addcol($col1);
	$row1->addcol($col2);
	echo $row1->render();	
		
*/

class InkElement {
	var $classes;
	var $styles;
	var $attribs;
	
	function setClass($c) {
		$this->classes = $c;
	}
	
	function setStyle($s) {
		$this->styles = $s;
	}
	
	function setAttribute($key, $val) {
		// Example: ->setAttribute('bgcolor', '#888');
		$this->attribs[$key] = $val;
	}
	
	function attributes() {
		$attStr = '';
		if (is_array($this->attribs) === true && empty($this->attribs) === false) {
			foreach ($this->attribs as $key => $val) {
				$attStr .= " $key=\"$val\"";
			}
		}
		return $attStr;
	}
}

class Row extends InkElement {
	var $columns; // array of Columns objects
	var $offset; 
	
	function __construct($off=null) {
		if (is_string($off)) {
			$this->offset = ' offset-by-' . $off;
		} else {
			$this->offset = '';
		}
	}
	
	function addcol($c) {
		$this->columns[] = $c;
	}
	
	function render() {
		if (is_array($this->columns) === false || empty($this->columns)) {
			throw new Exception('Row does not contain an array of columns.');
		}
		$out = sprintf("<table class=\"row %s\" style=\"%s\">\n", $this->classes, $this->styles);
		$out .= "\t<tr>\n";
		$numCols = count($this->columns);
		$colnum = 1;
		foreach ($this->columns as $colIndex => $col) {
			$lastClass = ($colnum == $numCols) ? ' last' : '';
			$out .= sprintf("\t\t<td class=\"wrapper%s%s %s\" style=\"%s\"%s>\n", $this->offset, $lastClass, $col->wrapperClasses, $col->wrapperStyles, $col->wrapperAttributes());
			$out .= sprintf("\t\t\t<table class=\"%s columns\">\n", $col->span);
			$out .= "\t\t\t\t<tr>\n";
			$out .= sprintf("\t\t\t\t\t<td class=\"%s %s\" style=\"%s\"%s>\n", $col->textPad, $col->classes, $col->styles, $col->attributes());
			$out .= $col->content . "\n";
			$out .= "\t\t\t\t\t</td>\n\t\t\t\t\t<td class=\"expander\"></td>\n";
			$out .= "\t\t\t\t</tr>\n";
			$out .= "\t\t\t</table>\n";
			$out .= "\t\t</td>\n\n";
			$colnum++;
		}
		$out .= "\t</tr>\n";
		$out .= "</table>\n\n\n";
		return $out;
	}
}

class Columns extends InkElement {
	var $span; // one, two ... twelve
	var $textPad; // none (default), left, right, both
	var $content; 
	var $wrapperClasses;
	var $wrapperStyles; 
	var $wrapperAttribs;
	
	function __construct($span='12', $pad='none') {
		$this->span = self::num($span);
		$this->textPad = $this->pad($pad);
	}
	
	public static function num($val) {
		switch ($val) {
			case '1': return 'one';
			case '2': return 'two';
			case '3': return 'three';
			case '4': return 'four';
			case '5': return 'five';
			case '6': return 'six';
			case '7': return 'seven';
			case '8': return 'eight';
			case '9': return 'nine';
			case '10': return 'ten';
			case '11': return 'eleven';
			case '12': return 'twelve';
		}
	}

	function setWrapperClass($c) {
		$this->wrapperClasses = $c;
	}
	
	function setWrapperStyle($s) {
		$this->wrapperStyles = $s;
	}
	
	function setContent($html) {
		$this->content = $html;
	}
	
	function appendContent($html) {
		$this->content .= $html;
	}
	
	function pad($pad) {
		switch($pad) {
			case 'left':
				$p = 'left-text-pad';
				break;
			case 'right':
				$p = 'right-text-pad';
				break;
			case 'both':
				$p = 'text-pad';
				break;
			default:
				$p = '';
		}
		return $p;
	}

	function setWrapperAttribute($key, $val) {
		// Example: ->setWrapperAttribute('bgcolor', '#888');
		$this->wrapperAttribs[$key] = $val;
	}

	function wrapperAttributes() {
		$attStr = '';
		if (is_array($this->wrapperAttribs) === true && empty($this->wrapperAttribs) === false) {
			foreach ($this->wrapperAttribs as $key => $val) {
				$attStr .= " $key=\"$val\"";
			}
		}
		return $attStr;
	}

}
	
?>