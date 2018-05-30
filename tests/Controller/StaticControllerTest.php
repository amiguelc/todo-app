<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StaticControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url){
        $client = self::createClient();
        $crawler=$client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('a:contains("Login")')->count());
        $this->assertEquals(1, $crawler->filter('a:contains("Register")')->count());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/about'];
        yield ['/register'];
        yield ['/login'];
    }
}

