<?php
$start_time = time();
$interval = 3;
while (true){
    if ((time()-$start_time)>=3){

        echo "<script>alert(\"Вы вошли на сайт, как гость.\");</script>";;
        return 0;
    }
}
?>
