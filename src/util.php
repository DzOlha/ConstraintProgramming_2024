<?php
namespace Src;

require_once '../vendor/autoload.php';

use Src\Entity\Matrix;
use Src\Helper\PrintHelper;
use Src\Model\TSS;

$tss = new TSS();

$A_test = new Matrix(
    [
        [2.1, 1.1, 0, 1, 2.3],
        [0.5, 0.2, 1, 0, -1.3],
        [0.1, 0, 2.5, 2, 0]
    ],
    3,
    5
);
/**
 * ---Matrix: p = 3, q = 5, Float numbers------------------------------------*
 */
$A_first = new Matrix(
    [
        [2.4, 1.1, 3, 1, 2.1],
        [0.7, 0.2, 1, 5, 0.8],
        [0.1, 3.3, 6.3, 0.2, 0]
    ],
    3,
    5
);

/**
 * ---Matrix: p = 3, q = 6, Integer numbers------------------------------------*
 */
$A_second = new Matrix(
    [
        [2, 1, 3, 1, 2, 1],
        [7, 2, 1, 5, 0, 3],
        [1, 3, 6, 2, 1, 4]
    ],
    3,
    6
);

/**
 * ---Matrix: p = 3, q = 7, Integer numbers------------------------------------*
 */
$A_third = new Matrix(
    [
        [2, 1, 3, 1, 2, 1, 6],
        [7, 2, 1, 5, 0, 3, 3],
        [1, 3, 6, 2, 1, 4, 0]
    ],
    3,
    7
);
$M = new Matrix(null, 0, 0);

$result = $tss->calculateSystemTSS($A_test, $M, 0);
if($result === false) {
    echo "\n".'The system does not have solution!';
} else {
    PrintHelper::printMatrix($result->getMatrix(), 'Final Result: ');
}
