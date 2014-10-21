<?php

//alow for variable number of results
$num = 3;
if(array_key_exists('num', $_GET)) {
	$num = intval($_GET['num']);
}

?>

<script>
  (function() {
    var cx = '006315996676710339076:ikprkfw5r20';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only refinementStyle='link' webSearchResultSetSize='<?= $num ?>'></gcse:searchresults-only>                          


<style type='text/css'>

/* Do no display the count of search results */    
.gsc-result-info {
	display: none;
}

/* Hide the Google branding in search results */
.gcsc-branding {
	display: none; 
}

.gsc-thumbnail {
	display: none;
}

.gs-snippet { 
	/*display: none;  */
}

/* Change the font size of the title of search results */
.gs-title a { 
	/*	font-size: 16px; */ 
}

/* Change the font size of snippets inside search results */
.gs-snippet {
	/*	font-size: 14px;  */
}

/* Google Custom Search highlights matching words in bold, toggle that */
.gs-title b, .gs-snippet b {
	/*	font-weight: normal;   */
}

/* Do no display the URL of web pages in search results */
.gsc-url-top, .gsc-url-bottom {
	/*display: none; */
}

/* Highlight the pagination buttons at the bottom of search results */
.gsc-cursor-page {
	/*	font-size: 1.5em;
	padding: 4px 8px;
	border: 2px solid #ccc;   */
}

/* hide things we don't want */
.gs-per-result-labels, .gsc-refinementsArea, .gsc-adBlock, .gsc-above-wrapper-area {
	display: none;
}

</style>






