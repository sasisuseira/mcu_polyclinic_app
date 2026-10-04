<?php

namespace Tests\Feature;

use Tests\TestCase;

class AssetsFooterTest extends TestCase
{
    public function test_api_base_url_renders_from_cached_application_configuration(): void
    {
        config(['app.api_version_prefix' => '/api/v1']);

        $html = view('includes.assetsfooter')->render();

        $this->assertStringContainsString('var baseurlapi = "'.url('/api/v1').'";', $html);
    }
}