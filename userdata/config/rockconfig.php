<?php
$MONGO = array();
$MONGO["features"]["log_query"] = "off";//log queries
$MONGO["features"]["theme"] = "default";//theme
$MONGO["features"]["plugins"] = "on";//plugins
$MONGO["servers"][0]["mongo_name"] = "%ip%";//mongo server name
$MONGO["servers"][0]["mongo_host"] = "%ip%";//mongo host
$MONGO["servers"][0]["mongo_port"] = "%mongodbport%";//mongo port
$MONGO["servers"][0]["mongo_timeout"] = 0;//mongo connection timeout
//$MONGO["servers"][0]["mongo_db"] = "MONGO_DATABASE";//default mongo db to connect, works only if mongo_auth=false
//$MONGO["servers"][0]["mongo_user"] = "MONGO_USERNAME";//mongo authentication user name, works only if mongo_auth=false
//$MONGO["servers"][0]["mongo_pass"] = "MONGO_PASSWORD";//mongo authentication password, works only if mongo_auth=false
$MONGO["servers"][0]["mongo_auth"] = false;//enable mongo authentication?
$MONGO["servers"][0]["control_auth"] = false;//enable control users, works only if mongo_auth=false
$MONGO["servers"][0]["control_users"]["admin"] = "admin";//one of control users ["USERNAME"]=PASSWORD, works only if mongo_auth=false
$MONGO["servers"][0]["ui_only_dbs"] = "";//databases to display
$MONGO["servers"][0]["ui_hide_dbs"] = "";//databases to hide
$MONGO["servers"][0]["ui_hide_collections"] = "";//collections to hide
$MONGO["servers"][0]["ui_hide_system_collections"] = false;//if hide the system collections
?>