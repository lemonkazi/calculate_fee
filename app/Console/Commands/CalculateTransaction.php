<?php

namespace App\Console\Commands;

use App\Http\Controllers\Currency\CurrencyContainer;
use App\Http\Controllers\Currency\Currency;
use App\Http\Controllers\TransactionItem;
use App\Http\Controllers\Transaction;
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
    protected $description = 'Commission fee calculation: Commission fee is always calculated in the currency of the operation. For example, if you withdraw or deposit in US dollars then commission fee is also in US dollars.';
    
    /**
     * Transaction items.
     *
     * @var array<TransactionItem> items list array
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
            //$user = new UserController();
            $csvData = $this->csvToArray($filename, ',');
            if (!is_array($csvData)) {
                $this->error('Check File format');
            }
             /**
            * Add some currencies.
            *
            * We can also process this from Currencies class.
            * To make the process simpler we've added it
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

            foreach ($csvData as $data) {
                list($date, $userId, $accountType, $type, $amount, $currency) = $data;
                $this->items[] = new TransactionItem($data);
            }

            // Process transaction
            $transaction = new Transaction($this->items);
            $transaction->allProcess();

            //$this->info(print_r($this->items));
            $this->info(implode("\n", $transaction->responses));

            //dd($transaction->getTransactions());
            \Log::info(get_class($this) . ': End process');
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            //throw $th;
            $this->error($th->getMessage());
        }
    }
}
