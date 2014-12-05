<?php

if($seo) {
DEFINE("_INDEX", "shop-index.html");
DEFINE("_BLOG", "shop-blog.html");
DEFINE("_ADD", "shop-add_to_cart.html");
DEFINE("_STEP2", "shop-step2.html");
DEFINE("_CHECKOUT", "shop-checkout.html");
DEFINE("_PREVIEW", "img-<?=$row[image]?>.jpg");
}
else {
DEFINE("_INDEX", "index.php?");
DEFINE("_BLOG", "index.php?op=blog");
DEFINE("_ADD", "index.php?op=add_to_cart");
DEFINE("_STEP2", "index.php?op=step2");
DEFINE("_CHECKOUT", "index.php?op=checkout");
DEFINE("_PREVIEW", "gallery/<?=$row[image]?>2.jpg");
}

?>
