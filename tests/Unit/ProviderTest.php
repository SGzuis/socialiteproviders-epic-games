<?php

namespace SocialiteProviders\EpicGames\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use SocialiteProviders\EpicGames\Provider;

class ProviderTest extends TestCase
{
    public function test_it_builds_the_epic_games_authorization_url(): void
    {
        $provider = $this->makeProvider();

        $response = $provider->stateless()->redirect();

        $this->assertStringContainsString('https://www.epicgames.com/id/authorize', $response->getTargetUrl());
        $this->assertStringContainsString('client_id=client-id', $response->getTargetUrl());
        $this->assertStringContainsString('redirect_uri=https%3A%2F%2Fexample.test%2Fcallback', $response->getTargetUrl());
        $this->assertStringContainsString('response_type=code', $response->getTargetUrl());
        $this->assertStringContainsString('scope=basic_profile', $response->getTargetUrl());
    }

    public function test_it_maps_user_from_token_and_profile_endpoint(): void
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'sub' => '1234567890',
                'preferred_username' => 'epic-gamer',
                'name' => 'Epic Gamer',
                'email' => 'gamer@example.test',
            ])),
        ]));

        $provider = $this->makeProvider();
        $provider->setHttpClient(new Client(['handler' => $handler]));

        $user = $provider->userFromToken('access-token');

        $this->assertSame('1234567890', $user->getId());
        $this->assertSame('epic-gamer', $user->getNickname());
        $this->assertSame('Epic Gamer', $user->getName());
        $this->assertSame('gamer@example.test', $user->getEmail());
        $this->assertNull($user->getAvatar());
        $this->assertSame('access-token', $user->token);
    }

    private function makeProvider(): Provider
    {
        $request = Request::create('/callback');
        $provider = new Provider($request, 'client-id', 'client-secret', 'https://example.test/callback');

        return $provider;
    }
}
