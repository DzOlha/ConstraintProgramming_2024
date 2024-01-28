<?php

namespace Src\Entity;

class Matrix
{
    /**
     * @var array $matrix
     *
     * A_pq => [
     *      [a_11, a_12, ......a_1q]
     *      [a_21, a_22, ......a_2q]
     *      .....................
     *      [a_p1, a_p2, .......a_pq]
     * ]
     */
    private ?array $matrix;
    private int $rows;
    private int $columns;

    /**
     * @param ?array $matrix
     * @param int $rows
     * @param int $columns
     */
    public function __construct(?array $matrix, int $rows, int $columns)
    {
        $this->matrix = $matrix;
        $this->rows = $rows;
        $this->columns = $columns;
    }

    public function getItem(int $i, int $j)
    {
        return $this->matrix[$i][$j];
    }

    public function getRows(): int
    {
        return $this->rows;
    }

    public function getColumns(): int
    {
        return $this->columns;
    }

    public function getMatrix(): ?array
    {
        return $this->matrix;
    }

    public function appendRow(array $row) {
        if($this->columns === 0) {
            //unset($this->matrix[0]);
            $this->columns = count($row);
        }
        if(count($row) === $this->columns) {
            if($this->matrix === null) {
                $this->matrix = [];
            }
            $this->matrix[] = $row;
            $this->rows++;
        }
        else {
            var_dump('The row size differ from the matrix column number! Can not append row to the matrix!');
            exit();
        }
        //var_dump($this->_toString());
        //PrintHelper::printMatrix($this->matrix, "\n".'Appended matrix = ');
    }

    public function isEmpty(): bool {
        return $this->matrix === null || count($this->matrix) === 0;
    }

    public function setCanonical(int $rows, int $columns) {
        $canonicalBasis = [];

        for ($i = 0; $i < $rows; $i++) {
            $vector = array_fill(0, $columns, 0);
            $vector[$i] = 1;
            $canonicalBasis[] = $vector;
        }

        $this->matrix = $canonicalBasis;
        $this->rows = $rows;
        $this->columns = $columns;
    }

    public function getRow(int $rowsIndex)
    {
        if(array_key_exists($rowsIndex, $this->matrix)) {
            return $this->matrix[$rowsIndex];
        } else {
            return false;
        }
    }

}