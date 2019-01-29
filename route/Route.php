<?php
use easy\Routehand;

   	
Routehand::get('/index',"index@Index@index");
Routehand::get('/app/show/{appid}/{sheetid}',"index@Index@list");

Routehand::get('/get/user/{id}',"index@Index@test");

Routehand::get('/test/app',"api@app@getUser");
