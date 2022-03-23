<?php

namespace App\Tests\Application;

use App\Factories\AddressFactory;
use App\Tests\PrepareTestDatabase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressControllerTest extends WebTestCase
{
    public function testListPageHasATitle(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Address List');
    }
    public function testListPageHasATableOfAddressesAndLinks(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
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
        PrepareTestDatabase::load();
        $first=0;
        $rows=7;
        $crawler = $client->request('GET', 'first/'.$first.'/rows/'.$rows);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('table');
        $this->assertLessThanOrEqual($rows,count($crawler->filter('tr.table-row')));

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
        PrepareTestDatabase::load();
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
        PrepareTestDatabase::load();
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
    public function testAddNewPageDoseNotAddAddressWithDuplicateEmail(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $existAddress = $addressService->find(1);
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
        $form['address[emailAddress]'] = $existAddress->getEmailAddress();
        $form['address[phoneNumber]'] = $address->getPhoneNumber();
        $form['address[chosenCity]'] = rand(1,10);
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert-error', 'Email Address already exist!');
    }
    public function testUpdatePageShowCorrectAddress(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $address = $addressService->find(1);

        $crawler = $client->request('GET', '/update/id/'.$address->getId());
        $buttonCrawlerNode = $crawler->selectButton('save');

        $form = $buttonCrawlerNode->form();
        $this->assertEquals($form['address[id]']->getValue(),$address->getId());
    }
    public function testUpdatePageUpdateAddress(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $address = $addressService->find(1);

        $crawler = $client->request('GET', '/update/id/'.$address->getId());
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
    public function testUpdatePageDoseNotUpdateWrongAddress(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $address = $addressService->find(1);

        $crawler = $client->request('GET', '/update/id/'.$address->getId());
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
        $this->assertSelectorTextContains('div.alert-error', 'Failure during update address!');
    }
    public function testUpdatePageDoesNotUpdateAddressWithDuplicateEmail(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $address = $addressService->find(1);
        $secondAddress = $addressService->find(2);

        $crawler = $client->request('GET', '/update/id/'.$address->getId());
        $buttonCrawlerNode = $crawler->selectButton('save');

        $form = $buttonCrawlerNode->form();
        $form['address[firstName]'] = $address->getFirstName();
        $form['address[lastName]'] = $address->getLastName();
        $form['address[streetAndNumber]'] = $address->getStreetAndNumber();
        $form['address[zip]'] = $address->getZip();
        $form['address[birthday][day]']->select((int)date('d' ,$address->getBirthday()->getTimestamp()));
        $form['address[birthday][month]']->select((int)date('m' ,$address->getBirthday()->getTimestamp()));
        $form['address[birthday][year]']->select((int)date('Y' ,$address->getBirthday()->getTimestamp()));
        $form['address[emailAddress]'] = $secondAddress->getEmailAddress();
        $form['address[phoneNumber]'] = $address->getPhoneNumber();
        $form['address[chosenCity]'] = rand(1,10);
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert-error', 'Email Address already exist!');
    }
    public function testDeletePageFetchCorrectAddress(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $addressList = $addressService->getAll();
        $address = $addressService->find($addressList[count($addressList)-1]->getId());

        $crawler = $client->request('GET', '/delete/id/'.$address->getId());
        $buttonCrawlerNode = $crawler->selectButton('delete');

        $form = $buttonCrawlerNode->form();
        $this->assertEquals($form['address_delete[id]']->getValue(),$address->getId());
    }
    public function testDeletePageDeleteAddress(): void
    {
        $client = static::createClient();
        PrepareTestDatabase::load();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $addressList = $addressService->getAll();
        $address = $addressService->find($addressList[count($addressList)-1]->getId());

        $crawler = $client->request('GET', '/delete/id/'.$address->getId());
        $buttonCrawlerNode = $crawler->selectButton('delete');
        $form = $buttonCrawlerNode->form();

        $crawler = $client->submit($form);
        $this->assertResponseRedirects();
    }
}
