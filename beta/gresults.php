<script src="//code.jquery.com/jquery-latest.min.js"></script>
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
    gcse.async = false;
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

.gsc-cursor-box {
	text-align:center;
}


.more_results_btn { padding: 5px 23px; border: 0 none; cursor: pointer; background: rgb(199,0,0); color: white; border-radius: 10px; font-size: 20px; float:right; margin-top:-45px; float:right  }
.more_results_btn:hover { background-color: rgb(171,5,26) } 
.more_results_btn { position: absolute; right: 5px; bottom: 5px;}
 

</style>




<script>
$(document).ready(function() {                 /*
	curr = $('.gsc-cursor-current-page');
	if(!curr || curr.length == 0) {
		$('.more_results_btn').hide();
	}                                             */
});

function goNext(how) {
	//current google page number
	curr = $('.gsc-cursor-current-page');
	curr = curr.html()-1;

	//this uses array indexing
	//it makes sure you don't go off the ends
	if(curr+how > 9 || curr+how < 0) return;

	//simulate a click on the number
	$('.gsc-cursor-page')[curr+how].click();
}

</script>

<button class='more_results_btn' style='left:5px' onclick='goNext(-1)'>Prev</button>
<button class='more_results_btn' onclick='goNext(1)'>Next</button>

