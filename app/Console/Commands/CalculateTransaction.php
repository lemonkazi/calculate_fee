<?php

namespace App\Console\Commands;

use App\Http\Controllers\Currency\CurrencyContainer;
use App\Http\Controllers\Currency\Currency;
use App\TransactionMapper\TransactionMapper;
//use App\Http\Controllers\Transaction;
use App\Http\Controllers\TransactionController;
use Illuminate\Console\Command;

class CalculateTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:transaction {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commission fee calculation: Commission fee will calculate. For example, if you withdraw or deposit in US dollars then commission fee is also in US dollars.';

    /**
     * Transaction items.
     *
     * @var array items list array
     */
    private array $items;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            \Log::info(get_class($this) . ': Start process');
            $filename = $this->argument('file');
            $this->info(sprintf('Importing file "%s"', $filename), $this->signature);
            $csvData = $this->csvToArray($filename, ',');
            if (!is_array($csvData)) {
                $this->error('Check File format');
            }
            /**
             * Add some currencies from config.
             */
            $baseCurrency = new Currency();
            $baseCurrency->setCurrency(config('global.BASE_CURRENCY'));
            $currencyData = CurrencyContainer::getInstance();
            $currincies = config('global.currency');
            foreach ($currincies as $value) {
                $currencySet = new Currency();
                if ($value == 'JPY') {
                    $currencySet->setCurrency($value)
                        ->setDecimals(0);
                } else {
                    $currencySet->setCurrency($value);
                }
                $currencyData->add($currencySet);
            }
            /**
             * read csv data and set items for get communication response
             */
            foreach ($csvData as $data) {
                $this->items[] = new TransactionMapper($data);
            }

            // Process transaction
            $transactionFactory = new TransactionController();
            $transaction = $transactionFactory->transaction($this->items);
            $transaction->allProcess();
            
            $this->line(implode("\n", $transaction->responses));
            \Log::info(get_class($this) . ': End process');
            return Command::SUCCESS;

        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
