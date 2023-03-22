<?php 
$a = 'variable';
$t = 'poil';
$$t = 10;
$autre = 100;
$$la = "autre";
$$a = 5;
echo $a; // Affiche : variable.
echo '<br />';
echo $variable; // Affiche : 5.
echo '<br />';
echo $poil; // Affiche : 5.
echo '<br />';
echo $$la; // Affiche : 5.