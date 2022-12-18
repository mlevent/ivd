<?php declare(strict_types=1); error_reporting(E_ALL); require dirname(__DIR__).'/vendor/autoload.php';

use Mlevent\Ivd\IvdException;
use Mlevent\Ivd\IvdService;

try {

    $ivd = (new IvdService())->login();
   
    var_dump($ivd->getTaxList());

    $ivd->logout();

} catch(IvdException $e){
    
    print_r($e->getMessage());
    print_r($e->getResponse());
    print_r($e->getRequest());
}