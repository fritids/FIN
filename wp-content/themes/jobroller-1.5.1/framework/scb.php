<?php

foreach ( array(
	'scbUtil', 'scbOptions', 'scbForms', 'scbTable',
	'scbWidget', 'scbAdminPage', 'scbBoxesPage',
	'scbCron', 'scbHooks',
) as $className ) {
	if ( !class_exists( $className ) ) {
		include dirname( __FILE__ ) . '/scb/' . substr( $className, 3 ) . '.php';
	}
}

