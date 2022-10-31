<?php

namespace Tests\Unit\Transaction;

use Illuminate\Support\Facades\Facade;
use App\Http\Controllers\Currency\Currency;
use App\Http\Controllers\Currency\CurrencyContainer;
use App\TransactionMapper\TransactionMapper;
use App\Http\Controllers\TransactionController;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class TransactionTest extends TestCase
{
    public string $fileName;

    /**
     * Add a csv file before starting the processes.
     *
     * @return void
     */
    protected function setup(): void
    {
        parent::setUp();
        $this->fileName = 'input.csv';

        // open csv file for writing
        $f = fopen($this->fileName, 'w');

        if ($f === false) {
            die('Error opening the file ' . $this->fileName);
        }

        // write each row at a time to a file
        foreach ($this->getTestData() as $row) {
            fputcsv($f, $row);
        }

        // close the file
        fclose($f);
    }

    /**
     * Delete the file after processing.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        // if (!unlink($this->fileName)) {
        //     throw new \Exception('Something went wrong. File can not being deleted.');
        // }
    }

    /**
     * file data get and create array
     *
     * @return array
     */
    public function csvToArray($file, $delimiter)
    {
        if (($handle = fopen($file, 'r')) !== FALSE) {

            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
                if (count($lineArray) == 1 && is_null($lineArray[0])) {
                    continue;
                }
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = trim($lineArray[$j]);
                }
                $i++;
            }
            fclose($handle);
        }
        
        return $arr;
    }

    /**
     * test
     */
    public function testGetCommissionFeesCsvFile()
    {
        $csvData = file_get_contents($this->fileName);
        $csvData = $this->csvToArray($this->fileName, ',');
        
        // print_r(Config::get('global.exchange_rate'));
        // exit();
        // if (!is_array($csvData)) {
        //     $this->error('Check File format');
        // }

        /**
         * Add some currencies for testing.
         */
        $baseCurrency = new Currency();
        $baseCurrency->setCurrency('EUR');

        $currencyUsd = new Currency();
        $currencyUsd->setCurrency('USD');

        $currencyJpy = new Currency();
        $currencyJpy->setCurrency('JPY')
            ->setDecimals(0);

        $currencyData = CurrencyContainer::getInstance();
        $currencyData->add($baseCurrency)
            ->add($currencyUsd)
            ->add($currencyJpy);

        // Map transaction data into TransactionItem object.
        // $transactionItems = new TransactionItems();
        // $transactionItems->setItems($csvReader->getRows());

        foreach ($csvData as $data) {
            $transactionItems[] = new TransactionMapper($data);
        }
        // Process transaction
        $transaction = new TransactionController();
        $transaction = $transaction->transaction($transactionItems);
        $transaction->allProcess();

        $this->assertEquals(
            $this->getTestResult(),
            $transaction->responses
        );
    }

   


    /**
     * Get test data.
     *
     * @return array Test datasets for the CSV file
     */
    public function getTestData(): array
    {
        return [
            ['2014-12-31', 4, 'private', 'withdraw', 1200.00, 'EUR'],
            ['2015-01-01', 4, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-05', 4, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-05', 1, 'private', 'deposit', 200.00, 'EUR'],
            ['2016-01-06', 2, 'business', 'withdraw', 300.00, 'EUR'],
            ['2016-01-06', 1, 'private', 'withdraw', 30000, 'JPY'],
            ['2016-01-07', 1, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-07', 1, 'private', 'withdraw', 100.00, 'USD'],
            ['2016-01-10', 1, 'private', 'withdraw', 100.00, 'EUR'],
            ['2016-01-10', 2, 'business', 'deposit', 10000.00, 'EUR'],
            ['2016-01-10', 3, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-02-15', 1, 'private', 'withdraw', 300.00, 'EUR'],
            ['2016-02-19', 5, 'private', 'withdraw', 3000000, 'JPY']
        ];
    }

    /**
     * Get test result set for that CSV file.
     *
     * @return array test array result set
     */
    public function getTestResult(): array
    {
        return [
            0.60,
            3.00,
            0.00,
            0.06,
            1.50,
            0,
            0.69,
            0.30,
            0.30,
            3.00,
            0.00,
            0.00,
            8607
        ];
    }
}
