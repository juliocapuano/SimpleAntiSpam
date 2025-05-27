<?php

namespace juliocapuano\SimpleAntiSpam;

use Exception;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use juliocapuano\SimpleAntiSpam\Clients\StopForumSpamClient;

class ValidateEmailTest extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    private $valid_email    = 'example@mail.com';
    private $no_valid_email = 'example.mail.com';

    protected function tearDown(): void
    {
        Mockery::close();
        SimpleAntiSpam::resetStopForumSpamClient();
        parent::tearDown();
    }

    /**
     * @test
     * @throws Exception
     */
    public function checkValidEmail()
    {
        // This test does not use the client, so no mocking needed here.
        // It tests the initial filter_var email validation.
        $result = SimpleAntiSpam::isSpamEmail($this->valid_email);
        $this->assertFalse($result); // A valid email format, not appearing in spam list by default (mocked below)

        $result = SimpleAntiSpam::isSpamEmail($this->no_valid_email);
        $this->assertTrue($result); // Invalid email format should be true (is spam)
    }

    /**
     * @test
     * @throws Exception
     */
    public function emailIsSpamWhenClientReturnsAppearsTrue()
    {
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);

        $testEmail = 'spam@example.com';
        $mockClient->shouldReceive('get')
            ->with('email', $testEmail)
            ->once()
            ->andReturn((object)['appears' => true]);

        $this->assertTrue(SimpleAntiSpam::isSpamEmail($testEmail));
    }

    /**
     * @test
     * @throws Exception
     */
    public function emailIsNotSpamWhenClientReturnsAppearsFalse()
    {
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);

        $testEmail = 'notspam@example.com';
        $mockClient->shouldReceive('get')
            ->with('email', $testEmail)
            ->once()
            ->andReturn((object)['appears' => false]);

        $this->assertFalse(SimpleAntiSpam::isSpamEmail($testEmail));
    }

    /**
     * @test
     * @throws Exception
     */
    public function emailIsSpamWhenClientReturnsNull()
    {
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);

        $testEmail = 'error@example.com';
        $mockClient->shouldReceive('get')
            ->with('email', $testEmail)
            ->once()
            ->andReturnNull();

        $this->assertTrue(SimpleAntiSpam::isSpamEmail($testEmail));
    }

    /**
     * @test
     * @throws Exception
     */
    public function emailIsSpamWhenClientReturnsObjectWithoutAppearsProperty()
    {
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);

        $testEmail = 'malformed@example.com';
        $mockClient->shouldReceive('get')
            ->with('email', $testEmail)
            ->once()
            ->andReturn((object)['frequency' => 10]); // Missing 'appears'

        $this->assertTrue(SimpleAntiSpam::isSpamEmail($testEmail));
    }
}
