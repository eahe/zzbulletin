<?php 
//note: variable p refers to page

/* Setup vars for query. */
$targetpage = "avatarsLocal.php";
$limited = $limit3;

if(isset($p))
$start = ($p - 1) * $limit3; // "first item to display on this p."
else
$start = 0; // "if no p var is given, set start to 0."

/* Setup p vars for display. */
if($p == 0) $p = 1; // "if no p var is given, default to 1."
$prev = $p - 1; // "previous p is p - 1."
$next = $p + 1; // "next p is p + 1."
$lpm1 = $lastpage - 1; // "last p minus 1."

/*
"Now we apply our rules and draw the pagination object.
We're actually saving the code to a variable in case we want to draw it more than once."
*/
$pagination = "";

if($lastpage > 1){
	$pagination .= "<div class=\"pagination\">";
	//previous button
	if($p > 1)
	$pagination.= "<a href=\"$targetpage?p=$prev\">previous</a>";
	else
	$pagination.= "<span class=\"disabled\">previous</span>";

	// "pages."
	if($lastpage < 7 + ($adjacents * 2)) // "not enough pages to bother breaking it up."
	{
		for($counter = 1; $counter <= $lastpage; $counter++){
			if($counter == $p)
			$pagination.= "<span class=\"current\"><a href=\"$targetpage?p=$counter\">$counter</a></span>";
			else
			$pagination.= "<a href=\"$targetpage?p=$counter\">$counter</a>";
		}
	}
	elseif($lastpage > 5 + ($adjacents * 2)) // "enough pages to hide some."
	{
		// "close to beginning; only hide later pages."
		if($p < 1 + ($adjacents * 2)){
			for($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage?p=$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage?p=$counter\">$counter</a>";
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage?p=$lpm1\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage?p=$lastpage\">$lastpage</a>";
		}
		//in middle; hide some front and some back
		elseif($lastpage - ($adjacents * 2) > $p && $p > ($adjacents * 2)){
			$pagination.= "<a href=\"$targetpage?p=1\">1</a>";
			$pagination.= "<a href=\"$targetpage?p=2\">2</a>";
			$pagination.= "...";
			for($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage?p=$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage?p=$counter\">$counter</a>";
			}
			$pagination.= "...";
			$pagination.= "<a href=\"$targetpage?p=$lpm1\">$lpm1</a>";
			$pagination.= "<a href=\"$targetpage?p=$lastpage\">$lastpage</a>";
		}
		// "close to end; only hide early pages."
		else{
			$pagination.= "<a href=\"$targetpage?p=1\">1</a>";
			$pagination.= "<a href=\"$targetpage?p=2\">2</a>";
			$pagination.= "...";
			for($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
				if($counter == $p)
				$pagination.= "<span class=\"current\"><a href=\"$targetpage?p=$counter\">$counter</a></span>";
				else
				$pagination.= "<a href=\"$targetpage?p=$counter\">$counter</a>";
			}
		}
	}

	//next button
	if($p < $counter - 1)
	$pagination.= "<a href=\"$targetpage?p=$next\">next</a>";
	else
	$pagination.= "<span class=\"disabled\">next</span>";
	$pagination.= "</div>\n";
}

echo "</tr></table>";

echo "<br>";
echo $pagination;

?>



