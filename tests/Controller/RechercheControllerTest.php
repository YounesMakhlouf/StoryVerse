<?php

namespace App\Tests\Controller;

use App\Entity\Story;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RechercheControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $testUser;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        // Create test data
        $this->testUser = new User();
        $this->testUser->setEmail('search_test_' . uniqid() . '@example.com');
        $this->testUser->setPassword('password123');
        $this->testUser->setFirstName('John');
        $this->testUser->setLastName('Doe');
        $this->testUser->setUsername('searchable_user_' . uniqid());
        $this->testUser->setGender('male');
        $this->testUser->setBio('Test user bio');
        $this->testUser->setAvatar('default.jpg');
        $this->testUser->setLastLoginDate(new DateTime());
        $this->testUser->setRoles(['ROLE_USER']);

        $story = new Story();
        $story->setTitle('Searchable Story Title');
        $story->setLanguage('english');
        $story->setStatus('pending');
        $story->setGenre('Fiction');
        $story->setStoryImage('Fiction.jpg');

        $this->entityManager->persist($this->testUser);
        $this->entityManager->persist($story);
        $this->entityManager->flush();
    }

    public function testSearchUsers(): void
    {
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/search', ['searchQuery' => 'searchable_user']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card-title');
        $this->assertSelectorTextContains('.card-title', 'searchable_user');
    }

    public function testSearchStories(): void
    {
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/search', ['searchQuery' => 'Searchable Story']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.card-title');
        $this->assertSelectorTextContains('.card-title', 'Searchable Story Title');
    }

    public function testEmptySearchResults(): void
    {
        $this->client->loginUser($this->testUser);
        $crawler = $this->client->request('GET', '/search', ['searchQuery' => 'nonexistent']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.container');

        // Get all h2 elements
        $h2Elements = $crawler->filter('h2');
        $this->assertEquals(2, $h2Elements->count());
        $this->assertEquals('No users found.', trim($h2Elements->first()->text()));
        $this->assertEquals('No stories found.', trim($h2Elements->last()->text()));
    }

    public function testSearchWithoutQuery(): void
    {
        $this->client->loginUser($this->testUser);
        $crawler = $this->client->request('GET', '/search');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Search Results');
        $this->assertSelectorExists('.search-results');

        // Get all h2 elements
        $h2Elements = $crawler->filter('h2');
        $this->assertEquals(2, $h2Elements->count());
        $this->assertEquals('No users found.', trim($h2Elements->first()->text()));
        $this->assertEquals('No stories found.', trim($h2Elements->last()->text()));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up the database
        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }
}