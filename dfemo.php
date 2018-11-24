<?php

echo  strtotime('+1 day') - time()."\n";
echo  strtotime('+1 day')."\n";

echo time()."\n";

echo (strtotime(date('Y-m-d', strtotime('+1 day'))) - time())/60/60;
