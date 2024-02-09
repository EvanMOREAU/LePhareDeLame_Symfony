<?php

namespace App\Test\Controller;

use App\Entity\RatingService;
use App\Repository\RatingServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RatingServiceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/rating/service/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(RatingService::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('RatingService index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'rating_service[value]' => 'Testing',
            'rating_service[published]' => 'Testing',
            'rating_service[user]' => 'Testing',
            'rating_service[service]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new RatingService();
        $fixture->setValue('My Title');
        $fixture->setPublished('My Title');
        $fixture->setUser('My Title');
        $fixture->setService('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('RatingService');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new RatingService();
        $fixture->setValue('Value');
        $fixture->setPublished('Value');
        $fixture->setUser('Value');
        $fixture->setService('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'rating_service[value]' => 'Something New',
            'rating_service[published]' => 'Something New',
            'rating_service[user]' => 'Something New',
            'rating_service[service]' => 'Something New',
        ]);

        self::assertResponseRedirects('/rating/service/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getValue());
        self::assertSame('Something New', $fixture[0]->getPublished());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getService());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new RatingService();
        $fixture->setValue('Value');
        $fixture->setPublished('Value');
        $fixture->setUser('Value');
        $fixture->setService('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/rating/service/');
        self::assertSame(0, $this->repository->count([]));
    }
}
