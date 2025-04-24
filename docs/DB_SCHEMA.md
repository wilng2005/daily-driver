# Database Schema Documentation

This document describes the current database schema as defined by the project's migrations. It includes all tables, columns, types, and key relationships.

---

## users
| Column                  | Type        | Attributes                |
|-------------------------|-------------|---------------------------|
| id                      | BIGINT      | Primary, auto-increment   |
| name                    | STRING      |                           |
| email                   | STRING      | Unique                    |
| email_verified_at       | TIMESTAMP   | Nullable                  |
| password                | STRING      |                           |
| remember_token          | STRING      | Nullable                  |
| user_resource_access    | STRING(100) | Default: 'None'           |
| capture_resource_access | STRING(100) | Default: 'None'           |
| created_at              | TIMESTAMP   |                           |
| updated_at              | TIMESTAMP   |                           |

---

## password_resets
| Column      | Type      | Attributes |
|-------------|-----------|------------|
| email       | STRING    | Indexed    |
| token       | STRING    |            |
| created_at  | TIMESTAMP | Nullable   |

---

## failed_jobs
| Column      | Type      | Attributes                |
|-------------|-----------|---------------------------|
| id          | BIGINT    | Primary, auto-increment   |
| uuid        | STRING    | Unique                    |
| connection  | TEXT      |                           |
| queue       | TEXT      |                           |
| payload     | LONGTEXT  |                           |
| exception   | LONGTEXT  |                           |
| failed_at   | TIMESTAMP | Default: current timestamp|

---

## personal_access_tokens
| Column         | Type      | Attributes                |
|----------------|-----------|---------------------------|
| id             | BIGINT    | Primary, auto-increment   |
| tokenable_id   | BIGINT    | Morphs                    |
| tokenable_type | STRING    | Morphs                    |
| name           | STRING    |                           |
| token          | STRING(64)| Unique                    |
| abilities      | TEXT      | Nullable                  |
| last_used_at   | TIMESTAMP | Nullable                  |
| created_at     | TIMESTAMP |                           |
| updated_at     | TIMESTAMP |                           |

---

## captures
| Column         | Type      | Attributes                |
|----------------|-----------|---------------------------|
| id             | BIGINT    | Primary, auto-increment   |
| created_at     | TIMESTAMP |                           |
| updated_at     | TIMESTAMP |                           |
| deleted_at     | TIMESTAMP | Soft deletes, nullable    |
| name           | TEXT      | Nullable (was 'title')    |
| content        | TEXT      | Nullable                  |
| inbox          | BOOLEAN   | Default: true             |
| capture_id     | BIGINT    | Nullable, self-reference? |
| next_action    | BOOLEAN   | Default: false            |
| priority_no    | INTEGER   | Unsigned, nullable        |
| user_id        | BIGINT    | Nullable, foreign key?    |

---

## telegram_updates
| Column      | Type      | Attributes                |
|-------------|-----------|---------------------------|
| id          | BIGINT    | Primary, auto-increment   |
| created_at  | TIMESTAMP |                           |
| updated_at  | TIMESTAMP |                           |
| data        | JSON      |                           |

---

## telegram_chats
| Column         | Type      | Attributes                |
|----------------|-----------|---------------------------|
| id             | BIGINT    | Primary, auto-increment   |
| created_at     | TIMESTAMP |                           |
| updated_at     | TIMESTAMP |                           |
| deleted_at     | TIMESTAMP | Soft deletes, nullable    |
| tg_chat_id     | BIGINT    |                           |
| data           | JSON      |                           |
| configuration  | JSON      | Nullable                  |

---

## telegram_messages
| Column           | Type      | Attributes                |
|------------------|-----------|---------------------------|
| id               | BIGINT    | Primary, auto-increment   |
| created_at       | TIMESTAMP |                           |
| updated_at       | TIMESTAMP |                           |
| data             | JSON      |                           |
| telegram_chat_id | BIGINT    | Foreign key?              |
| text             | TEXT      |                           |
| is_incoming      | BOOLEAN   |                           |
| is_outgoing      | BOOLEAN   |                           |
| from_username    | STRING(35)|                           |

---

## daily_snapshots
| Column      | Type      | Attributes                |
|-------------|-----------|---------------------------|
| id          | BIGINT    | Primary, auto-increment   |
| date        | DATE      |                           |
| data        | JSON      |                           |
| deleted_at  | TIMESTAMP | Soft deletes, nullable    |
| created_at  | TIMESTAMP |                           |
| updated_at  | TIMESTAMP |                           |

---

## posts
| Column         | Type        | Attributes                |
|----------------|-------------|---------------------------|
| id             | BIGINT      | Primary, auto-increment   |
| deleted_at     | TIMESTAMP   | Soft deletes, nullable    |
| title          | TEXT        | Nullable                  |
| content        | TEXT        | Nullable                  |
| slug           | TEXT        | Nullable                  |
| image_file     | STRING(200) | Nullable                  |
| image_credit   | TEXT        | Nullable                  |
| sequence_code  | STRING      | Nullable                  |
| published_at   | DATETIME    | Nullable                  |
| created_at     | TIMESTAMP   |                           |
| updated_at     | TIMESTAMP   |                           |

---

## tags
| Column         | Type        | Attributes                |
|----------------|-------------|---------------------------|
| id             | BIGINT      | Primary, auto-increment   |
| deleted_at     | TIMESTAMP   | Soft deletes, nullable    |
| name           | TEXT        | Nullable                  |
| content        | TEXT        | Nullable                  |
| slug           | TEXT        | Nullable                  |
| image_file     | STRING(200) | Nullable                  |
| image_credit   | TEXT        | Nullable                  |
| sequence_code  | STRING      | Nullable                  |
| published_at   | DATETIME    | Nullable                  |
| created_at     | TIMESTAMP   |                           |
| updated_at     | TIMESTAMP   |                           |

---

## post_tag (pivot table for posts and tags)
| Column     | Type      | Attributes                |
|------------|-----------|---------------------------|
| id         | BIGINT    | Primary, auto-increment   |
| post_id    | BIGINT    | Nullable, foreign key?    |
| tag_id     | BIGINT    | Nullable, foreign key?    |
| created_at | TIMESTAMP |                           |
| updated_at | TIMESTAMP |                           |

---

### Notes
- Foreign key constraints are implied by naming but not always explicitly defined in migrations. (E.g., `user_id` in captures, `telegram_chat_id` in telegram_messages, etc.)
- Soft deletes are enabled on several tables via `deleted_at`.
- The `captures` table has a possible self-referential relationship via `capture_id`.
- Many tables use Laravel conventions for timestamps and soft deletes.
