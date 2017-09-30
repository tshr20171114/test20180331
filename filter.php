$buffer = '';
for(;;){
    $tmp = fgets(STDIN);
    if ( $tmp == FALSE ){
        break;
    }
    $buffer .= $tmp;
}
//$buffer = preg_replace("/http:..__OPENSHIFT_DIY_IP__:30080.-.builtin.icons.ysato/", "/delegate/icons", $buffer);
//$buffer = preg_replace("/<TITLE>/", "<HTML><HEAD><META HTTP-EQUIV="REFRESH" CONTENT="600"><TITLE>", $buffer, 1);
//$buffer = preg_replace("/<\/TITLE>/", "</TITLE></HEAD>", $buffer, 1);
$buffer = preg_replace("/<FORM ACTION="..\/-search" METHOD=GET>.+?<\/FORM>/", "", $buffer, 1);
//$buffer = preg_replace("/<TABLE width=100% border=0 bgcolor=#8080FF cellpadding=1 cellspacing=0>.*/", "</HTML>", $buffer, 1);
print $buffer;
