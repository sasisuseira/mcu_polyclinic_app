<?php

namespace Tests\Unit;

use App\Http\Middleware\AuthenticateBroadcastUser;
use App\Events\ForceLogout;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateBroadcastUserTest extends TestCase
{
    public function test_request_without_jwt_cookies_is_rejected(): void
    {
        $request = Request::create('/broadcasting/auth', 'POST');

        $response = (new AuthenticateBroadcastUser())->handle($request, fn () => response('', 204));

        $this->assertSame(401, $response->getStatusCode());
    }

    public function test_valid_jwt_cookie_resolves_the_broadcast_user(): void
    {
        $user = new User();
        $user->setAttribute('id', 17);
        $jwt = Mockery::mock();
        JWTAuth::shouldReceive('setToken')->once()->with('valid-token')->andReturn($jwt);
        $jwt->shouldReceive('authenticate')->once()->andReturn($user);

        $request = Request::create('/broadcasting/auth', 'POST', [], [
            'token_device' => 'valid-token',
            'user_id' => '17',
        ]);

        $response = (new AuthenticateBroadcastUser())->handle($request, function (Request $request) {
            return response()->json(['user_id' => $request->user()->getKey()]);
        });

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(17, $response->getData(true)['user_id']);
    }

    public function test_jwt_cookie_cannot_authenticate_a_different_user_id(): void
    {
        $user = new User();
        $user->setAttribute('id', 17);
        $jwt = Mockery::mock();
        JWTAuth::shouldReceive('setToken')->once()->with('valid-token')->andReturn($jwt);
        $jwt->shouldReceive('authenticate')->once()->andReturn($user);

        $request = Request::create('/broadcasting/auth', 'POST', [], [
            'token_device' => 'valid-token',
            'user_id' => '18',
        ]);

        $response = (new AuthenticateBroadcastUser())->handle($request, fn () => response('', 204));

        $this->assertSame(401, $response->getStatusCode());
    }

    public function test_force_logout_targets_the_users_private_channel(): void
    {
        $event = new ForceLogout(17);

        $this->assertSame('private-users.17', $event->broadcastOn()[0]->name);
        $this->assertSame('force-logout', $event->broadcastAs());
    }
}