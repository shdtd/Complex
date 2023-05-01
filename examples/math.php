<?php
require("class/complex.php");

$arr_cmp = array( new complex(7, 5),
  new complex(1, -2),
  new complex("2"),
  new complex("-2"),
  new complex("i"),
  new complex("-i"),
  new complex("2i"),
  new complex("-2i"),
  new complex("2+i"),
  new complex("-2+i"),
  new complex("-2-i"),
  new complex("1+2i"),
  new complex("1.00+2.00i"),
  new complex("-0.1+0.2i"),
  new complex("0.21-0.2i"),
  new complex("5.1121-2.01i"),
  new complex(".1121-.01i"),
  new complex("sqrt(5)(cos(atan(2)) + i sin(atan(2)))"),
  new complex("sqrt(5)(cos(arctg(2)) + i sin(arctg(2)))"),
  new complex("sqrt(5)(cos(-atan(2) + pi) + i sin(-atan(2) + pi))"),
  new complex("sqrt(5)(cos(-arctg(2) + pi) + i sin(-arctg(2) + pi))"),
  new complex("sqrt(5)(cos(-atan(2) + 2 * pi) + i sin(-atan(2) + 2 * pi))"),
  new complex("sqrt(5)(cos(-arctg(2) + 2 * pi) + i sin(-arctg(2) + 2 * pi))"),
  new complex("sqrt(5)(cos(atan(2) + pi) + i sin(atan(2) + pi))"),
  new complex("sqrt(5)(cos(arctg(2) + pi) + i sin(arctg(2) + pi))"),
  new complex("2.2360679774998(cos(0.46364760900081) + i * sin(0.46364760900081))"),
  new complex("5.4930561994212(cos(-0.37461743046639) + i * sin(-0.37461743046639))"),
  new complex("0.22360679774998(cos(2.0344439357957) + i * sin(2.0344439357957))"), );

foreach ($arr_cmp as $cmp){
  echo "+-----------------------------------------------------------------\n";
  echo "| Algebraic representation: " . $cmp->toString()."\n".
  "| Algebraic representation (rounded to 1/1000): " . $cmp->toString2()."\n".
  "| Trigonometric representation:\n|\t" . $cmp->toTrigString()."\n".
  "| Trigonometric representation 2:\n|\t" . $cmp->toTrigString2()."\n".
  "| Addition: " . complex::add($cmp, $cmp)->toString()."\n".
  "| Subtraction: " . complex::sub($cmp, $cmp)->toString()."\n".
  "| Multiplication: " . complex::mult($cmp, $cmp)->toString()."\n".
  "| Division: " . complex::div($cmp, $cmp)->toString()."\n";
  echo "+-----------------------------------------------------------------\n";
}