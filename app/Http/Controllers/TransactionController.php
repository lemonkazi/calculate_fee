<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\Transaction\Transaction;

class TransactionController extends Controller
{

    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Set items for transactions 
     * 
     * @param string $items
     * 
     * @return class Transaction
     */
    public function transaction($items) {
        return new Transaction($items);
    }
}