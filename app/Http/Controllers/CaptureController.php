<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaptureController extends Controller
{
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
