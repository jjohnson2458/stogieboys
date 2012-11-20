<?php

	
	
	if(!function_exists('__autoload_global_classes')){
		function __autoload_global_classes(){
			$args = func_get_args();
			$class = $args[0];
			if(file_exists(GLOBAL_CLASSES_DIR . $class . '.php')){
				require(GLOBAL_CLASSES_DIR . $class . '.php');
			} 
		}
	}

	spl_autoload_register('__autoload_global_classes');
	
	
	
	/* *** Debug / PHP Enhancement functions ****************************** *** */
    /**
     * Redirects to the $url by http headers, javascript, and anchor-clicking,
     * in that order, passing on to the next option on failure.  If the debug
     * constant QUICK_REDIRECT is set to false, headers and javascript will be
     * bypassed, leaving only a link to click for debugging purposes
     */
    if (!defined("OFFSET")){
		define("OFFSET",0); ## to put server on Local time
	}

if(!function_exists('redirect')){	
	function redirect($url){
        if(QUICK_REDIRECT !== false){
            @header( "Location: {$url}" );
            print "<script type='text/javascript'>window.location = '{$url}';</script>";
        }
        print "<span style='font:12px verdana'>";
        print "Please click here to continue: <a href='{$url}'>Continue!</a>\n";
		print "</span>";
        die;
    }
}

if(!function_exists('xmlentities')){	
    function xmlentities($string, $quote_style=ENT_QUOTES){
   		static $trans;
		   if (!isset($trans)) {
			   $trans = get_html_translation_table(HTML_ENTITIES, $quote_style);
			   foreach ($trans as $key => $value)
				   $trans[$key] = '&#'.ord($key).';';
			   // dont translate the '&' in case it is part of &xxx;
			   $trans[chr(38)] = '&';
		   }
	   // after the initial translation, _do_ map standalone '&' into '&#38;'
	   return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/","&#38;" , strtr($string, $trans));
	}		
}

if(!function_exists('pagination')){		
		function pagination ($total_rows,$per_page,$PAGES_PER_PAGE,$request_url=""){ ## <-- very cool app :-)
		#function ToHtml(total_rows){
		#$html = CDBList::ToHtml();
		$request_url=($request_url!="")  ? $request_url : $_SERVER['REQUEST_URI'] ;
		$paginationCode = '';
		if ($per_page) {
			$page = (!isset($_GET['page']))? 1 : $_GET['page'];
			$prev = ($page - 1);
			$next = ($page + 1);
			$max_records = $per_page;
			$from = ($page - 1) * $max_records;
			$total_result = $total_rows;
			$total_pages = ceil($total_result / $max_records);
			$ppp = $PAGES_PER_PAGE;
			if ($total_pages > 1 && $ppp && $ppp < $total_pages) {
				if ($page > $ppp) {
					$first_page = $page;
				} else {
					$first_page = 1;
				}
				$last_page = $ppp + $first_page;
				if ($last_page > $total_pages) {
					$last_page = $total_pages;
					$first_page = $last_page - $ppp;
				}
			} else {
				$first_page = 1;
				$last_page = $total_pages;
			}
			$prev_shown = false;
			$next_shown = false;
			$generic_uri = Http::addGet($request_url, "page=PAGEREPLACE");
			$extra_data = "";
			if ($pass_key) {
				foreach ($pass_key as $key => $value) {
					if (is_array($value)) {
						foreach ($value as $getValue) {
							$extra_data .= "&" . urlencode($key) . "=" . $getValue;
						}
					} else {
						$generic_uri = Http::addGet($generic_uri, "$key=$value");
					}
				}
				$generic_uri .= $extra_data;
			}
			if ($first_page > 1) {
				$prev_pages = $page - $ppp;
				$prev_shown = true;
			}
			if ($last_page != $total_pages) {
				$next_pages = $page + $ppp;
				$next_shown = true;
			}
			if(($total_pages > 1) && ($page > 1)) {
				$uri = str_replace('PAGEREPLACE', $prev, $generic_uri);
				$paginationCode .= '<a class="page-prev" href="'.$uri.'">&laquo; Previous</a>';
			}
			if ($total_pages > 1) {
				for($i = $first_page; $i <= $last_page; $i++) {
					if(($page) == $i) {
						$paginationCode .= ' <span class="page-num">'.$i.'</span> ';
					} else {
						$uri = str_replace('PAGEREPLACE', $i, $generic_uri);
						$paginationCode .= ' <a href="'.$uri.'" class="page-link">'.$i.'</a> ';
					}
				}
			}
			if(($total_pages > 1) && ($page < $total_pages)) {
				$uri = str_replace('PAGEREPLACE', $next, $generic_uri);
				$paginationCode .= '<a class="page-next" href="';
				$paginationCode .= $uri . '">Next &raquo;</a>';
			}
			$paginationCode .= "<br /><br />";
			if ($prev_shown) {
				$uri = str_replace('PAGEREPLACE', '1', $generic_uri);
				$paginationCode .= '<a class="page-first" href="'.$uri.'">First Page&nbsp;|&nbsp;</a>';
				$uri = str_replace('PAGEREPLACE', $prev_pages, $generic_uri);
				$paginationCode .= '<a class="page-prev" href="'.$uri.'">Previous. '.$ppp.' Pages</a>';
			}
			if ($next_shown) {
				if ($prev_shown) $paginationCode .= '&nbsp;|&nbsp;';
				$uri = str_replace('PAGEREPLACE', $next_pages, $generic_uri);
				$paginationCode .= '<a class="page-next" href="'.$uri.'">Next '.$ppp.' Pages&nbsp;|&nbsp;</a> ';
				$uri = str_replace('PAGEREPLACE', "$total_pages", $generic_uri);
				$paginationCode .= '<a class="page-last" href="'.$uri.'">Last Page</a>';
			}
			if ($prev_shown || $next_shown)	$paginationCode .= "<br><br>";
			$min = ($page - 1) * $per_page + 1;
			$max = min($page * $per_page, $total_rows);
			$paginationCode .= "<div class=\"page-showing\">Showing records $min-$max of {$total_rows} ($total_pages pages total).</div>";
		}
		$html .= $paginationCode . "<br />";
		return $html; 
	}
}