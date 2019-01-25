<?php

namespace iMemento\Clients\Tests\Unit;

use iMemento\Clients\Tests\TestCase;

class ClientTest extends TestCase
{
    protected function client(array $config = [])
    {
        return new ClientStub($config);
    }

    /**
     * Mode behaviour tests
     */
    public function testDefaultMode()
    {
        $this->assertEquals('critical', $this->client()->getMode());
    }

    public function testRequestedMode()
    {
        $this->assertEquals('silent', $this->client()->silent()->getMode());
    }

    public function testPreferredMode()
    {
        $this->assertEquals('silent', $this->client()->preferredSilentCall()->getMode());
    }

    public function testModePrecedence()
    {
        $this->assertEquals('critical', $this->client()->critical()->preferredSilentCall()->getMode());
    }

    public function testModeOverwrite()
    {
        $this->assertEquals('silent', $this->client()->critical()->silentCall()->getMode());
    }

    /**
     * Authentication behaviour
     */
    public function codeSamples()
    {
        $client = $this->client();

        // Regular calls
        $client->call();

        // Unauthenticated calls (for publicly available endpoints)
        $client->anonymously()->call();

        // With custom token (for authenticated users)
        $client->withToken($token)->call();

        // Will use the app credentials
        $client->asService()->call();
    }

    /**
     * Async behaviour
     */
    public function otherCodeSamples()
    {
        $client = $this->client();

        $client->call();

        $client->async()->call();
    }

}
