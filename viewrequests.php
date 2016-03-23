<?php
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();
stdhead("Requests Page");

$categ = $_GET["category"];
$requestorid = $_GET["requestorid"];
$sort = $_GET["sort"];
$search = $_GET["search"];
$filter = $_GET["filter"];
$search = " AND requests.request like '%$search%' ";
if ($sort == "votes")
$sort = " order by hits desc ";
else if ($sort == "request")
$sort = " order by request ";
else
$sort = " order by added desc ";
if ($filter == "true")
$filter = " AND requests.filledby = 0 ";
else
$filter = "";

if ($requestorid <> NULL)
{
if (($categ <> NULL) && ($categ <> 0))
 $categ = "WHERE requests.cat = " . $categ . " AND requests.userid = " . $requestorid;
else
 $categ = "WHERE requests.userid = " . $requestorid;
}

else if ($categ == 0)
$categ = '';
else
$categ = "WHERE requests.cat = " . $categ;

$res = mysql_query("SELECT count(requests.id) FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id  $categ $filter $search") or die(mysql_error());
$row = mysql_fetch_array($res);
$count = $row[0];


$perpage = 50;

 list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" . "category=" . $_GET["category"] . "&sort=" . $_GET["sort"] . "&" );

$res = mysql_query("SELECT users.downloaded, users.uploaded, users.username, requests.filled, requests.filledby, requests.id, requests.userid, requests.request, requests.added, requests.hits, categories.name as cat FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id  $categ $filter $search $sort $limit") or sqlerr();
$num = mysql_num_rows($res);

//print("<table class=frame width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=frame colspan=2>");
begin_frame("Request",true,5,750);
print("<form method=\"get\" action=\"viewrequests.php\">");
?>
<select name="category">
<option value="0">(Show All)</option>
<?

$cats = genrelist();
$catdropdown = "";
foreach ($cats as $cat) {
   $catdropdown .= "<option value=\"" . $cat["id"] . "\"";
   $catdropdown .= ">" . htmlspecialchars($cat["name"]) . "</option>\n";
}

?>
<?= $catdropdown ?>
</select>
<?
print("<input type=\"submit\" align=\"middle\" value=\"Change\" />\n");
print("</form>\n<p></p>");

print("<form method=\"get\" action=\"viewrequests.php\">"); print("<b>Search </b><input type=\"text\" size=\"40\" name=\"search\" />"); print(" <input 
type=\"submit\" align=\"middle\" value=\"Search\" />\n"); print("</form><br/>"); 
print("<a href=\"requests.php\">Make a request</a>&nbsp;|&nbsp;<a href=\"viewrequests.php?requestorid=$CURUSER[id]\">View my requests</a> | <a href=\"". $_SERVER[PHP_SELF] ."?category=" . $_GET[category] . "&amp;sort=" . $_GET[sort] . "&amp;filter=true\">Hide Filled</a>"); echo $pagertop;
//print("<table border=2 width=100% cellspacing=0 cellpadding=2>\n"); 
print("<form method=\"post\" action=\"takedelreq.php\">"); 
begin_table(true); 
print("<tr><td class=\"rowhead\" width=\"48\" align=\"center\">Type</td><td class=\"rowhead\" align=\"left\"><a href=\"". $_SERVER[PHP_SELF] ."?category=" . $_GET[category] . "&amp;filter=" . $_GET[filter] . "&amp;sort=request\"><b>Name</b></a>&nbsp;/&nbsp;<a href=\"" . $_SERVER[PHP_SELF] ."?category=" . $_GET[category] . "&amp;filter=" . $_GET[filter] . "&amp;sort=added\"><b>Added</b></a></td><td class=\"rowhead\" align=\"center\">Added By</td><td class=\"rowhead\" align=\"center\">Filled?</td><td class=\"rowhead\" align=\"center\"><a href=\"" . $_SERVER[PHP_SELF] . "?category=" . $_GET[category] . "&amp;filter=" . $_GET[filter] . "&amp;sort=votes\"><b>Votes</b></a></td><td class=\"rowhead\" align=\"center\">Del</td></tr>\n"); for ($i = 0; $i < $num; ++$i) { $arr = mysql_fetch_assoc($res); if ($arr["downloaded"] > 0) { $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2); $ratio = "<font color=" . get_ratio_color($ratio) . "><b>$ratio</b></font>"; } else if ($arr["uploaded"] > 0) $ratio = "Inf."; else $ratio = "---";

$cros = mysql_query("SELECT image FROM categories WHERE name='$arr[cat]'");
if (mysql_num_rows($cros) == 1)
{
  $corr = mysql_fetch_assoc($cros);
  $category = "<img src=\"pic/$corr[image]\" alt=\"\" />";
}


$res2 = mysql_query("SELECT username from users where id=" . $arr[filledby]);
$arr2 = mysql_fetch_assoc($res2);
if ($arr2[username])
$filledby = $arr2[username];
else
$filledby = " ";
$addedby = "<td class=\"row1\" align=\"center\"><a href=\"userdetails.php?id=$arr[userid]\"><b>$arr[username] ($ratio)</b></a></td>";
$filled = $arr[filled];
if ($filled)
$filled = "<a href=\"$filled\"><font color=\"green\"><b>Yes</b></font></a>";
else
$filled = "<a href=\"reqdetails.php?id=$arr[id]\"><font color=\"red\"><b>No</b></font></a>";
$dispname = htmlspecialchars($arr["request"]);
 print("<tr><td width=\"48\" class=\"row1\" align=\"center\">$category</td><td class=\"row1\" align=\"left\">&nbsp;<a href=\"reqdetails.php?id=$arr[id]\"><b>$dispname</b></a><br/>&nbsp;<em>$arr[added]</em></td>" .
 "$addedby<td align=\"center\" class=\"row1\">$filled<br/><a href=\"userdetails.php?id=$arr[filledby]\"><b>$arr2[username]</b></a></td><td align=\"center\" class=\"row1\"><a href=\"votesview.php?requestid=$arr[id]\"><b>$arr[hits]</b></a></td><td class=\"row1\" align=\"center\"><input type=\"checkbox\" name=\"delreq[]\" value=\"" . $arr[id] . "\" /></td></tr>\n");
}

print("</table>\n");
print("<p align=\"right\"><input type=\"submit\" value=\"Delete\" /></p>");
print("</form>");
print("</td></tr><tr><td class=\"frame\" colspan=\"2\" align=\"right\">$pagerbottom");
//print("</table>");
end_frame();
stdfoot();
?>
