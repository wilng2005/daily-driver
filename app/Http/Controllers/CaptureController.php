<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaptureController extends Controller
{
    /**
     * Store a newly created capture.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'priority_no' => 'nullable|integer|min:0',
            'inbox' => 'sometimes|boolean',
            'next_action' => 'sometimes|boolean',
        ]);

        // Set defaults for optional fields
        $capture = new Capture();
        $capture->name = $validated['name'];
        $capture->content = $validated['content'];
        $capture->priority_no = $validated['priority_no'] ?? null;
        $capture->inbox = array_key_exists('inbox', $validated) ? $validated['inbox'] : true;
        $capture->next_action = array_key_exists('next_action', $validated) ? $validated['next_action'] : true;
        // TECHNICAL DEBT: Hardcoded for single-user environment. See README and issue docs for details.
        $capture->user_id = 1;
        $capture->save();

        return response()->json($capture, 201);
    }

    /**
     * Update the specified capture.
     */
    public function update(Request $request, $id)
    {
        $capture = Capture::findOrFail($id);

        // Validate only permitted fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'priority_no' => 'nullable|integer|min:0',
            'inbox' => 'required|boolean',
            'next_action' => 'required|boolean',
        ]);

        // Only update allowed fields
        $capture->fill(array_intersect_key($validated, array_flip([
            'name', 'content', 'priority_no', 'inbox', 'next_action'
        ])));
        $capture->save();

        return response()->json($capture);
    }
}
