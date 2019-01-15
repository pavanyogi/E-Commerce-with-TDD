<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Controller\CalculatorController;
use PHPUnit\Framework\TestCase;
use AppBundle\Entity\Customer;
use DateTime;

class CalculatorControllerTest extends WebTestCase
{
    public function testAdd()
    {
        $calculator = new CalculatorController();
        $result = $calculator->add(30, 12);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(360, $result);
    }

    /**
     * @dataProvider provideTestParams
     */

    public function testCalculator($testParams)
    {
        /****************The client(), submit(), and all the crawler method returns a crawler object *********/

        // To get a client
        $client = static::createClient();

        // To enable the profiler
        $client->enableProfiler();
        // To request to a client
        $crawler = $client->request('GET', $testParams);

        // To get the _text and href attributes of the crawler node in an array format
        /*$info = $crawler->extract(array('_text', 'href'));
        print_r($info); die();*/

        // To get the href attributes of the crawler node
        /*$data = $crawler->each(function ($node, $i) {
            return $node->attr('href');
        });
        print_r($data); die();*/

        // To select a link and click on it and get the attributes value
        /*$crawlerLink = $crawler->selectLink('google');
        print_r($crawlerLink->getUri()); die();
        print_r($client->click($crawlerLink->getUri())); die();*/

        // Method to filter the nodes with input[type=text] and get the attributes value
        /*$newCrawler = $crawler->filter('input[type=text]');
        print_r($newCrawler->attr('name')); die();*/

        // Another method to select and click on a link
        $link = $crawler
            ->filter('a:contains("google")') // find all links with the text "Greet"
            ->eq(0) // select the second link in the list
            ->link();
        $client->click($link);

        // To get the data regarding internal executions
        $profile = $client->getProfile();
        // print_r($profile); die();
        /*$this->getMockBuilder();*/
        // method of posting a form
        $form = $crawler->selectButton('submit', array('task' => 'Fabien'))->form();
        $crawler = $client->submit($form);

        // another method to select a button and form wrapping the button and submitting the form
        $buttonCrawlerNode = $crawler->selectButton('submit');
        $form = $buttonCrawlerNode->form();
        $form['form[task]'] = 'Fabien';
        $client->submit($form);

        // another method to select a button and form wrapping the button and submitting the form
        $buttonCrawlerNode = $crawler->selectButton('submit');
        $form = $buttonCrawlerNode->form(array(
            'form[task]'    => 'Fabien',
        ));
        $client->submit($form);

        // another method of posting a form
        $client->request('POST', $testParams, array('task' => 'Fabien'));

        // fetching container
        $container = $client->getContainer();

        // inserting a new record to test database
       /* $entityManager = $container->get('doctrine')->getManager();
        $customer = new Customer();
        $customer->setName("nj");
        $customer->setDob(new DateTime("20-11-22 00:00:00"));
        $entityManager->persist($customer);
        $entityManager->flush();*/
        //print_r($client->getHistory()); die();

        // making assertions
        $this->assertCount(1, $crawler->filter('h2'));
        $this->assertContains('Prafulla Meher', $client->getResponse()->getContent());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function provideTestParams()
    {
        return array(
            array('/calculator')
        );
    }
}
