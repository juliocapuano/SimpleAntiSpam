<?php

namespace juliocapuano\SimpleAntiSpam;

use Exception;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use juliocapuano\SimpleAntiSpam\Clients\StopForumSpamClient;

class ValidateIpTest extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    private $valid_ip    = '107.180.104.65'; // Example of a valid IP that is not inherently spam
    private $no_valid_ip = '400.88.200.8'; // Example of an invalid IP format

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
    public function checkValidIp()
    {
        // This test does not use the client for the invalid IP case.
        // It tests the initial filter_var IP validation.
        // For a valid IP, it would proceed to the client, so we mock for that.
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);
        
        $mockClient->shouldReceive('get')
            ->with('ip', $this->valid_ip)
            ->once()
            ->andReturn((object)['appears' => false]); // Assuming not spam for this basic valid IP check

        $result = SimpleAntiSpam::isSpamIp($this->valid_ip);
        $this->assertFalse($result);

        // No mocking needed here as invalid IP format returns true before client call
        SimpleAntiSpam::resetStopForumSpamClient(); // Ensure no mock for this part
        $result = SimpleAntiSpam::isSpamIp($this->no_valid_ip);
        $this->assertTrue($result);
    }

    /**
     * @test
     * @throws Exception
     */
    public function ipIsSpamWhenClientReturnsAppearsTrue()
    {
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);

        $testIp = '1.2.3.4'; // A valid IP format
        $mockClient->shouldReceive('get')
            ->with('ip', $testIp)
            ->once()
            ->andReturn((object)['appears' => true]);

        $this->assertTrue(SimpleAntiSpam::isSpamIp($testIp));
    }

    /**
     * @test
     * @throws Exception
     */
    public function ipIsNotSpamWhenClientReturnsAppearsFalse()
    {
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);

        $testIp = '5.6.7.8'; // A valid IP format
        $mockClient->shouldReceive('get')
            ->with('ip', $testIp)
            ->once()
            ->andReturn((object)['appears' => false]);

        $this->assertFalse(SimpleAntiSpam::isSpamIp($testIp));
    }

    /**
     * @test
     * @throws Exception
     */
    public function ipIsSpamWhenClientReturnsNull()
    {
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);

        $testIp = '9.10.11.12'; // A valid IP format
        $mockClient->shouldReceive('get')
            ->with('ip', $testIp)
            ->once()
            ->andReturnNull();

        $this->assertTrue(SimpleAntiSpam::isSpamIp($testIp));
    }

    /**
     * @test
     * @throws Exception
     */
    public function ipIsSpamWhenClientReturnsObjectWithoutAppearsProperty()
    {
        $mockClient = Mockery::mock(StopForumSpamClient::class);
        SimpleAntiSpam::setStopForumSpamClient($mockClient);

        $testIp = '13.14.15.16'; // A valid IP format
        $mockClient->shouldReceive('get')
            ->with('ip', $testIp)
            ->once()
            ->andReturn((object)['frequency' => 50]); // Missing 'appears'

        $this->assertTrue(SimpleAntiSpam::isSpamIp($testIp));
    }
}
