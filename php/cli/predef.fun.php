<?
function predef_1(){global $a,$d;
	echo "predefinido 1\n";
	$c = prompt('concepto');
	$b = prompt('base');
	$iva = prompt('iva', '8');
	$sc = prompt('subcuenta', '601');
	$cp = prompt('contrapartida', '400.1');
	
	apunte($c, $b, $sc, $cp);
	apunte($c, ($b*$iva/100), "472.$iva", $cp);
	apunte($c, -($b*(1+$iva/100)), $cp, $sc);
}
function predef_2(){global $a,$d;
	echo "predefinido 2. \n";
	$c = prompt('concepto');
	$b = prompt('base');
	$iva = prompt('iva', '8');
	$irpf = prompt('irpf', '8');
	$sc = prompt('subcuenta', '601');
	$cp = prompt('contrapartida', '570.1');
	
	apunte($c, $b, $sc, $cp);
	apunte($c, ($b*$iva/100), "472.$iva", $cp);
	apunte($c, ($b*$irpf/100), "4751", $cp);
	apunte($c, ($b*(1+$iva/100)), $cp, $sc);
}
function predef_3(){global $a,$d;
	$c = prompt('concepto');
	$b = prompt('base');
	$iva = prompt('iva', '18');
	$sc = prompt('subcuenta', '701.1');
	$cp = prompt('contrapartida', '430');
	
	apunte($c, ($b*(1+$iva/100)), $sc, $cp);
	apunte($c, ($b*$iva/100), "477.$iva", $cp);
	apunte($c, $b, $cp, $sc);
}
function predef_4(){global $a,$d;
	$c = prompt('concepto');
	$s = prompt('salario base');
	$ss_aut = prompt('SS autonomos');
	$ss_gral = prompt('Cont. comunes + desemp. + f.p.');
	$irpf = prompt('IRPF');
	$irpf_e = prompt('IRPF Retr.E');
	
	apunte($c, $s, '640', '465');
	apunte($c, $ss_aut, '640.1', '465');
	apunte($c, $ss_gral, '642', '465');
	apunte($c, $irpf_e, '475.1', '465');
	apunte($c, $irpf, '475', '465');
	apunte($c, ($s+$ss_aut+$ss_gral+$irpf+$irpf_e), '465', '640');
}
?>