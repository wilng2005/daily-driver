# Nova Admin Features & Usage

This document provides an overview of the key features and workflows available in the Nova admin panel for this application.

---

## Table of Contents
- [Overview](#overview)
- [Capture Management](#capture-management)
- [Blog Management](#blog-management)
- [Journaling & Telegram Integration](#journaling--telegram-integration)
- [User Management](#user-management)
- [Dashboards & Metrics](#dashboards--metrics)
- [Access Control](#access-control)

---

## Overview
The Nova admin panel is the central hub for productivity, journaling, blogging, and user management. It provides custom actions, lenses, and dashboards tailored for GTD-style workflows and Telegram integration.

---

## Capture Management (Productivity)
- **CRUD for Captures**: Create, view, edit, and organize captures (tasks, notes, or ideas).
- **Fields**: Priority number, name, markdown content, inbox/next action booleans, timestamps, user association.
- **Relationships**: Parent/child captures, user linkage.
- **Lenses**:
  - **InboxCaptures**: Captures marked for the inbox.
  - **NextActionCaptures**: Captures marked as next actions.
- **Actions**:
  - Delay, add/remove to Inbox/Next Action, clean up markdown, refresh/change priority.
- **Metrics**: "ThingsToDo" productivity card.
- **Access Control**: Users with "All" access see all captures; others see only their own.

---

## Blog Management
- **Posts**: Manage blog posts with title, rich content (Trix with S3 file support), slug, image, image credit, sequence code, published date, and tags.
- **Tags**: Manage tags with name, content, slug, image, image credit, sequence code, published date, and related posts.

---

## Journaling & Telegram Integration
- **Telegram Chats**: View/manage Telegram chat records, including chat ID, message counts (recent/total), configuration, raw data, timestamps, and related messages.
- **Actions**: Send message, send sticker, send journal entry, generate summary for chat.
- **Metrics**: Analytics on incoming messages, chat activity, and value achievement.

---

## User Management (Admin)
- **Users**: Manage users with name, email, password, resource access levels, and avatar.
- **Access Control**: "User Resource Access" and "Capture Resource Access" fields restrict what users can see/do.
- **Sidebar Visibility**: Resource visibility depends on user policy.

---

## Dashboards & Metrics
- **Main Dashboard**: Custom dashboard available in sidebar.
- **Metrics**: Productivity and Telegram-related metrics (e.g., things to do, incoming messages per day).

---

## Access Control
- **Role-based Access**: Field-level access for users vs. admins.
- **Policies**: Enforced via Nova fields and Laravel policies.

---

For further details, see the Nova resource files in `app/Nova/` and actions in `app/Nova/Actions/`.
