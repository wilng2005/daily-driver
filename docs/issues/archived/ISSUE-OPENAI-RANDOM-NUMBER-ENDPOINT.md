# OpenAI Random Number Endpoint

## Title
Implement `/api/open-ai/random-number`: Random Integer Generation Endpoint

## Summary
Add a public API endpoint that generates a random integer within a given range. The endpoint should accept optional `min` and `max` parameters (defaulting to a 12-sided die if omitted), and return a JSON response with the generated value. An unauthenticated route should also expose the OpenAPI schema for this endpoint.

## Context
- There is a need for a simple, public endpoint to generate random numbers, useful for dice rolls or other randomization tasks.
- The endpoint should be accessible without authentication and should default to a 12-sided die roll if no parameters are provided.
- The OpenAPI schema for this endpoint should be available at a dedicated, unauthenticated route.

## Implementation Plan
1. **Create new controller** (e.g. `OpenAiController`) with:
   - `randomNumber(Request $request)` method for handling the endpoint.
   - `randomNumberSchema()` method for serving the OpenAPI schema.
2. **Routing:**
   - `GET /api/open-ai/random-number` — accepts optional `min` and `max` query parameters, returns a random integer in JSON.
   - `GET /api/open-ai/random-number/schema` — returns OpenAPI schema for the endpoint.
   - Both routes require no authentication.
3. **Validation:**
   - If provided, `min` and `max` must be integers and `min <= max`.
   - Return appropriate validation errors for invalid input.
4. **Testing:**
   - Write a feature test to cover:
     - Default behavior (12-sided die)
     - Custom ranges
     - Edge cases (`min == max`, invalid input, etc.)
     - Unauthenticated access
   - Ensure 100% code coverage.
5. **Documentation:**
   - Update README and OpenAPI schema.
   - Add usage examples.
6. **Issue Management:**
   - Track implementation status and closure date in this file.

## Status
- [x] Drafted
- [x] In Progress
- [x] Code Complete
- [x] Tests Complete
- [x] Documentation Complete
- [x] Closed

---

**Closure Note:**
This feature, including endpoint implementation, validation, OpenAPI schema, automated tests, and documentation (README and ChatGPT usage guide), was completed and merged on 2025-05-21.
