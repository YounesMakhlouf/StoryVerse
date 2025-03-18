<?php

namespace App\Tests\Controller;

use App\Entity\Story;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIndexPageForNonAuthenticatedUser(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome');
        $this->assertSelectorExists('section.trending');
        $this->assertSelectorExists('section.team');
    }

    public function testRedirectForAuthenticatedUser(): void
    {
        // Create a test user with all required fields
        $testUser = new User();
        $testUser->setEmail('test' . uniqid() . '@example.com');
        $testUser->setPassword('password123');
        $testUser->setFirstName('John');
        $testUser->setLastName('Doe');
        $testUser->setUsername('johndoe' . uniqid());
        $testUser->setGender('male');
        $testUser->setBio('Test user bio');
        $testUser->setLastLoginDate(new DateTime());
        $testUser->setRoles(['ROLE_USER']);

        $this->entityManager->persist($testUser);
        $this->entityManager->flush();

        // Log in the user
        $this->client->loginUser($testUser);

        // Make the request
        $this->client->request('GET', '/');

        // Should redirect to browse stories
        $this->assertResponseRedirects('/story/browse');
    }

    public function testTrendingStoriesAreDisplayed(): void
    {
        // Create some test stories with all required fields
        for ($i = 0; $i < 5; $i++) { // Create 5 stories but only 3 should be displayed
            $story = new Story();
            $story->setTitle('Test Story ' . $i);
            $story->setLanguage('english');
            $story->setStatus('pending');
            $story->setGenre('Fiction');
            $story->setStoryImage('Fiction.jpg');

            $this->entityManager->persist($story);
        }
        $this->entityManager->flush();

        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('section.trending');
        $this->assertSelectorExists('section.trending article');
        $this->assertCount(3, $this->client->getCrawler()->filter('section.trending article .col')); // Verify only 3 stories are shown
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