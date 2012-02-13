<?php

	// Page PHP Backend Code Begin
		include_once("page.php");
		$page = new Page();
		$page->title = "Quicksilver Music";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
		$id = $_REQUEST['storeId'];
		
		////////////////////////////////////////////////////////
		
		include ("AppClasses/db_conn.php");
		dbConnect("0", "00");
		dbSelect("sales");
		$store = mysql_fetch_array(mysql_query("SELECT * FROM `store` WHERE `storeId` = '".$id."'"));
		
		$storeName = $store['storeName'];
		
?>

<div class="container-615">
	<h2 class="header-615-red item-heading">The <?=$storeName?> Store</h2>
    <div class="content-615-red">
    	<div id="store-img">
        <img src="images/stores/<?=$id?>.jpg" alt="<?=$storeName?>" />
        </div>
    </div>
</div>



<span class="spacer">
	&nbsp;
</span>

<div class="container-300">
	<h2 class="header-300-blue item-heading">Store Details</h2>
    <div class="content-300-blue">
    	<span class="bold">Address:<br /></span>
        <span><?=$store['address'];?><br /><?=$store['city'];?></span>
        <hr />
        <h3 class="opening-hours">Opening Hours</h3>
					<div class="opening-hours">
						<span>&nbsp;</span>
					<div class="day-row">
						<span class="day">Mon:</span><span>&nbsp;</span><span class="times">07:00 - 18:00</span>
					</div><div class="day-row">
						<span class="day">Tue:</span><span>&nbsp;</span><span class="times">07:00 - 18:00</span>
					</div><div class="day-row">
						<span class="day">Wed:</span><span>&nbsp;</span><span class="times">07:00 - 18:00</span>
					</div><div class="day-row">
						<span class="day">Thu:</span><span>&nbsp;</span><span class="times">07:00 - 19:00</span>
					</div><div class="day-row">
						<span class="day">Fri:</span><span>&nbsp;</span><span class="times">07:00 - 19:00</span>
					</div><div class="day-row">
						<span class="day">Sat:</span><span>&nbsp;</span><span class="times">07:30 - 16:30</span>
					</div><div class="day-row">
						<span class="day">Sun:</span><span>&nbsp;</span><span class="times">10:00 - 16:00</span>
					</div>
                    </div>
				
    </div>
</div>

<?php	
	$page->getFooter();
?>