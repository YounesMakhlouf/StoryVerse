// ... existing code ...
        $this->testUser->setUsername('story_tester_' . uniqid());
        $this->testUser->setGender('male');
        $this->testUser->setLastLoginDate(new DateTime());
        $this->testUser->setRoles(['ROLE_USER']);
        $this->testUser->setBio('Test user bio');
        $this->testUser->setAvatar('default.jpg');
// ... existing code ...