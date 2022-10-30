<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;

class UserController extends Controller
{
    //
    const FREE_OF_CHARGE_AMOUNT = 1000;
    const FREE_OF_CHARGE_CURRENCY = config('global.BASE_CURRENCY');

    const AccountType = [
        "business" => 'BUSINESS',
        "private" => 'PRIVATE'
    ];

    public  $id;
    public  $accountType;

    //protected Money $remain;
    //public  AccountType $accountType;

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
     * Set Account type private or bussines 
     * 
     * @param string $accountType
     * 
     * @return enum AccountType
     */
    public function setAccountType($accountType)
    {
        $this->accountType = self::AccountType[$accountType];

        return $this->accountType;
    }

    

    

    /**
     * Get Remain 
     * 
     * @return Money $remain
     */
    public function getRemain()
    {
        return $this->remain;
    }

    public function removeDate($data)
    {
        $result = (Arr::except($data, current(array_keys($data)))); // delete date row "2014-12-31" no need this
        return $result;
    }

}
