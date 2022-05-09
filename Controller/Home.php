<?php
namespace Controller;

use Exception;

class Home extends \Framework\Controller
{

    /**
     * @throws Exception
     */
    public function index()
    {

        $this->generateView([
            'person' => [
                'lastname' => 'GILLES'
            ],
            'myDog' => 'Winter'
        ]);
    }

    /**
     * @throws Exception
     */
    public function contact()
    {

        $this->generateView([

        ]);
    }

    /**
     * @throws Exception
     */
    public function signIn()
    {

        $this->generateView([

        ]);
    }

    /**
     * @throws Exception
     */
    public function register()
    {

        $this->generateView([

        ]);
    }

    /**
     * @throws Exception
     */
    public function forgotPassword()
    {

        $this->generateView([

        ]);
    }

}