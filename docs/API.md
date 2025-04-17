# API Reference

This document describes the available API endpoints for the Daily Driver project.

---

## Authentication

Some endpoints require authentication via Sanctum or an API Token. See endpoint details below.

---

## Endpoints

### 1. Get Authenticated User
- **GET** `/api/user`
- **Auth:** Requires `auth:sanctum` (user authentication)
- **Response:**
    - Authenticated user's data (JSON)

### 2. OpenAPI Schema
- **GET** `/api/open-ai/schema`
- **Description:** Returns the OpenAPI 3.1.0 schema for the Todo List API, including endpoint details and schemas.
- **Response:**
    - OpenAPI schema (JSON)

### 3. Search Todo Items
- **GET** `/api/todos`
- **Auth:** Requires `X-API-Token` header (see below)
- **Query Parameters:**
    - `search` (optional): Search term to filter todo items by name or content
- **Response:**
    - Array of todo items (JSON)
    - Each item includes:
        - `id`: integer
        - `name`: string
        - `content`: string
        - `inbox`: boolean
        - `next_action`: boolean
        - `priority_no`: integer
- **Example Request:**
    ```bash
    curl -H "X-API-Token: <your-token>" "https://<your-domain>/api/todos?search=example"
    ```

### 4. Telegram Webhook (Internal)
- **POST** `/api/telegram/dsYeN7rvWz3sGk88X9X4LbQt/webhook`
- **Description:** Receives Telegram bot webhook updates. Not intended for public API use.

---

## Security
- **Sanctum Auth:**
    - Used for `/api/user` endpoint. Requires user to be authenticated (e.g., via SPA or mobile app).
- **API Token:**
    - Used for `/api/todos` endpoint. Provide your API token in the `X-API-Token` header.
    - Example: `X-API-Token: <your-token-here>`

---

## OpenAPI Schema
- The `/api/open-ai/schema` endpoint returns a machine-readable OpenAPI 3.1.0 schema for this API. This can be used for auto-generating documentation or client code.

---

## Changelog
- 2025-04-17: Initial API documentation created.
