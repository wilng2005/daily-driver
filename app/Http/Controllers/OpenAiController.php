<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class OpenAiController extends Controller
{
    /**
     * Generate a random integer between min and max (inclusive).
     * Defaults to a 12-sided die if no parameters are provided.
     */
    public function randomNumber(Request $request): JsonResponse
    {
        $validated = validator($request->query(), [
            'min' => ['nullable', 'integer'],
            'max' => ['nullable', 'integer'],
        ])->validate();

        $min = $validated['min'] ?? 1;
        $max = $validated['max'] ?? 12;

        if ($min > $max) {
            return response()->json([
                'message' => 'The min must be less than or equal to max.',
                'errors' => ['min' => ['The min must be less than or equal to max.']]
            ], 422);
        }

        $random = random_int($min, $max);
        return response()->json(['random' => $random]);
    }

    /**
     * Return OpenAPI schema for the random number endpoint.
     */
    public function randomNumberSchema(): JsonResponse
    {
        //@codeCoverageIgnoreStart
        return response()->json([
            'openapi' => '3.1.0',
            'info' => [
                'title' => 'OpenAI Random Number API',
                'description' => 'Generate a random integer within a given range. Defaults to a 12-sided die.',
                'version' => 'v1.0.0',
            ],
            'servers' => [
                [
                    'url' => config('app.url') // This will use your Laravel app URL
                ]
            ],
            'paths' => [
                '/api/open-ai/random-number' => [
                    'get' => [
                        'operationId' => 'getRandomNumber',
                        'summary' => 'Generate random integer',
                        'parameters' => [
                            [
                                'name' => 'min',
                                'in' => 'query',
                                'description' => 'Minimum value (inclusive)',
                                'required' => false,
                                'schema' => ['type' => 'integer', 'default' => 1],
                            ],
                            [
                                'name' => 'max',
                                'in' => 'query',
                                'description' => 'Maximum value (inclusive)',
                                'required' => false,
                                'schema' => ['type' => 'integer', 'default' => 12],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Random integer response',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'random' => [
                                                    'type' => 'integer',
                                                    'description' => 'Randomly generated integer',
                                                ],
                                            ],
                                            'example' => ['random' => 7],
                                        ],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation error',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string'],
                                                'errors' => ['type' => 'object'],
                                            ],
                                            'example' => [
                                                'message' => 'The min must be less than or equal to max.',
                                                'errors' => ['min' => ['The min must be less than or equal to max.']],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        //@codeCoverageIgnoreEnd
    }

    /**
     * Return current timestamp info. Defaults to Asia/Singapore; accepts optional IANA timezone.
     */
    public function timestamp(Request $request): JsonResponse
    {
        $validated = validator($request->query(), [
            'timezone' => ['nullable', 'timezone'],
        ])->validate();

        $tz = $validated['timezone'] ?? 'Asia/Singapore';

        $nowUtc = Carbon::now('UTC');
        $nowLocal = $nowUtc->copy()->setTimezone($tz);

        return response()->json([
            'timezone' => $tz,
            'abbreviation' => $nowLocal->format('T'),
            'utc_offset' => $nowLocal->format('P'),
            'iso8601' => $nowLocal->toIso8601String(),
            'unix' => $nowLocal->timestamp,
            'rfc2822' => $nowLocal->toRfc2822String(),
            'human' => $nowLocal->format('j M Y, g:i A T'),
            'utc_iso8601' => $nowUtc->toIso8601String(),
        ]);
    }

    /**
     * Return OpenAPI schema for the timestamp endpoint.
     */
    public function timestampSchema(): JsonResponse
    {
        //@codeCoverageIgnoreStart
        return response()->json([
            'openapi' => '3.1.0',
            'info' => [
                'title' => 'OpenAI Timestamp API',
                'description' => 'Get the current timestamp in a specified timezone. Defaults to Asia/Singapore.',
                'version' => 'v1.0.0',
            ],
            'servers' => [
                [
                    'url' => config('app.url')
                ]
            ],
            'paths' => [
                '/api/open-ai/timestamp' => [
                    'get' => [
                        'operationId' => 'getTimestamp',
                        'summary' => 'Get current timestamp',
                        'parameters' => [
                            [
                                'name' => 'timezone',
                                'in' => 'query',
                                'description' => 'IANA timezone name (e.g., Asia/Singapore)',
                                'required' => false,
                                'schema' => ['type' => 'string', 'default' => 'Asia/Singapore'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Timestamp response',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'timezone' => ['type' => 'string'],
                                                'abbreviation' => ['type' => 'string'],
                                                'utc_offset' => ['type' => 'string'],
                                                'iso8601' => ['type' => 'string', 'format' => 'date-time'],
                                                'unix' => ['type' => 'integer'],
                                                'rfc2822' => ['type' => 'string'],
                                                'human' => ['type' => 'string'],
                                                'utc_iso8601' => ['type' => 'string', 'format' => 'date-time'],
                                            ],
                                            'example' => [
                                                'timezone' => 'Asia/Singapore',
                                                'abbreviation' => 'SGT',
                                                'utc_offset' => '+08:00',
                                                'iso8601' => '2025-05-21T19:04:55+08:00',
                                                'unix' => 1747835095,
                                                'rfc2822' => 'Wed, 21 May 2025 19:04:55 +0800',
                                                'human' => '21 May 2025, 7:04 PM SGT',
                                                'utc_iso8601' => '2025-05-21T11:04:55+00:00',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Validation error',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string'],
                                                'errors' => ['type' => 'object'],
                                            ],
                                            'example' => [
                                                'message' => 'The timezone field must be a valid timezone.',
                                                'errors' => ['timezone' => ['The timezone field must be a valid timezone.']],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        //@codeCoverageIgnoreEnd
    }
}
