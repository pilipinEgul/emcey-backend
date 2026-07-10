<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The site root redirects visitors to the admin panel.
     */
    public function test_root_redirects_to_admin(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/admin');
    }

    /**
     * The framework health endpoint responds OK.
     */
    public function test_health_check_responds_ok(): void
    {
        $this->get('/up')->assertOk();
    }
}
