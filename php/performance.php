<?php

function isPrime($n){
  $prime = true;
  for ($i=2; $i<$n; $i++){
    $div = intval($n/$i);
    if (($div*$i) == $n){
      $prime = false ;
    }
  }
  return $prime ;
}

function findPrimes($n){
  $primes=array();
  for ($i=1; $i<$n; $i++){
    if (isPrime($i)){
      $primes[]=$i;
    }
  }
  return $primes ;
}

$upper=5000;
$start=microtime(true);
$primes=findPrimes($upper);
$n=count($primes);
$stop=microtime(true);
$duration=$stop-$start;
echo "<p>Found $n primes below $upper in $duration seconds</p>" ;
phpinfo();
?>

