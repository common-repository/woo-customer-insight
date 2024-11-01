<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

?>

<style>
html,body
{
background-color: #FFFFFF;
}

</style>
<?php

require_once('WCI_ClickClass.php');
echo "<br><br><div id='chart_title'>" .WCI_TITLE. " :: Reports </div>";

 // MENU BAR
        echo "<div class='wp-common-wciwrapper'>";
        $menu_obj = new SM_Woo_Customer_Insight();
        $menu_obj->wci_menubar();
        echo "</div>";

echo "<div class='header_title' style='margin-top:0px;'><h4> " . esc_html('Tracked Events :')." </h4></div>";
$list = new Customer_Button_click_Table();     
        $list->prepare_items();
        echo "<div style='margin-top:10px; width:98%;'>";
        $list->display();
        echo "</div>";