<?php

/*
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 0);
error_reporting(E_ALL);
*/

//@ explicity start session  ( remove if needless )
session_start();

//@ if logoff
if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
	header("location:login.html");	//@ redirect 
} else {
	$_SESSION['exp_user']['expires'] = time()+(45*60);	//@ renew 45 minutes
}

	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin - Gomez Shopping Cart v2.0</title>
<link href="login.css" rel="stylesheet" media="all" />
</head>

<body class="oneColLiqCtr">

<div id="container">
  <div id="mainContent">
    <h1> Gomez Shopping Cart - Admin</h1>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><a href="?op=home"><img src="images/hot.png" width="32" height="32" /></a><br />
          Change Password</td>
        <td><a href="?op=add_product"><img src="images/cart_add.png" width="32" height="32" /></a><br />
        Add Product</td>
        <td><a href="?op=edit_product"><img src="images/ok.png" width="32" height="32" /></a><br />
          Edit Product</td>
      </tr>
      <tr>
        <td><a href="?op=payments"><img src="images/credit_cards.png" width="33" height="26" /></a><br />
Payments</td>
        <td><a href="?op=templates"><img src="images/photo.png" width="32" height="32" /></a><br />
          Templates</td>
        <td><a href="?op=delete_product"><img src="images/cancel.png" width="32" height="32" /></a><br />
        Delete Product</td>
      </tr>
      <tr>
        <td><a href="?op=blog"><img src="images/blog.png" width="32" height="32" /></a><br />
Blog</td>
        <td><a href="?op=categories"><img src="images/bag_green.png" width="32" height="32" /></a><br />
        Categories</td>
        <td><a href="?op=settings"><img src="images/chilli.png" width="32" height="32" /></a><br />
Settings</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
<br />

<?php

// admin


require("../config.inc.php");
require("../class/Database.class.php");

$server = $config['server'];
$user = $config['user'];
$pass = $config['pass'];
$database = $config['database'];

$db = new Database($server, $user, $pass, $database); // create the $db object
$db->connect(); // connect to the server


function changepw() {

global $db;

if(isset($_POST['submit'])) {

	if($_POST['password1'] != $_POST['password2']) {
		echo "<h3>PASSWORD FIELDS ARE WRONG!</h3>";

	} else {

	$data['userpassword'] = md5($_POST['password1']);
	$db->query_update("tbl_user", $data, "userid='1'");

	echo "<h3>Password UPDATED! [ OK ]</h3>";

	}
}

echo "    <h2>Change Password</h2>\n"; 
echo "    <p>Now you can change the default password or choose new one.</p>\n"; 
echo "    <form id=\"form1\" name=\"form1\" method=\"post\" action=\"\">\n"; 
echo "      <p>\n";
echo "        <input type=\"password\" name=\"password1\" value=\"\" />\n"; 
echo "        <br />\n"; 
echo "        Password<br />\n"; 
echo "        <input type=\"password\" name=\"password2\" value=\"\" />\n"; 
echo "        <br />\n"; 
echo "        Re-Type\n"; 
echo "      </p>\n"; 
echo "      <p>\n"; 
echo "        <input type=\"submit\" name=\"submit\" id=\"button\" value=\"Submit\" />\n"; 
echo "      </p>\n"; 
echo "    </form>\n"; 
echo "    <p>&nbsp;</p>\n";
}


function add_product() {

global $db;


if(isset($_POST['submit'])) {

      include("../class/SimpleImage.php");
      $image = new SimpleImage();
      $image->load($_FILES['uploaded_image']['tmp_name']);
      //$image->resizeToWidth(150);
      $image->save("../gallery/".$_POST['title'].".jpg");
      //$image->output();
      echo "uploading image... <br />";


$data['title'] = $_POST['title']; 
$data['image'] = $_POST['title'];
$data['shortdesc'] = $_POST['shortdesc']; 
$data['longdesc'] = $_POST['longdesc']; 
$data['cost'] = $_POST['cost']; 
$data['cat'] = $_POST['cat']; 

$db->query_insert("products", $data); 

echo "<h3>New product added [ OK ]</h3>";

}

echo "    <p>&nbsp;</p>\n"; 
echo "    <h2>Add Product</h2>\n"; 
echo "    <p>Enter details</p>\n"; 
echo "    <form action=\"?op=add_product\" method=\"post\" enctype=\"multipart/form-data\" name=\"form1\" id=\"form1\">\n"; 
echo "      <p>Title<br /> \n"; 
echo "        <input type=\"text\" name=\"title\" id=\"textfield\" /> (Characters NOT allowed: <b>\ / * : ? \" < > |</b>\n"; 
echo "        <br />\n"; 
echo "        Preview description<br />\n"; 
echo "        <input type=\"text\" name=\"shortdesc\" value=\"\" />\n"; 
echo "        <br />\n"; 
echo "        Description\n"; 
echo "        <br />\n"; 
echo "        <textarea name=\"longdesc\" value=\"\" cols=\"45\" rows=\"5\"></textarea>\n"; 
echo "        <br />\n"; 
echo "        Categorie<br />\n"; 
		$sql = "SELECT * FROM ".$db->pre."categories
			ORDER BY catid DESC";

		$countRows = $db->query($sql);

    	echo "<select size=\"1\" name=\"cat\">\n";
		while ($countRow = $db->fetch_array($countRows)) {
			echo "<option value=\"".$countRow[catname]."\">".$countRow[catname]."</option>\n";
		}
		echo "</select>\n";
echo "        <br />\n"; 
echo "        Screenshot<br />\n"; 
echo "        <input type=\"file\" name=\"uploaded_image\" id=\"fileField\" /> \n"; 
echo "        <br />\n"; 
echo "        Price<br />\n"; 
echo "        $\n"; 
echo "        <input name=\"cost\" type=\"text\" value=\"0.00\" size=\"10\" />\n"; 
echo "      USD</p>\n"; 
echo "      <p>\n"; 
echo "        <input type=\"submit\" name=\"submit\" id=\"button\" value=\"Add Product\" />\n"; 
echo "      </p>\n"; 
echo "    </form>\n";


}



function edit_product() {

global $db;

 echo "    <form action=\"?op=edit_product\" method=\"post\">\n";
 echo "ID: <input type=\"text\" name=\"id\">";
 echo "<input type=\"submit\" name=\"submit\" value=\"Edit\" />";
 echo "<input type=\"hidden\" name=\"step2\" value=\"1\" />\n"; 
 echo "    </form>\n";


if(isset($_POST['step2'])) {
$id = $_POST['id'];
 $sql = "SELECT * FROM ".$db->pre."products
          WHERE id LIKE '". $db->escape($id) ."%'";

 $countRows = $db->query($sql);


 while ($countRow = $db->fetch_array($countRows)) {


 echo "    <p>&nbsp;</p>\n"; 
 echo "    <h2>Edit products</h2>\n"; 
 echo "    <p>Enter details</p>\n"; 
 echo "    <form action=\"?op=edit_product\" method=\"post\" enctype=\"multipart/form-data\" name=\"form1\" id=\"form1\">\n"; 
 echo "      <p>Title<br /> \n"; 
 echo "        <input type=\"text\" name=\"title\" value=\"$countRow[title]\" /> (Characters NOT allowed: <b>\ / * : ? \" < > |</b>\n"; 
 echo "        <br />\n"; 
 echo "        Preview description<br />\n"; 
 echo "        <input type=\"text\" name=\"shortdesc\" value=\"$countRow[shortdesc]\" />\n"; 
 echo "        <br />\n"; 
 echo "        Description\n"; 
 echo "        <br />\n"; 
 echo "        <textarea name=\"longdesc\" cols=\"45\" rows=\"5\">$countRow[longdesc]</textarea>\n"; 
 echo "        <br />\n"; 
 echo "        Categorie<br />\n"; 
 echo "<select size=\"1\" name=\"cat\">\n";
 
 
 $cat_sql = "SELECT * FROM ".$db->pre."categories
          ORDER BY catid DESC";

$cat_countRows = $db->query($cat_sql);

while ($cat_countRow = $db->fetch_array($cat_countRows)) {
    $var_catname = $cat_countRow[catname]; // CAT SELECTED
       if($var_catname==$countRow[cat]) {
        echo "<option value=\"$cat_countRow[catname]\" SELECTED>$cat_countRow[catname] (SELECTED)</option>\n";
        } else {
        echo "<option value=\"$cat_countRow[catname]\">$cat_countRow[catname]</option>\n";  
        } 
}
     
 echo "</select>";       
 echo "        <br />\n"; 
 echo "        Screenshot: <br />\n"; 
 echo "        <img src=\"../gallery/$countRow[image].jpg\" width=\"430\" height=\"418\"/>\n"; 
 echo "        <br />\n"; 
 echo "        Price<br />\n"; 
 echo "        $\n"; 
 echo "        <input name=\"cost\" type=\"text\" value=\"$countRow[cost]\" size=\"10\" />\n"; 
 echo "      USD</p>\n"; 
 echo "      <p>\n"; 
 echo "        <input type=\"submit\" name=\"submit\" id=\"button\" value=\"Add Product\" />\n";
 echo "<input type=\"hidden\" name=\"step3\" value=\"1\" />\n"; 
 echo "<input type=\"hidden\" name=\"id\" value=\"$id\" />\n"; 
 echo "      </p>\n"; 
 echo "    </form>\n";

}
 } else if(isset($_POST['step3'])) {

$id = $_POST['id'];

 $data['title'] = $_POST['title']; 
 $data['image'] = $_POST['title'];
 $data['shortdesc'] = $_POST['shortdesc']; 
 $data['longdesc'] = $_POST['longdesc']; 
 $data['cost'] = $_POST['cost']; 
 $data['cat'] = $_POST['cat']; 



$db->query_update("products", $data, "id='$id'"); 

 echo "<h3>Product UPDATED [ OK ]</h3>";


}

}


function templates() {

global $db;


if(isset($_POST['submit'])) {

	if($_POST['select'] != "") {

	$data['template'] = $_POST['select'];
	$db->query_update("tbl_user", $data, "userid='1'");
	echo "<h3>Template CHANGED! [ OK ]</h3>";

	}

}


echo "    <h2>Choose Template</h2>\n"; 
echo "    <p>Select your template.</p>\n"; 
echo  "<form method=\"post\" action=\"?op=templates\">\n";
echo "Paste in 'templates' folder.<br />\n";
echo  "<br />\n";


// Get Default Template
$sql = "SELECT * FROM ".$db->pre."tbl_user";
$countRows = $db->query($sql);

while ($countRow = $db->fetch_array($countRows)) {
    $template = $countRow['template'];
    echo "<h3>DEFAULT TEMPLATE [ $template ]</h3>";
}

	echo "<select name=\"select\">\n";
	echo "<option value=\"\">Please Select...</option>\n";

    $count = 0;

    $templatedir = dir("../templates");
    while($func=$templatedir->read()) {
	$count++;

	// Deleting dirs "." and ".."
	if($count > 2) {
		// Print Selected Template
		if ($func == $template) 
			echo "<option value=\"$func\" SELECTED>$func</option>\n";
		 else 
			echo "<option value=\"$func\">$func</option>\n";
		
	}
	
    }

	echo "</select>";
	
    closedir($templatedir->handle);

echo  "<input type=\"submit\" name=\"submit\" id=\"button\" value=\"Change\" />\n";
echo  "</form>\n";
echo "    <p>&nbsp;</p>\n";

}


function delete_product() {
	
global $db;


if(isset($_POST['submit'])) {
$chk = $_POST['chk'];
for($i=0; $chk[$i]>=$i; $i++) {
$sql = "DELETE FROM ".$db->pre."products WHERE id=$chk[$i]";
$db->query($sql);
echo "<h3>Delete Product $chk[$i] Sucess [ OK ]</h3>";
}
}

echo "<form method=\"post\" action=\"?op=delete_product\">";
echo "<table width=\"100%\" border=\"0\">";
echo "  <tr class=\"strb\">";
echo "    <td>ID</td>";
echo "    <td>Name</td>";
echo "    <td>Select</td>";
echo "  </tr>";


$sql = "SELECT * FROM ".$db->pre."products
          ORDER BY id DESC";

$countRows = $db->query($sql);

$j = 0;
while ($countRow = $db->fetch_array($countRows)) {
$j++;
echo "  <tr>";
echo "    <td>$countRow[id]</td>";
echo "    <td>$countRow[title]</td>";
echo "    <td><input type=\"checkbox\" name=\"chk[]\" value=\"$countRow[id]\" /></td>";
echo "  </tr>";    
}
echo "  <tr>";
echo "   <td></td>";
echo "   <td></td>";
echo "   <td align=right><input type=\"submit\" name=\"submit\" value=\"Delete\" /></td>";
echo "  </tr>";   
echo "</table>";
echo "</form>";
	
}


function categories() {

global $db;

// catid 	catname 	catdesc
if(isset($_POST['submit'])) {

$data['catname'] = $_POST['catname'];
$data['catdesc'] = $_POST['catdesc'];

$db->query_insert("categories", $data); 

echo "Category Added";
}elseif(isset($_POST['delete'])) {

$cat = $_POST['cat'];

$sql = "DELETE FROM ".$db->pre."categories WHERE catname='$cat'";
$db->query($sql);

echo "Category Deleted";
}

$sql = "SELECT * FROM ".$db->pre."categories
          ORDER BY catid DESC";

$countRows = $db->query($sql);

echo "<form method=\"post\" action=\"?op=categories\">";
    	echo "<select size=\"1\" name=\"cat\">\n";
while ($countRow = $db->fetch_array($countRows)) {
	echo "<option value=\"".$countRow[catname]."\">".$countRow[catname]."</option>\n";
}
	echo "</select>\n";
	echo "<input type=\"submit\" value=\"Delete\" name=\"delete\"><br />";


echo "<p>Add category<br />* Category Name<br />* Description<br /><br />";
echo "<input type=\"text\" value=\"\" name=\"catname\" size=15> <br />";
echo "<textarea name=\"catdesc\"></textarea>";
echo "<input type=\"submit\" value=\"Add\" name=\"submit\">";
echo "</form>";
}

function payments() {

global $db;

if(isset($_GET['id'])) {
$id = $_GET['id'];
$sql = "DELETE FROM ".$db->pre."process WHERE id=$id";
$db->query($sql);
}

$sql = "SELECT * FROM ".$db->pre."process
          ORDER BY id DESC";

$countRows = $db->query($sql);

//id 	session_id 	name 	email 	country 	genre 	address 	phone 	CardNumber 	total 	total_products 	products 	status

    	echo "<table width=\"100%\">\n";
		echo "<tr class=\"strb\">\n";
		echo "<td>Name</td>\n";
		echo "<td>E-mail</td>\n";
		echo "<td>Country</td>\n";
		echo "<td>Genre</td>\n";
		echo "<td>Address</td>\n";
		echo "<td>Phone</td>\n";
		echo "<td>Card</td>\n";
		echo "<td>Total</td>\n";
		echo "<td>Total products</td>\n";
		echo "<td>Products</td>\n";
		echo "<td>Status</td>\n";
		echo "</tr>\n";
		
/* color counter */
$ccolor = 0;

while ($countRow = $db->fetch_array($countRows)) {
		$ccolor++;
		if($ccolor > 0) {
		$ccolor = "#ac1101"; // color 1
		$ccolor--;
		} else { 
		$ccolor = "#a44d44"; // color 2
		}
		echo "<tr bgcolor=\"$ccolor\">\n";
		echo "<td>$countRow[name]</td>\n";
		echo "<td>$countRow[email]</td>\n";
		echo "<td>$countRow[country]</td>\n";
		echo "<td>$countRow[genre]</td>\n";
		echo "<td>$countRow[address]</td>\n";
		echo "<td>$countRow[phone]</td>\n";
		echo "<td>$countRow[CardNumber]</td>\n";
		echo "<td>$countRow[total]</td>\n";
		echo "<td>$countRow[total_products]</td>\n";
		echo "<td>$countRow[products]</td>\n";
		echo "<td>$countRow[status]</td>\n";
		echo "<td><a href=\"?op=payments&id=$countRow[id]\">[ Complete ]</a></td>\n";
		echo "</tr>\n";
}
		echo "</table>\n";


}


function add_post() {
	
global $db;

if(isset($_POST['submit'])) {
$data['short'] = $_POST['short'];
$data['title'] = $_POST['title'];
$data['posted'] = $_POST['posted'];
echo "<b>New Entry Has Been Posted [ OK ] </b><br />";
$db->query_insert("news", $data); 
} else {
echo "<form method=\"post\" action=\"?op=add_post\">";
echo "Title";
echo "<br />";
echo "<input type=\"text\" value=\"\" name=\"title\" size=50>";
echo "<br />";
echo "Body";
echo "<br />";
echo "<textarea name=\"short\" cols=80 rows=20>";
echo "</textarea>";
echo "<br />";
echo "Posted by:";
echo "<br />";
echo "<input type=\"text\" value=\"\" name=\"posted\" size=50>";
echo "<p>";
echo "<input type=\"submit\" name=\"submit\" value=\"Add Post\">";
echo "</form>";
echo "<p>";
}
}

function delete_post() {
	
global $db;


$sql = "DELETE FROM ".$db->pre."news WHERE id=$_GET[id]";
$db->query($sql);
echo "<h3>Delete Post $_GET[id] Sucess [ OK ]</h3>";

	
}

function update_post() {
    
    
    global $db;
    
	if(isset($_POST['submit'])) {
		$data['short'] = $_POST['short'];
		$data['title'] = $_POST['title'];
		$data['posted'] = $_POST['posted'];
		$db->query_update("news", $data, "id='$_POST[id]'"); 
		echo "<b>Post Has Been Updated [ OK ] </b><br />";
	} else {

			$sql = "SELECT * FROM ".$db->pre."news
			WHERE id=$_GET[id]";

			$countRows = $db->query($sql);

			while ($countRow = $db->fetch_array($countRows)) {
				echo "<form method=\"post\" action=\"?op=update_post&id=$countRow[id]\">";
				echo "Title";
				echo "<br />";
				echo "<input type=\"text\" value=\"$countRow[title]\" name=\"title\" size=50>";
				echo "<br />";
				echo "Body";
				echo "<br />";
				echo "<textarea name=\"short\" cols=80 rows=20>";
				echo $countRow[short];
				echo "</textarea>";
				echo "<br />";
				echo "Posted by:";
				echo "<br />";
				echo "<input type=\"text\" value=\"$countRow[posted]\" name=\"posted\" size=50>";
				echo "<input type=\"hidden\" value=\"$countRow[id]\" name=\"id\">";
				echo "<p>";
				echo "<input type=\"submit\" name=\"submit\" value=\"Edit\">";
				echo "</form>";
				echo "<p>";

			}
	}    
    
}

function blog() {

global $db;

    
 
		$sql = "SELECT * FROM ".$db->pre."news ORDER BY id DESC";

		$countRows = $db->query($sql);

		echo "<table width=100%>";
		echo "<tr class=\"strb\">";
		echo "<td>Key</td>";
		echo "<td>Title</td>";
		echo "<td>Action</td>";
        echo "</tr>";

			while ($countRow = $db->fetch_array($countRows)) {

                $title = htmlentities($countRow[title]);
                echo "<tr>";
				echo "<td>";
				echo "$countRow[id]";
				echo "</td>";
				echo "<td>";
				echo "$title";
				echo "</td>";
				echo "<td>";
				echo "<a href=\"?op=update_post&id=$countRow[id]\">[ Edit | <a href=\"?op=delete_post&id=$countRow[id]\">Delete</a> ]</a>";
				echo "</td>";
                echo "</tr>";

			}
	echo "</tr>";
	echo "</table>";
	echo "<br />";
    echo "<a href=\"?op=add_post\">[ Add Post ]</a>";
	
}


function settings() {
	
global $db;

if(isset($_POST['submit'])) {
 
    if($_POST['seo'] == 1) {
    $seo = 1;
    $data['seo'] = 1;
    }else{
    $data['seo'] = 0;
    $seo = 0; 
    }
    
$db->query_update("tbl_user", $data, "userid=1");  

echo "<strong>Settings Saved [ OK ]</strong>";
    
} else {
    

$sql = "SELECT * FROM ".$db->pre."tbl_user
          WHERE userid=1";

$countRows = $db->query($sql);

while ($countRow = $db->fetch_array($countRows)) {
    $seo = $countRow[seo];
}
    
echo "<form method=\"post\" action=\"?op=settings\">";
echo "<table width=\"536\" border=\"0\">";
echo "  <tr>";
echo "    <td width=\"139\">";
echo "    SEO URLs</td>";
echo "    <td width=\"387\">";
echo "<input type=\"radio\" name=\"seo\" id=\"radio\" value=\"1\" ";
 if($seo==1){ echo "checked"; } 
echo "/>";
echo "      ON ";
echo "<input type=\"radio\" name=\"seo\" id=\"radio2\" value=\"0\" ";
 if($seo==0){ echo "checked"; } 
echo "/>";
echo "      OFF</td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td>&nbsp;</td>";
echo "    <td><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Save\" /></td>";
echo "  </tr>";
echo "</table>";
echo "    </form>";
}	
}

// admin




if(isset($_GET['op'])) {

switch($_GET['op']) {

case "delete_product":
delete_product();
break;

case "templates":
templates();
break;

case "add_product":
add_product();
break;

case "edit_product":
edit_product();
break;

case "payments":
payments();
break;

case "home": 
changepw();
break;

case "categories":
categories();
break;

case "blog":
blog();
break;

case "add_post":
add_post();
break;

case "update_post":
update_post();
break;

case "delete_post":
delete_post();
break;

case "settings":
settings();
break;

default: 
changepw();

}

}

$db->close();

?>


<p align=right><a href="?logoff=1"><img src="images/padlock.png" width="32" height="32" /></a><br />Logout</p>
  <!-- end #mainContent --></div>
<!-- end #container -->
<div id="footer">
<a href="http://www.gomezstudio.com/">Powered by Gomez Shopping Cart</a>
</div>
</div>
</body>
</html>
