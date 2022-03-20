<?php

namespace App\Tests\Application;

use App\Factories\AddressFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressControllerTest extends WebTestCase
{
    public function testListPageHasATitle(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Address List');
    }
    public function testListPageHasATableOfAddressesAndLinks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('table');

        $this->assertCount(1, $crawler->selectLink('Add'));
        $this->assertCount(1, $crawler->selectLink('Edit'));
        $this->assertCount(1, $crawler->selectLink('Delete'));
    }
    public function testListPageHasATableOfAddressesWithLimitation(): void
    {
        $client = static::createClient();
        $first=0;
        $rows=7;
        $crawler = $client->request('GET', '/'.$first.'/'.$rows);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('table');
        $this->assertLessThan($rows,count($crawler->filter('tr.table-row')));

    }
    public function testAddNewPageHasForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/store');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form');
    }
    public function testAddNewPageAddAddress(): void
    {
        $client = static::createClient();
        $address = AddressFactory::create();
        $crawler = $client->request('GET', '/store');
        $buttonCrawlerNode = $crawler->selectButton('save');

        $form = $buttonCrawlerNode->form();
        $form['address[firstName]'] = $address->getFirstName();
        $form['address[lastName]'] = $address->getLastName();
        $form['address[streetAndNumber]'] = $address->getStreetAndNumber();
        $form['address[zip]'] = $address->getZip();
        $form['address[birthday][day]']->select((int)date('d' ,$address->getBirthday()->getTimestamp()));
        $form['address[birthday][month]']->select((int)date('m' ,$address->getBirthday()->getTimestamp()));
        $form['address[birthday][year]']->select((int)date('Y' ,$address->getBirthday()->getTimestamp()));
        $form['address[emailAddress]'] = $address->getEmailAddress();
        $form['address[phoneNumber]'] = $address->getPhoneNumber();
        $form['address[chosenCity]'] = rand(1,10);
        $crawler = $client->submit($form);
        $this->assertResponseRedirects();
    }
    public function testAddNewPageDoseNotAddWrongAddress(): void
    {
        $client = static::createClient();
        $address = AddressFactory::create();
        $crawler = $client->request('GET', '/store');
        $buttonCrawlerNode = $crawler->selectButton('save');

        $form = $buttonCrawlerNode->form();
        $form['address[firstName]'] = 456;
        $form['address[lastName]'] = $address->getLastName();
        $form['address[streetAndNumber]'] = $address->getStreetAndNumber();
        $form['address[zip]'] = $address->getZip();
        $form['address[birthday][day]']->select((int)date('d' ,$address->getBirthday()->getTimestamp()));
        $form['address[birthday][month]']->select((int)date('m' ,$address->getBirthday()->getTimestamp()));
        $form['address[birthday][year]']->select((int)date('Y' ,$address->getBirthday()->getTimestamp()));
        $form['address[emailAddress]'] = $address->getEmailAddress();
        $form['address[phoneNumber]'] = $address->getPhoneNumber();
        $form['address[chosenCity]'] = rand(1,10);
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert-error', 'Failure during register address!');
    }
}
