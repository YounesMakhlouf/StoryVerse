<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Story;
use App\Factory\StoryFactory;
use App\Factory\UserFactory;
use App\Form\FileType as ProfilePictureType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

final class WorkflowSmokeTest extends WebTestCase
{
    private ?string $uploadDirectory = null;
    private ?string $sourceDirectory = null;

    protected function tearDown(): void
    {
        if ($this->uploadDirectory !== null) {
            (new Filesystem())->remove($this->uploadDirectory);
        }
        if ($this->sourceDirectory !== null) {
            (new Filesystem())->remove($this->sourceDirectory);
        }

        parent::tearDown();
    }

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

    public function testAdminCrudActions(): void
    {
        $client = static::createClient();
        $admin = UserFactory::createOne(['roles' => ['ROLE_ADMIN']]);
        $user = UserFactory::createOne(['roles' => ['ROLE_USER']]);
        $client->loginUser($admin);

        $client->request('GET', '/admin/user');
        self::assertResponseIsSuccessful();
        $client->request('POST', '/admin/user/' . $user->getId() . '/add-admin-role');
        self::assertResponseRedirects();
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->find($user->getId());
        self::assertSame(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());

        $client->request('GET', '/admin/user');
        self::assertResponseIsSuccessful();
        $client->request('POST', '/admin/user/' . $user->getId() . '/remove-admin-role');
        self::assertResponseRedirects();
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->find($user->getId());
        self::assertSame(['ROLE_USER'], $user->getRoles());

        $reportedStory = StoryFactory::createOne([
            'title' => 'Reported workflow ' . bin2hex(random_bytes(4)),
            'IsReported' => true,
        ]);
        $client->request('GET', '/admin/reported-story');
        self::assertResponseIsSuccessful();
        $client->request('POST', '/admin/reported-story/' . $reportedStory->getId() . '/reject-report');
        self::assertResponseRedirects();
        $reportedStory = static::getContainer()->get('doctrine')->getRepository(Story::class)->find($reportedStory->getId());
        self::assertFalse($reportedStory->isIsReported());
    }

    public function testProfileUploadsRequireMatchingImageExtensions(): void
    {
        $client = static::createClient();
        $form = static::getContainer()->get('form.factory')->create(ProfilePictureType::class, null, ['data_class' => null]);
        self::assertTrue($form->has('profilePicture'));

        $user = UserFactory::createOne(['roles' => ['ROLE_USER']]);
        $this->uploadDirectory = static::getContainer()->getParameter('avatar_directory');
        (new Filesystem())->mkdir($this->uploadDirectory);
        $this->sourceDirectory = sys_get_temp_dir() . '/storyverse-upload-source-' . bin2hex(random_bytes(5));
        mkdir($this->sourceDirectory);
        $client->loginUser($user);

        $source = $this->sourceDirectory . '/valid.png';
        copy(__DIR__ . '/../public/uploads/avatar_directory/female.png', $source);
        $crawler = $client->request('GET', '/modify-profile');
        self::assertResponseIsSuccessful();
        $client->submit($crawler->selectButton('Save changes')->form([
            'profile[avatar]' => $source,
        ]));
        self::assertResponseRedirects();
        self::assertCount(1, glob($this->uploadDirectory . '/*'));
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->find($user->getId());
        $avatarAfterValidUpload = $user->getAvatar();

        $source = $this->sourceDirectory . '/mismatched.jpg';
        copy(__DIR__ . '/../public/uploads/avatar_directory/female.png', $source);
        $crawler = $client->request('GET', '/modify-profile');
        $client->submit($crawler->selectButton('Save changes')->form([
            'profile[avatar]' => $source,
        ]));
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Please upload a valid image file (JPEG or PNG).', $client->getResponse()->getContent());
        self::assertCount(1, glob($this->uploadDirectory . '/*'));
        $user = static::getContainer()->get('doctrine')->getRepository(User::class)->find($user->getId());
        self::assertSame($avatarAfterValidUpload, $user->getAvatar());
    }
}
