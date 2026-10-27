<?php

namespace App\Tests;

use App\Entity\User;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class WorkflowSmokeTest extends WebTestCase
{
    public function testRegistrationLoginAndStoryWorkflows(): void
    {
        $client = static::createClient();
        $email = 'workflow-' . bin2hex(random_bytes(5)) . '@example.test';

        $crawler = $client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        $form = $crawler->selectButton('Register')->form();
        $values = $form->getPhpValues();
        $values['registration_form'] = array_replace($values['registration_form'] ?? [], [
            'first_name' => 'Workflow',
            'last_name' => 'Tester',
            'username' => 'wf' . bin2hex(random_bytes(4)),
            'email' => $email,
            'gender' => 'female',
            'password' => ['first' => 'workflow-password', 'second' => 'workflow-password'],
        ]);
        $client->submit($form, $values);
        self::assertResponseRedirects();
        self::assertStringContainsString('/sendEmail/', $client->getResponse()->headers->get('Location'));

        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => $email]);
        self::assertInstanceOf(User::class, $user);
        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('#login')->form([
            'email' => $email,
            'password' => 'workflow-password',
        ]);
        $client->submit($form);
        self::assertResponseRedirects();

        $crawler = $client->request('GET', '/story/new');
        self::assertResponseIsSuccessful();
        $form = $crawler->selectButton('Save changes')->form([
            'story[title]' => 'Workflow test story',
            'story[genre]' => 'Fiction',
            'story[firstContribution]' => 'The first page of the workflow story.',
        ]);
        $client->submit($form);
        self::assertResponseRedirects();
        $storyUrl = $client->getResponse()->headers->get('Location');
        self::assertStringContainsString('/storypage/', $storyUrl);

        $contributor = UserFactory::createOne(['roles' => ['ROLE_USER']]);
        $client->loginUser($contributor);
        $crawler = $client->request('GET', $storyUrl);
        self::assertResponseIsSuccessful();
        $client->submit($crawler->filter('#contribution-form')->form([
            'contribution[content]' => 'A second contribution.',
        ]));
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        $crawler = $client->request('GET', $storyUrl);
        $client->submit($crawler->filter('#comment-form')->form([
            'comment[content]' => 'A workflow comment.',
        ]));
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        $target = UserFactory::createOne(['roles' => ['ROLE_USER']]);
        $client->request('POST', '/profile/' . $target->getId() . '/follow');
        self::assertResponseRedirects('/profile/' . $target->getId());

        $client->request('GET', str_replace('/storypage/', '/pdf/', $storyUrl));
        self::assertResponseIsSuccessful();
        self::assertSame('application/pdf', $client->getResponse()->headers->get('Content-Type'));
        self::assertStringStartsWith('%PDF-', $client->getResponse()->getContent());
    }

    public function testAdminAccessIsRoleProtected(): void
    {
        $client = static::createClient();
        $user = UserFactory::createOne(['roles' => ['ROLE_USER']]);
        $client->loginUser($user);
        $client->request('GET', '/admin');
        self::assertResponseStatusCodeSame(403);

        $admin = UserFactory::createOne(['roles' => ['ROLE_ADMIN']]);
        $client->loginUser($admin);
        $client->request('GET', '/admin');
        self::assertResponseIsSuccessful();
    }
}
