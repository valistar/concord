<?php
// src/Acme/HelloBundle/Controller/HelloController.php
namespace Spelunker\TestBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class TestController
{
public function test1Action()
{
    $name = 'Valistar';
    return new Response('<html><body>Hello '.$name.'!</body></html>');
}
    public function test2Action($duck = 1, $blob)
    {
        return new Response('<html><body>Hello '.$blob.'!</body></html>');
    }
}