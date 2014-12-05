<?php

 error_reporting (E_ALL ^ E_NOTICE); 

require("config.inc.php"); // I'm using a separate config file. so pull in those values
require("class/Database.class.php"); // pull in the file with the database class

$server = $config['server'];
$user = $config['user'];
$pass = $config['pass'];
$database = $config['database'];

$total_products = 0;

$db = new Database($server, $user, $pass, $database); // create the $db ojbect

$db->connect(); // connect to the server


function shop_index() {

global $db, $server, $user, $pass, $database, $seo;

//shop

		$sql = "SELECT * FROM ".$db->pre."products
        	ORDER BY id DESC"; //Connect to mysql db


		include("class/SimpleImage.php"); // IMG Thumb Class
		$image = new SimpleImage();

		include("class/ps_pagination.php");
		//Create a PS_Pagination object
		$pager = new PS_Pagination($db,$sql,5,3,"shop",$seo);

		$rows = $pager->paginate();
			while ($row = $db->fetch_array($rows)) {

?>

<form method=post action="<?=_ADD?>" name="frm_add_to_cart" class="niceform">
<div class="button">
<input type="submit" value="Add to cart" name="add_to_cart" id="submit">
</div>
<div class="thumb">
<?=$row['title']?>
<br />
<?php
		// Create IMG Thumb if isnt exist and resize
		if(!file_exists("gallery/$row[image]2.jpg")) {
			$image->load("gallery/$row[image].jpg");
   			$image->resize(150,95);
   			$image->save("gallery/$row[image]2.jpg");
		}
		// get image size
		if(file_exists("gallery/$row[image].jpg")) {
		      list($width, $height, $type, $attr) = getimagesize("gallery/$row[image].jpg");
        }
?>
<a href="img-<?=$row[image]?>.jpg" target="_blank" onClick="window.open(this.href, this.target, 'width=<?=$width?>,height=<?=$height?>'); return false;">
<?php
if(empty($row['image'])) {
?>
<img src="gallery/no_preview.png" alt="<?=$row[id]?>" width="140" height="110" target="_blank">
<?php
} else {    
?>
<img src="gallery/<?=$row['image']?>2.jpg" alt="<?=$row[id]?>" width="140" height="110" target="_blank">
<?php
}
?>
</a>
<br />
<div class="phototitle">
<?php
			// get image size, ext and type
			list($width, $height, $type, $attr) = getimagesize("gallery/$row[image].jpg");
			//echo $width, $height, $type, $attr;
			echo "($width x $height) <br />";
?>
<?=$row['shortdesc']?>
<br />
<?=$row['longdesc']?>
<br />
<div class="ID"><p>id.<?=$row['id']?></p></div>
<div class="price"><p>$<?=$row['cost']?> USD</p></div>
</div>
<input type=hidden name="product_id" value="<?=$row[id]?>">
<input type=hidden name="session_id" value="<?=$_SERVER[REMOTE_ADDR]?>">
<input type=hidden name="title" value="<?=$row[title]?>">
<input type=hidden name="cost" value="<?=$row[cost]?>">
</div>
</form>


<?php          

			} // end while

	echo "<div class=\"navbar\"><p>" . $pager->renderFullNav() . "</p></div>\n";

// end function shop
}

function links() {
	echo "<a href=\""._INDEX."\">Home</a><br />";
	echo "<a href=\""._BLOG."\">Blog</a><br />";
} // end function links

function checkout() {
    
    include("functions/creditcard.php");
    include("functions/check_blank.php");
    
    global $db, $server, $user, $pass, $database;
    
 if(!empty($_POST['name']) && (!empty($_POST['email'])) && (!empty($_POST['country'])) && (!empty($_POST['address'])) && (!empty($_POST['phone'])) && (checkCreditCard ($_POST['CardNumber'], $_POST['CardType'], $ccerror, $ccerrortext))) {
        $ccerrortext = "<p class=\"ok\">This card has a valid format</p>";
        //echo $ccerrortext;
		
		
$sql = "SELECT id, product_id, session_id, title, cost, type FROM stock 
          WHERE session_id LIKE '". $db->escape($_POST[session_id]) ."%' 
          ORDER BY id DESC 
          LIMIT 0,10"; 

$countRows = $db->query($sql); 
$total_products = mysql_num_rows($countRows);

while ($countRow = $db->fetch_array($countRows)) { 


$session_id = $countRow[session_id];
$title = $countRow[title] . "," . $countRow[title];
$cost = $countRow[cost];
$total = $cost+$total;
$total_products = $total_products + 1;
//$client_info

} 

$data['session_id'] = $session_id;
$data['name'] = "$_POST[name]";
$data['email'] = "$_POST[email]";
$data['country'] = "$_POST[country]";
$data['genre'] = "$_POST[genre]";
$data['address'] = "$_POST[address]";
$data['phone'] = "$_POST[phone]";
$data['CardNumber'] = "$_POST[CardNumber]";
$data['total'] = $total;
$data['total_products'] = $total_products;
$data['products'] = $title;
$data['status'] = "pending";

$db->query_insert("process", $data); 

echo "<p class=ok>Your payment has been accepted. We are contact in 24 hrs.</p>";

 } else {
    
    
$sql0 = "SELECT id, product_id, session_id, title, cost, type FROM stock 
          WHERE session_id LIKE '". $db->escape($_SERVER[REMOTE_ADDR]) ."%' 
          ORDER BY product_id DESC
	  LIMIT 0, 10"; 

$countRows0 = $db->query($sql0); 


while ($countRow0 = $db->fetch_array($countRows0)) { 
	  $total = $total+$countRow0[cost];
	  $products = $countRow0[product_id];
	  $title = $$countRow0[title];
} 

?>

<!-- <PAYMENT FORM> -->
<form method=post action="index.php?op=checkout" class="niceform">
	  	  <input type=hidden name="session_id" value="<?=$_SERVER[REMOTE_ADDR]?>">
		  	  	  <input type=hidden name="buy" value="1"> 
Personal Info
  <table width="100%" height="366" border="0">
    <tr>
      <td width="155">Your Name:</td>
      <td width="389"><input name="name" type="text" size="30" />
    </td>
    </tr>
    <tr><td>      <?php if(isset($_POST['submitted'])) if(check_blank($_POST['name'])) echo "<p class=\"wrong\">Type your name</p>"; ?></td></tr>
    <tr>
      <td>E-mail</td>
      <td><input name="email" type="text" size="30" />
      </td>
    </tr>
    <tr><td><?php if(isset($_POST['submitted'])) if(check_blank($_POST['email'])) { echo "<p class=\"wrong\"><p class=\"wrong\">Type your email</p>"; } ?></td></tr>
    <tr>
      <td>Contry</td>
      <td><select size="1" name="country" id="country">
<option value="">Country...</option>
<option value="Afganistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bonaire">Bonaire</option>
<option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Brazil">Brazil</option>
<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
<option value="Brunei">Brunei</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Canary Islands">Canary Islands</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Channel Islands">Channel Islands</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Christmas Island">Christmas Island</option>
<option value="Cocos Island">Cocos Island</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo">Congo</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote DIvoire">Cote D'Ivoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Curaco">Curacao</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="East Timor">East Timor</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Falkland Islands">Falkland Islands</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="French Guiana">French Guiana</option>
<option value="French Polynesia">French Polynesia</option>
<option value="French Southern Ter">French Southern Ter</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Great Britain">Great Britain</option>
<option value="Greece">Greece</option>
<option value="Greenland">Greenland</option>
<option value="Grenada">Grenada</option>
<option value="Guadeloupe">Guadeloupe</option>
<option value="Guam">Guam</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Hawaii">Hawaii</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Isle of Man">Isle of Man</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Korea North">Korea North</option>
<option value="Korea Sout">Korea South</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macau">Macau</option>
<option value="Macedonia">Macedonia</option>
<option value="Madagascar">Madagascar</option>
<option value="Malaysia">Malaysia</option>
<option value="Malawi">Malawi</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Martinique">Martinique</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mayotte">Mayotte</option>
<option value="Mexico">Mexico</option>
<option value="Midway Islands">Midway Islands</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montserrat">Montserrat</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Nambia">Nambia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherland Antilles">Netherland Antilles</option>
<option value="Netherlands">Netherlands (Holland, Europe)</option>
<option value="Nevis">Nevis</option>
<option value="New Caledonia">New Caledonia</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Niue">Niue</option>
<option value="Norfolk Island">Norfolk Island</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau Island">Palau Island</option>
<option value="Palestine">Palestine</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Phillipines">Philippines</option>
<option value="Pitcairn Island">Pitcairn Island</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
<option value="Republic of Montenegro">Republic of Montenegro</option>
<option value="Republic of Serbia">Republic of Serbia</option>
<option value="Reunion">Reunion</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="St Barthelemy">St Barthelemy</option>
<option value="St Eustatius">St Eustatius</option>
<option value="St Helena">St Helena</option>
<option value="St Kitts-Nevis">St Kitts-Nevis</option>
<option value="St Lucia">St Lucia</option>
<option value="St Maarten">St Maarten</option>
<option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
<option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
<option value="Saipan">Saipan</option>
<option value="Samoa">Samoa</option>
<option value="Samoa American">Samoa American</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome & Principe">Sao Tome &amp; Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Tahiti">Tahiti</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Togo">Togo</option>
<option value="Tokelau">Tokelau</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Erimates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States of America">United States of America</option>
<option value="Uraguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City State">Vatican City State</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
<option value="Wake Island">Wake Island</option>
<option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
<option value="Yemen">Yemen</option>
<option value="Zaire">Zaire</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
      </select>
     </td>
    </tr>
    <tr><td><?php if(isset($_POST['submitted'])) if(check_blank($_POST['country'])) { echo "<p class=\"wrong\">Select your country</p>"; } ?></td></tr>
    <tr>
      <td>Genre</td>
      <td><select size="1" name="genre" id="select">
        <option value="Boy">Boy</option>
        <option value="Girl">Girl</option>
      </select></td>
    </tr>
    <tr>
      <td>Adress</td>
      <td><input name="address" type="text" size="50" />
      </td>
    </tr>
    <tr><td><?php if(isset($_POST['submitted'])) if(check_blank($_POST['address'])) { echo "<p class=\"wrong\">Type your adress</p>"; } ?></td></tr>
    <tr>
      <td>Phone</td>
      <td><input name="phone" type="text" size="30" />
      </td>
    </tr>
    <tr><td><?php if(isset($_POST['submitted'])) if(check_blank($_POST['phone'])) { echo "<p class=\"wrong\">Type your phone number</p>"; } ?></td></tr>
    <tr>
      <td colspan="2">Payment Info</td>
      </tr>
    <tr>
      <td>Select      Credit Card</td>
      <td><select size="1" name="CardType">
        <option value="MasterCard">MasterCard</option>
        <option value="American Express">American Express</option>
        <option value="Carte Blanche">Carte Blanche</option>
        <option value="Diners Club">Diners Club</option>
        <option value="Discover">Discover</option>
        <option value="Enroute">enRoute</option>
        <option value="JCB">JCB</option>
        <option value="Maestro">Maestro</option>
        <option value="MasterCard">MasterCard</option>
        <option value="Solo">Solo</option>
        <option value="Switch">Switch</option>
        <option value="Visa">Visa</option>
        <option value="Visa Electron">Visa Electron</option>
        <option value="LaserCard">Laser</option>
      </select>
        </td>
    </tr>
    <tr><td>
      <?php
      if (checkCreditCard ($_POST['CardNumber'], $_POST['CardType'], $ccerror, $ccerrortext)) {
       $ccerrortext = "<p class=\"ok\">This card has a valid format</p>";
	   echo $ccerrortext;
	   } else {
	   echo $ccerrortext;
	   }
      ?></td></tr>
    <tr>
      <td>Number</td>
      <td><input type="text" name="CardNumber" maxlength="24" size="24" value="454554" title="Enter credit card number here" tabindex="12" />
      </span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="submitted" id="button" value="Submit" /></td>
    </tr>
  </table>
</form>
<!-- </PAYMENT FORM> -->

<?php
 } // else
} // end finish

    
function step2() {

global $db, $server, $user, $pass, $database;

		if(isset($_POST['clean_my_cart'])) {
		// delete a specific entry
		$sql = "DELETE FROM ".$db->pre."stock WHERE session_id='$_SERVER[REMOTE_ADDR]'"; 
		$db->query($sql); 
		shop_index();
		}  else {

	$total = 0;

	$sql3 = "SELECT * FROM ".$db->pre."stock  
          	WHERE session_id LIKE '". $db->escape($_SERVER[REMOTE_ADDR]) ."%' 
          	ORDER BY product_id DESC"; 

	$countRows3 = $db->query($sql3); 

?>

	<form method="post" action="<?=_CHECKOUT?>" class="niceform">

<?php
	while ($countRow3 = $db->fetch_array($countRows3)) { 
?>

		<div class="stock">
        <?=$countRow3[title]?>
        </div>
        <div class="stock_price">
        $<?=$countRow3[cost]?>
        </div>
        <br />
		<input type=hidden name="product_id" value="<?=$countRow3[id]?>">
		<input type=hidden name="p_session_id" value="<?=$countRow3[session_id]?>">
		<input type=hidden name="title" value="<?=$countRow3[title]?>">
		<input type=hidden name="cost" value="<?=$countRow3[cost]?>">

<?php
	  	$total = $total+$countRow3[cost];
	} 

	$pro = "SELECT id, product_id, session_id, title, cost, type FROM ".$db->pre."stock  
          	WHERE session_id LIKE '". $db->escape($_SERVER[REMOTE_ADDR]) ."%' 
          	ORDER BY title DESC"; 

	$tl = $db->query($pro); 

	$total_products = mysql_num_rows($tl);

?>

		<div class="stock">
        (<?=$total_products?> products)
        </div>
        <div class="stock_price">
        Total: <b>$<?=$total?> 	 USD </b>
        </div>
        <br />
        
	<br />
        	<b>ESTA APUNTO DE TERMINAR SU COMPRA PROPORCIONE SUS DATOS EN LA PAGINA SIGUIENTE.</b>
    <p align=right>
	<input name="buy" type="submit" value="FInish!" />
	<input name="function" type="hidden" value="step2" />
    </p>
    
        
	</form>

<?php
	}
} // end function step2

function basket() {

global $db, $total_products;

		if(isset($_POST['add_to_cart']))  {
		$data['product_id'] = $_POST['product_id']; // query_insert() will auto escape it for us 
		$data['session_id'] = $_POST['session_id'];
		$data['title'] = $_POST['title'];
		$data['cost'] = $_POST['cost'];

		$db->query_insert("stock", $data); // insert a new record using query_insert() 

		echo "<div class=\"nadd\">New product added to your Shopping Cart Basket&nbsp;\n<b>" . $_POST['title'] . "</b></div><br />\n";

		}

// contador
$ip = $_SERVER['REMOTE_ADDR'];
		if(!isset($_POST['clean_my_cart']))  {
		$sql0 = "SELECT id, product_id, session_id, title, cost, type FROM ".$db->pre."stock 
          	WHERE session_id LIKE '". $db->escape($ip) ."%' 
          	ORDER BY id DESC"; 

		$countRows = $db->query($sql0); 
		$total_products = mysql_num_rows($countRows);
		}
?>

<div id="basket">

<b><?=$total_products?></b> items in your basket
<br />
<br />
<form method="post" action="<?=_STEP2?>" class="niceform">
<input type="submit" name="step2" value="Checkout" />
<input type="submit" name="clean_my_cart" value="Clear" />
</form>

</div>

<?php


} // end function basket



function categories() {

global $db, $seo;

$name = $_GET['name']; 

$sql = "SELECT * FROM ".$db->pre."categories ORDER BY catid ASC";
$countRows = $db->query($sql);

while ($countRow = $db->fetch_array($countRows)) {
$sql2 = "SELECT * FROM ".$db->pre."products WHERE cat='$countRow[catname]'";
$countRows2 = $db->query($sql2);
$count = mysql_num_rows($countRows2);
$name_url = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>",$countRow['catname']);
    if($seo) 
    echo "<a href=\"cat-name-$name_url.html\">$countRow[catname]</a> <br /> $countRow[catdesc] ($count)<br />\n";
    else
    echo "<a href=\"index.php?op=cat&name=$name_url\">$countRow[catname]</a> <br /> $countRow[catdesc] ($count)<br />\n";
}

} // end function categories

function cat() {

global $db, $server, $user, $pass, $database, $seo;

$name = $_GET['name']; 
$name_url = ereg_replace("_", " ",$name);
    
		$sql = "SELECT * FROM ".$db->pre."products
        	WHERE cat='$name_url' ORDER BY id DESC"; //Connect to mysql db


		include("class/SimpleImage.php"); // IMG Thumb Class
		$image = new SimpleImage();

		include("class/ps_pagination.php");
		//Create a PS_Pagination object
        
    if($seo) {
        $pager = new PS_Pagination($db,$sql,5,3,"test",$seo);
    } else {
        $pager = new PS_Pagination($db,$sql,5,3,"test",$seo);
	}	

		$rows = $pager->paginate();
			while ($row = $db->fetch_array($rows)) {

?>

<form method=post action="<?=_ADD?>" name="frm_add_to_cart" class="niceform">
<div class="button">
<input type="submit" value="Add to cart" name="add_to_cart" id="submit">
</div>
<div class="thumb">
<?=$row[title]?>
<br />
<?php
		// Create IMG Thumb if isnt exist and resize
		if(!file_exists("gallery/$row[image]2.jpg")) {
			$image->load("gallery/$row[image].jpg");
   			$image->resize(150,95);
   			$image->save("gallery/$row[image]2.jpg");
		}
		// get image size
		list($width, $height, $type, $attr) = getimagesize("gallery/$row[image].jpg");
?>
<a href="img-<?=$row[image]?>.jpg" target="_blank" onClick="window.open(this.href, this.target, 'width=<?=$width?>,height=<?=$height?>'); return false;">
<img src="gallery/<?=$row[image]?>2.jpg" alt="<?=$row[id]?>" width="140" height="110" target="_blank">
</a>
<br />
<div class="phototitle">
<?php
			// get image size, ext and type
			list($width, $height, $type, $attr) = getimagesize("gallery/$row[image].jpg");
			//echo $width, $height, $type, $attr;
			echo "($width x $height) <br />";
?>
<?=$row[shortdesc]?>
<br />
<?=$row[longdesc]?>
<br />
<div class="ID"><p>id.<?=$row[id]?></p></div>
<div class="price"><p>$<?=$row[cost]?> USD</p></div>
</div>
<input type=hidden name="product_id" value="<?=$row[id]?>">
<input type=hidden name="session_id" value="<?=$_SERVER[REMOTE_ADDR]?>">
<input type=hidden name="title" value="<?=$row[title]?>">
<input type=hidden name="cost" value="<?=$row[cost]?>">
</div>
</form>


<?php          

			} // end while

	echo "<div class=\"navbar\"><p>" . $pager->renderFullNav() . "</p></div>\n";


} // end function categories

function add_to_cart() {

global $db, $server, $user, $pass, $database;



} // end function add_to_cart





function blog() {

global $db, $server, $user, $pass, $database, $seo;


	//Include the PS_Pagination class
	include("class/ps_pagination.php");

	echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"3\" align=center width='100%'><tr>";

	//$sql = 'SELECT * FROM news ORDER BY id DESC';
	$sql = "SELECT * FROM ".$db->pre."news
           ORDER BY id DESC"; 
          
	//Create a PS_Pagination object
	$pager = new PS_Pagination($db,$sql,5,5,"blog",$seo);
	



	//The paginate() function returns a mysql result set 
	$rs = $pager->paginate();

	echo "<table>";

	while($row = mysql_fetch_assoc($rs)) {

	echo "<tr><td align=left>
          <p><b>$row[title]</b><br>________________________________________

          <p>$row[short]        <br>________________________________________</b>
	  <p>[ Posted by <strong>$row[posted]</strong></a> ]</td></tr>"; 
	}
	echo "</table>";

	
	//Display the full navigation in one go
	echo $pager->renderFullNav();
	



} // end function blog



function footer() {

$footer = "Powered By Gomez Shopping Cart."; $footer_ = "&#80;&#111;&#119;&#101;&#114;&#101;&#100;&nbsp;&#98;&#121;&nbsp;&#71;&#111;&#109;&#101;&#122;&nbsp;&#83;&#104;&#111;&#112;&#112;&#105;&#110;&#103;&nbsp;&#67;art";

echo "<a href='http://www.gomezstudio.com/'>$footer_</a>";

}

function tags() {

include ("class/wordcloud.class.php");

$cloud = new wordCloud();

$cloud->addWord(array('word' => 'Webmasters', 'size' => 1, 'url' => '', 'Webmasters' => '#FFFF00'));
$cloud->addWord(array('word' => 'Web Design Jobs', 'size' => 2, 'url' => '', 'Web Design Jobs' => '#FFFF00'));
$cloud->addWord(array('word' => 'Blogging', 'size' => 1, 'url' => '', 'Blogging' => '#FFFF00'));
$cloud->addWord(array('word' => 'Web', 'size' => 1, 'url' => '', 'Web' => '#FFFF00'));
$cloud->addWord(array('word' => 'Maitenance', 'size' => 1, 'url' => '', 'Maintenance' => '#FFFF00'));
$cloud->addWord(array('word' => 'XHTML', 'size' => 1, 'url' => '', 'Four your website' => '#FFFF00'));
$cloud->addWord(array('word' => 'CSS', 'size' => 1, 'url' => '', 'CSS' => '#FFFF00'));
$cloud->addWord(array('word' => 'CROSS', 'size' => 3, 'url' => '', 'CROSS' => '#FFFF00'));
$cloud->addWord(array('word' => 'Browsing', 'size' => 3, 'url' => '', 'Browsing' => '#FFFF00'));
$cloud->addWord(array('word' => 'Browser', 'size' => 3, 'url' => '', 'Browser' => '#FFFF00'));
$cloud->addWord(array('word' => 'AppDivs', 'size' => 1, 'url' => '', 'AppDivs' => '#FFFF00'));
$cloud->addWord(array('word' => 'Shop', 'size' => 1, 'url' => '', 'Shop' => '#FFFF00'));
$cloud->addWord(array('word' => 'SQL', 'size' => 3, 'url' => '', 'SQL' => '#FFFF00'));
$cloud->addWord(array('word' => 'MySQL', 'size' => 1, 'url' => '', 'MySQL' => '#FFFF00'));
$cloud->addWord(array('word' => 'Cart', 'size' => 3, 'url' => '', 'Cart' => '#FFFF00'));
$cloud->addWord(array('word' => 'PHP', 'size' => 1, 'url' => '', 'PHP' => '#FFFF00'));
$cloud->addWord(array('word' => '4.5', 'size' => 3, 'url' => '', '4.5' => '#FFFF00'));
$cloud->addWord(array('word' => 'Apache', 'size' => 3, 'url' => '', 'Apache' => '#FFFF00'));
$cloud->addWord(array('word' => 'Server', 'size' => 1, 'url' => '', 'Server' => '#FFFF00'));
$cloud->addWord(array('word' => 'Macromedia', 'size' => 1, 'url' => '', 'Macromedia' => '#FFFF00'));
$cloud->addWord(array('word' => 'Ps', 'size' => 2, 'url' => '', 'Ps' => '#FFFF00'));
$cloud->addWord(array('word' => 'Dw', 'size' => 1, 'url' => '', 'Dw' => '#FFFF00'));
$cloud->addWord(array('word' => '.PSD', 'size' => 2, 'url' => '', '.PSD' => '#FFFF00'));
$cloud->addWord(array('word' => 'gnome', 'size' => 1, 'url' => '', 'gnome' => '#FFFF00'));
$cloud->addWord(array('word' => 'Gomez', 'size' => 3, 'url' => '', 'Gomez' => '#FFFF00'));
$cloud->addWord(array('word' => 'web', 'size' => 2, 'url' => '', 'web' => '#FFFF00'));
$cloud->addWord(array('word' => '2.0', 'size' => 4, 'url' => '', '2.0' => '#FFFF00'));
$cloud->addWord(array('word' => 'new', 'size' => 3, 'url' => '', 'new' => '#FFFF00'));
$cloud->addWord(array('word' => 'version', 'size' => 1, 'url' => '', 'version' => '#FFFF00'));

$cloud->setLimit(20);
echo $cloud->showCloud(); 

}

function shop() {

global $db, $server, $user, $pass, $database;

		// Shopping Cart (HTTP GET VARIABLES)
		switch ($_GET['op']) {

		case "blog":
		blog();
		break;

		case "step2":
		step2();
		break;
		
		case "checkout":
		checkout();
		break;

		case "cat":
		cat();
		break;

		default:
		shop_index();

		}
} // end function shop


/*
 * Load Shopping Cart
 */


/*
 * Get Default Template
 */


$sql = "SELECT * FROM ".$db->pre."tbl_user";
$countRows = $db->query($sql);

while ($countRow = $db->fetch_array($countRows)) {
    $template = $countRow['template'];
    $seo = $countRow['seo'];
}

/*
 * Load SEO URL's
 */

include("SEO.php"); 

if(file_exists("templates/$template/index.html")) {
	include("templates/$template/index.html");
} else {
	die("Can't Load Template...");
}

$db->close();


?>