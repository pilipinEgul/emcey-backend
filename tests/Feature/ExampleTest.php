<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The framework health endpoint responds OK.
     */
    public function test_health_check_responds_ok(): void
    {
        $this->get('/up')->assertOk();
    }
}
