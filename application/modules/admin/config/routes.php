<?php
// admin routes
$route['admin'] = 'auth/index';
$route['admin/forgotpassword'] = 'auth/forgotpassword';
$route['admin/resetpassword'] = 'auth/resetpassword';
$route['admin/logout'] = 'auth/logout';
$route['admin/dashboard'] = 'auth/dashboard';
$route['admin/users'] = 'users/user_list';
$route['admin/freetips'] = 'tips/index';
$route['admin/featurematch'] = 'tips/add_feature_match';
$route['admin/games'] = 'tips/add_game_match';


