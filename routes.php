<?php

require_once __DIR__.'/router.php';

// ##################################################
// ##################################################
// ##################################################

//GET Tuiles
get('/tuiles', 'api-rest/tuiles/read.php');
put('/tuiles', 'api-rest/tuiles/update.php');
delete('/tuiles', 'api-rest/tuiles/delete.php');
post('/tuiles', 'api-rest/tuiles/create.php');

any('/404', 'views/404.php');
