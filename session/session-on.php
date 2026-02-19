<?php

session.start();

if (empty( $_SESSION['count'])) {
	$_SESSIONS['count'] = 1;
} else {
	$_SESSION['count']++;
}


