<?php

namespace App\Model;


 abstract class Db
{
    protected $pdo;

     /**
      * Db constructor.
      */
    public function __construct()
    {

        try {
            $this->pdo =  new \PDO("sqlite:". dirname(dirname(__DIR__))."/database");

        } catch (\Exception $e) {
            die('Error : ' . $e->getMessage());
        }
    }



}

