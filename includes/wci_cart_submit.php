<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

$cart_action = sanitize_text_field( $_REQUEST['action_name'] );
print_r( $cart_action );
die;
