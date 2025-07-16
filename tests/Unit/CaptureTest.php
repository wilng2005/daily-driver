<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class CaptureTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_generate_delayed_name_prefix_for_date_adds_prefix(): void
    {
        $result = \App\Models\Capture::generate_delayed_name_prefix_for_date('Task name', '2025-07-20');
        $this->assertSame('2025-07-20 Task name', $result);
    }

    public function test_generate_delayed_name_prefix_for_date_replaces_existing_prefix(): void
    {
        $result = \App\Models\Capture::generate_delayed_name_prefix_for_date('2025-07-16 Old name', '2025-07-20');
        $this->assertSame('2025-07-20 Old name', $result);
    }

    public function test_generate_delayed_name_prefix_for_date_handles_empty_name(): void
    {
        $result = \App\Models\Capture::generate_delayed_name_prefix_for_date('', '2025-07-20');
        $this->assertSame('2025-07-20', $result);
    }

    public function test_generate_delayed_name_prefix_for_date_handles_non_prefixed_name(): void
    {
        $result = \App\Models\Capture::generate_delayed_name_prefix_for_date('Another task', '2025-07-21');
        $this->assertSame('2025-07-21 Another task', $result);
    }
}
