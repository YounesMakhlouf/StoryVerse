<?php

namespace App\Tests\Controller;

use App\Entity\Story;
use App\Entity\User;
use App\Entity\Tier;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $testUser;
    private $otherUser;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        // Create main test user
        $this->testUser = new User();
        $this->testUser->setEmail('profile_test_' . uniqid() . '@example.com');
        $this->testUser->setPassword('password123');
        $this->testUser->setFirstName('John');
        $this->testUser->setLastName('Doe');
        $this->testUser->setUsername('profile_tester_' . uniqid());
        $this->testUser->setGender('male');
        $this->testUser->setLastLoginDate(new DateTime());
        $this->testUser->setRoles(['ROLE_USER']);
        $this->testUser->setBio('Test user bio');
        $this->testUser->setAvatar('default.jpg');

        // Create and set up a tier
        $tier = new \App\Entity\Tier();
        $tier->setName('Test Tier');
        $tier->setXpThreshold(100);
        $tier->setBadge('test_badge.png');
        $this->testUser->setTier($tier);

        // Create another user for testing interactions
        $this->otherUser = new User();
        $this->otherUser->setEmail('other_test_' . uniqid() . '@example.com');
        $this->otherUser->setPassword('password123');
        $this->otherUser->setFirstName('Jane');
        $this->otherUser->setLastName('Smith');
        $this->otherUser->setUsername('other_tester_' . uniqid());
        $this->otherUser->setGender('female');
        $this->otherUser->setLastLoginDate(new DateTime());
        $this->otherUser->setRoles(['ROLE_USER']);
        $this->otherUser->setBio('Other test user bio');
        $this->otherUser->setAvatar('default.jpg');

        $this->entityManager->persist($tier);
        $this->entityManager->persist($this->testUser);
        $this->entityManager->persist($this->otherUser);
        $this->entityManager->flush();
    }

    public function testViewOwnProfile(): void
    {
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/profile/' . $this->testUser->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->testUser->getUsername());
        $this->assertSelectorExists('.profile-container');
    }

    public function testViewOtherUserProfile(): void
    {
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/profile/' . $this->otherUser->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->otherUser->getUsername());
        $this->assertSelectorExists('.follow-button');
    }

    public function testViewUserContributedStories(): void
    {
        // Create a story and contribution
        $story = new Story();
        $story->setTitle('Test Story');
        $story->setLanguage('English');
        $story->setGenre('Fantasy');
        $story->setStoryImage('default.jpg');
        $story->setStatus('published');
        $story->setSlug('test-story');

        $contribution = new \App\Entity\Contribution();
        $contribution->setContent('Test contribution content');
        $contribution->setAuthor($this->testUser);
        $contribution->setStory($story);
        $contribution->setPosition(1);

        $story->addContribution($contribution);
        $this->testUser->addContribution($contribution);

        $this->entityManager->persist($story);
        $this->entityManager->persist($contribution);
        $this->entityManager->flush();

        // View the user's profile
        $this->client->loginUser($this->testUser);
        $crawler = $this->client->request('GET', '/profile/' . $this->testUser->getId());

        $this->assertResponseIsSuccessful();

        // Get the raw response content
        $content = $this->client->getResponse()->getContent();

        // Check if the story data is present in the response
        $this->assertStringContainsString('Test Story', $content);
        $this->assertStringContainsString('Fantasy', $content);
        $this->assertStringContainsString('Test contribution content', $content);
    }

    public function testViewFollowers(): void
    {
        // Make otherUser follow testUser
        $this->testUser->addFollower($this->otherUser);
        $this->entityManager->flush();

        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/followers/' . $this->testUser->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.card-title', $this->otherUser->getUsername());
    }

    public function testViewFollowing(): void
    {
        // Make testUser follow otherUser
        $this->testUser->addFollowing($this->otherUser);
        $this->entityManager->flush();

        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/following/' . $this->testUser->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.card-title', $this->otherUser->getUsername());
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