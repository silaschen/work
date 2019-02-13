<?php
use easy\Routehand;

Routehand::get('/index',"index@Index@index");
Routehand::get('/app/show/{appid}/{sheetid}',"index@Index@list");
Routehand::post('/get/user/{id}',"index@Index@test");
