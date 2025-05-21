<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
}
