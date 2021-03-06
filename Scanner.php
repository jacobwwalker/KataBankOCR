<?php

class Scanner
{
    public function Scan($filename)
    {
        $lines = file($filename);

        while (count($lines) > 0)
        {
            $splice = array_splice($lines, 0, 4);

            $linesSplitIntoThrees = array();

            foreach ($splice as $line)
            {
                $linesSplitIntoThrees[] = str_split($line, 3);
            }

            $charactersSplitIntoRows = $this->rotateArray($linesSplitIntoThrees);

            $accountNumberAsArray = array();

            foreach ($charactersSplitIntoRows as $characterAsArrayOfRows)
            {
                $accountNumberAsArray[] = $this->identifyCharacter($characterAsArrayOfRows);
            }

            $accountNumber = implode($accountNumberAsArray);

            echo $accountNumber;

            if ($this->verifyAccountNumber($accountNumber))
            {
                echo " INVALID";
            }

            echo "\n";
        }
    }

    private function rotateArray($inputArray)
    {
        $rotatedArray = array();

        for ($column = 0; $column < 9; $column++)
        {
            for ($row = 0; $row < 3; $row++)
            {
                $rotatedArray[$column][] = $inputArray[$row][$column];
            }
        }

        return $rotatedArray;
    }

    private $arrangementTable = array(
        '   ' => '0',
        ' _ ' => '1',
        '  |' => '2',
        ' _|' => '3',
        '|_|' => '4',
        '|_ ' => '5',
        '| |' => '6'
    );

    private $characterCodeTable = array(
        '164' => 0,
        '022' => 1,
        '135' => 2,
        '133' => 3,
        '042' => 4,
        '153' => 5,
        '154' => 6,
        '122' => 7,
        '144' => 8,
        '143' => 9
    );

    private function identifyCharacter(array $character)
    {
        $characterAsCode = '';

        for ($characterRow = 0; $characterRow < 3; $characterRow++)
        {
            $arrangement = $character[$characterRow];
            $characterAsCode .= $this->arrangementTable[$arrangement];
        }

        return $this->characterCodeTable[$characterAsCode];
    }

    private function verifyAccountNumber($accountNumber)
    {
        $digitArray = str_split($accountNumber, 1);
        $reversedDigitArray = array_reverse($digitArray);

        $dotProduct = 0;

        for ($digit = 0; $digit < 9; $digit++)
        {
            // Multiplier is the 1-indexed position of the digit
            $dotProduct += ($digit + 1) * $reversedDigitArray[$digit];
        }

        return $dotProduct % 11 ? true : false;
    }
}
