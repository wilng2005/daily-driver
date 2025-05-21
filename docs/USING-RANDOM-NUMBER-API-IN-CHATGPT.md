# Using the OpenAI Random Number API with ChatGPT

This guide explains how to use the `/api/open-ai/random-number` endpoint from this project as a plugin or external tool inside ChatGPT or a similar LLM-powered assistant.

## Overview
- **Endpoint:** `GET /api/open-ai/random-number`
- **Purpose:** Generate a random integer within a specified range (default: 1–12, like a 12-sided die).
- **Public:** No authentication required.

## How to Use in ChatGPT

### 1. Add the API as an Action/Plugin (if supported)
If you are using ChatGPT with plugin or action support (e.g., ChatGPT Plus, GPT-4o, or a custom integration):

- **Base URL:** `https://<your-domain-or-ip>/api/open-ai/random-number`
- **Parameters:**
  - `min` (optional, integer): Minimum value (inclusive)
  - `max` (optional, integer): Maximum value (inclusive)

#### Example Usage Prompts
- "Roll a 12-sided die using the random number API."
- "Get a random number between 5 and 15 using the API."
- "Call the random number endpoint with min=100 and max=200."

#### Example API Calls
- `GET https://<your-domain>/api/open-ai/random-number` → `{ "random": 7 }`
- `GET https://<your-domain>/api/open-ai/random-number?min=10&max=20` → `{ "random": 13 }`

### 2. Instruct ChatGPT to Call the API
If you are using an LLM that can browse or call APIs, you can use prompts like:
- "Use the random number generator API at `/api/open-ai/random-number` to get a random integer between 1 and 100."
- "Show me the result of calling `/api/open-ai/random-number?min=2&max=6`."

### 3. Error Handling
- If `min` > `max`, the API returns HTTP 422 with an error message.
- If parameters are not integers, the API returns HTTP 422 with validation errors.

### 4. OpenAPI Schema
- The endpoint `/api/open-ai/random-number/schema` returns a machine-readable OpenAPI schema for plugin registration or tool auto-discovery.

## Notes
- This endpoint is stateless and public. No authentication or tokens are required.
- You can self-host or deploy this API anywhere accessible to ChatGPT or your LLM.
- For integration with ChatGPT plugins, you may need to provide the OpenAPI schema URL during plugin setup.

---

For more details or troubleshooting, see the [main README](../README.md) or contact the project maintainer.
