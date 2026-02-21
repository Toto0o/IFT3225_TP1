<?php

require_once __DIR__.'/router.php';

// ##################################################
// ##################################################
// ##################################################

//Tuiles
get('/tuiles', 'api-rest/tuiles/read.php');
put('/tuiles', 'api-rest/tuiles/update.php');
delete('/tuiles', 'api-rest/tuiles/delete.php');
post('/tuiles', 'api-rest/tuiles/create.php');

//Users
get('/users/$username', 'api-rest/users/read.php');
put('/users', 'api-rest/users/update.php');
delete('/users', 'api-rest/users/delete.php');
post('/users', 'api-rest/users/create.php');
post('/users/login', 'api-rest/users/login.php');
post('/users/logout', 'api-rest/users/logout.php');

any('/404', 'views/404.php');
