# News Aggregator Backend

This project is a **backend-only Laravel-based News Aggregator API** that provides a personalized newsfeed for users.

---

## Table of Contents

- [Key Features](#key-features)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Local Setup](#local-setup)
- [News Aggregator](#news-aggregator)
  - [Command](#command)
  - [Scheduler](#scheduler)
- [Using the API](#using-the-api)
  - [Generate Documentation](#generate-docs)
  - [API Document URL](#api-documentation-url)
- [Testing](#testing)
- [Special Notes](#special-notes)

---

## **Key Features**

- **Aggregator Service**:
    - Fetch news from multiple sources and aggregate them into local database
  
- **User Authentication**:
    - Register, login, logout, forgot password, password reset

- **Personalized Newsfeed**:
    - Dynamically filtered articles based on user preferences (sources, categories, authors).

- **API-Driven**:
    - RESTful API endpoints for managing user preferences and fetching articles.
    - Fully documented API with Postman and OpenAPI formats and online API tester.

- **Search and Filters**:
    - Comprehensive filtering and sorting system for articles, categories, authors and news sources.

- **Dockerized Setup**:
    - Easy setup with Docker Compose for running Laravel, MySQL, Redis, and Scheduler.

---

## **Getting Started**

### Prerequisites
- Docker and Docker Compose installed.

### Local Setup
1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <project-folder>

2. Copy `.env.example` file to `.env`. docker will create database based on this credentials
    ```env
    DB_CONNECTION=mysql
    DB_HOST=mysql // this host should match with the docker-compose file
    DB_PORT=3306
    DB_DATABASE=news_backend
    DB_TEST_DATABASE=news_backend_test // second database for testing
    DB_USERNAME=db_user
    DB_PASSWORD=db_password

    REDIS_CLIENT=predis
    REDIS_HOST=redis-cache // this host should match with the docker-compose file
    REDIS_PASSWORD=redis_password
    REDIS_PORT=6379
   
3. Build and start Docker containers:
   ```bash
   docker-compose build
   docker-compose up -d
   
4. Access the Laravel application container:
   ```bash
   docker-compose exec laravel_app bash

5. Seed the database: migration is automatically done in the build step
    ```bash
   php artisan db::seed

## **News Aggregator**

### Command

- **Fetch News**:
    ```bash
    php artisan news::fetch

### Scheduler
- This command is scheduled to run every hour to fetch news.

## **Using the API**

### Generate Docs

- **Generate API Documentation**: documentation is already generated, but you can generate it again:
   ```bash
   php artisan scribe:generate

### API Documentation URL:

- Online API Documentation: http://localhost:8080/docs
- Postman Collection: http://localhost:8080/docs/collection.json
- OpenAPI Spec:  http://localhost:8080/docs/openapi.yaml

## **Testing**
- **Run Tests**:

   ```bash
   php artisan test

---

## **Special Notes**

### Backend-Only Project:
- **Web Routes Removed**: This project does not include web routes; it's purely API-driven.
- **No API Prefix**: API routes are not prefixed, making endpoints easier to access.
- **Sessions Removed**: No session is needed in stateless APIs.

### Password Reset:
- The password reset token can be found in the application logs.

