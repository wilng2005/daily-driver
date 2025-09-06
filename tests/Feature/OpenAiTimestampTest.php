<?php

namespace Tests\Feature;

use Tests\TestCase;
use Carbon\Carbon;

class OpenAiTimestampTest extends TestCase
{

	/** @test */
	public function it_returns_current_timestamp_in_singapore_by_default()
	{
		$frozenUtc = Carbon::parse('2025-05-21T11:04:55Z');
		Carbon::setTestNow($frozenUtc);

		$response = $this->getJson('/api/open-ai/timestamp');
		$response->assertStatus(200)
			->assertJsonStructure([
				'timezone', 'abbreviation', 'utc_offset', 'iso8601', 'unix', 'rfc2822', 'human', 'utc_iso8601'
			]);

		$sg = $frozenUtc->copy()->setTimezone('Asia/Singapore');
		$utc = $frozenUtc->copy()->setTimezone('UTC');

		$response->assertJson([
			'timezone' => 'Asia/Singapore',
			'iso8601' => $sg->toIso8601String(),
			'unix' => $sg->timestamp,
			'rfc2822' => $sg->toRfc2822String(),
			'utc_iso8601' => $utc->toIso8601String(),
		]);


		$response->assertJson(fn ($json) =>
			$json->where('abbreviation', $sg->format('T'))
				->where('utc_offset', $sg->format('P'))
				->where('human', $sg->format('j M Y, g:i A T'))
				->etc()
		);

		Carbon::setTestNow();
	}

	/** @test */
	public function it_returns_current_timestamp_for_custom_timezone()
	{
		$frozenUtc = Carbon::parse('2025-05-21T11:04:55Z');
		Carbon::setTestNow($frozenUtc);

		$response = $this->getJson('/api/open-ai/timestamp?timezone=America/New_York');
		$response->assertStatus(200)
			->assertJsonStructure([
				'timezone', 'abbreviation', 'utc_offset', 'iso8601', 'unix', 'rfc2822', 'human', 'utc_iso8601'
			]);

		$tz = 'America/New_York';
		$ny = $frozenUtc->copy()->setTimezone($tz);
		$utc = $frozenUtc->copy()->setTimezone('UTC');

		$response->assertJson([
			'timezone' => $tz,
			'iso8601' => $ny->toIso8601String(),
			'unix' => $ny->timestamp,
			'rfc2822' => $ny->toRfc2822String(),
			'utc_iso8601' => $utc->toIso8601String(),
		]);


		$response->assertJson(fn ($json) =>
			$json->where('abbreviation', $ny->format('T'))
				->where('utc_offset', $ny->format('P'))
				->where('human', $ny->format('j M Y, g:i A T'))
				->etc()
		);

		Carbon::setTestNow();
	}

	/** @test */
	public function it_returns_validation_error_for_invalid_timezone()
	{
		$response = $this->getJson('/api/open-ai/timestamp?timezone=Not/AZone');
		$response->assertStatus(422)
			->assertJsonValidationErrors(['timezone']);
	}

	/** @test */
	public function it_is_publicly_accessible()
	{
		$response = $this->getJson('/api/open-ai/timestamp');
		$response->assertStatus(200);
	}
}


