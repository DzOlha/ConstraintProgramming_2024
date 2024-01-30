<?php

namespace Src\Model;

use Src\Entity\Matrix;
use Src\Helper\PrintHelper;

/**
 * Truncated Set of Solution
 */
class TSS
{
    /**
     * @param Matrix $A - the matrix of the system of linear homogeneous equations
     * @param Matrix $M - the initial set of solutions
     * @param int $k - the number of equation in the system that we are working on

     * @return Matrix|false
     */
    public function calculateSystemTSS(
        Matrix $A, Matrix $M, int    $k
    )
    {
        PrintHelper::printMatrix($A->getMatrix(), 'A = ');
        if ($M->isEmpty()) {
            $k = 0;
            $M->setCanonical($A->getRows(), $A->getColumns());
            PrintHelper::printMatrix($M->getMatrix(), "\n Canonical Basis = ");
        }

        for ($i = $k; $i < $A->getRows(); $i++) {
            //echo "\n".'Index $i = '.$i."\n";
            $M = $this->calculateSubsystemTSS($M, $A->getRow($i), $i);

            PrintHelper::printMatrix($M->getMatrix(), 'M_1 result = ');
            echo "\n-----------------------------------------------------------------------------------------------------";
        }

        if ($M->isEmpty()) {
            return false;
        }
        return $M;
    }

    public function calculateSubsystemTSS(Matrix $M, array $equation, int $iteration)
    {
        $function = [];
        PrintHelper::printMatrix($M->getMatrix(), 'M old = ');
        PrintHelper::printArray($equation, 'Equation row = ');

        if($iteration > 0) {
            for($j = 0; $j < $M->getRows(); $j++) {
                /**
                 * Make the new function using the previously calculated
                 * solution matrix and the current row (the next in terms of M)
                 *
                 * Example: f = L(s1)y1 + L(s2)y2 + L(s3)y3 + L(s4)y4
                 *
                 * $equation = L(x)
                 *
                 * $function = [
                 *      L(s1), L(s2), L(s3), L(s4)
                 * ]
                 */
                $function[] = $this->multiply($M->getRow($j), $equation);
            }
        } else {
            $function = $equation;
        }

        $M_new = new Matrix(null, 0, 0);

        $functionVariablesCount = count($function);
        $negativeFirstCoefficient = null;
        for ($m = 0; $m < $functionVariablesCount; $m++) {
            /**
             * Save the first coefficient of the function with the opposite sign
             */
            if ($m === 0) {
                $negativeFirstCoefficient = -$function[0];
                continue;
            }

            //echo "\n m = $m ";

            $item = $function[$m];
            $vector = array_fill(0, $functionVariablesCount, 0);

            /**
             * If the coefficient of the function is zero
             * -> just use the canonical vector instead of it
             */
            if ($item === 0) {
                $vector[$m] = 1;
            } else {
                $vector[$m] = $negativeFirstCoefficient;
                $vector[0] = $item;
            }

            PrintHelper::printArray($vector, 'Base vector = ');
            $M_new->appendRow($vector);
        }

        /**
         * Apply the solution matrix, gotten on the last iteration,
         * to the current solution matrix
         *
         * Example: M = (s1, s2, s3, s4) - old solution matrix
         *
         * M_new:            |------------Transformation-------------|
         *      (1, 2, 3, 4) -> 1*s1 + 2*s2 + 3*s3 + 4*s4 = s1_result
         *      (5, 5, 6, 7) -> 5*s1 + 5*s2 + 6*s3 + 7*s4 = s2_result
         *      (1, 4, 3, 6) -> 1*s1 + 4*s2 + 3*s3 + 6*s4 = s3_result
         *                   |----------------------------------------|
         */
        if($iteration > 0) {
            $M_result = new Matrix(null, 0, 0);
            for ($n = 0; $n < $M_new->getRows(); $n++){
                $row = [];
                for($o = 0; $o < $M->getRows(); $o++) {
                    /**
                     * Multiply all indexes in the one of the base vector of the current matrix
                     * with the corresponding base vector from the previous matrix
                     */
                    $row[] = $this->vectorMultiplyNumber(
                        $M_new->getItem($n, $o),
                        $M->getRow($o)
                    );
                }
                $countVectors = count($row);
                $finalRow = [];
                /**
                 * loop through columns to add the value from the different rows
                 * and place it into the appropriate array cell
                 */
                for($j = 0; $j < $M->getColumns(); $j++) {
                    /**
                     * loop through rows
                     */
                    $sum = 0;
                    for($i = 0; $i < $countVectors; $i++) {
                        $sum += $row[$i][$j];
                    }
                    $finalRow[$j] = $sum;
                }
                //PrintHelper::printArray($finalRow, 'Row = ');
                $M_result->appendRow($finalRow);
            }
            return $M_result;
        }
        return $M_new;
    }

    public function multiply(array $a, array $b)
    {
        $size_a = count($a);
        $size_b = count($b);

        if ($size_a !== $size_b) {
            var_dump('Vectors should have the same sizes! Can not get scalar product!');
            exit();
        }

        $result = 0;
        for ($i = 0; $i < $size_a; $i++) {
            $result += $a[$i] * $b[$i];
        }

        return $result;
    }

    public function vectorMultiplyNumber(float $number, array $vector)
    {
        $result = [];
        foreach ($vector as $value) {
            $result[] = $value * $number;
        }
        //PrintHelper::printArray($result, "\n Multiplication = ");
        return $result;
    }
}