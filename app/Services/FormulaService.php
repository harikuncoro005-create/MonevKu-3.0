<?php

namespace App\Services;

class FormulaService {
    public function formula_1($a) {
        return $a;
    }

    public function formula_2($a,$b) {
        if ($b == 0) {
            return 0;
        }

        return $a/$b*100000;
    }

    public function formula_3($a,$b) {
        if ($b == 0) {
            return 0;
        }

        return $a/$b*1000;
    }

    public function formula_4($a,$b) {
        if ($b == 0) {
            return 0;
        }

        return $a/$b*100;
    }

    public function formula_5($a,$b,$c,$d) {
        if (($c+$d) == 0) {
            return 0;
        }
       
        return ($a+$b)/($c+$d);
    }

    public function formula_6($a,$b,$c) {
        return $a+$b+$c;
    }

    public function formula_7($a,$b) {
        return $a+$b;
    }

    public function formula_8($a,$b) {
        return $a-$b;
    }

    public function formula_9($a,$b,$c,$d) {
        if ($b == 0 || $d == 0) {
            return 0;
        }

        return (($a/$b)*100)+(($c/$d)*100);
    }

    public function formula_10($a,$b,$c) {
        if ($c == 0) {
            return 0;
        }

        return ($a+$b)/$c*100;
    }

    public function formula_11($a,$b,$c,$d,$e,$f,$g,$h) {
        if (($e+$f+$g+$h) == 0) {
            return 0;
        }

        return ($a+$b+$c+$d)/($e+$f+$g+$h)*100;
    }

    public function formula_12($a,$b,$c,$d) {
        return ($a+$b+$c+$d)/4;
    }

    public function formula_13($a,$b,$c,$d) {
        if ($d == 0) {
            return 0;
        }

        return ($a+$b+$c)/$d*100;
    }

    public function formula_14($a,$b,$c,$d) {
        if ($b == 0 || $d == 0) {
            return 0;
        }

        return (($a/$b*0.5)+($c/$d*0.5))*100;
    }

    public function formula_15($a,$b) {
        if ($a == 0) {
            return 0;
        }
        return ($a-$b)/$a*100;
    }

    public function formula_16($a,$b,$c) {
        return ($a+$b+$c)/3;
    }

    public function formula_17($a,$b,$c,$d) {
        if ($b == 0 || $d == 0) {
            return 0;
        }

        return (($a/$b*100)+($c/$d*100))/2;
    }

    public function formula_18($a,$b,$c,$d,$e,$f,$g,$h,$i,$j) {
        if (($f+$g+$h+$i+$j) == 0) {
            return 0;
        }

        return ($a+$b+$c+$d+$e)/($f+$g+$h+$i+$j)*100;
    }

    public function formula_19($a,$b,$c,$d) {
        if ($b == 0 || $d == 0) {
            return 0;
        }

        return (($a/$b*100)+($c/$d*100))/2;
    }

    public function formula_20($a,$b,$c,$d) {
        if ($b == 0 || $d == 0) {
             return 0;
        }

        return (($a/$b)/($c/$d))/2*100;
    }

    public function formula_21($a,$b,$c,$d,$e,$f,$g,$h,$i,$j) {
        return $a+$b+$c+$d+$e+$f+$g+$h+$i+$j;
    }
}