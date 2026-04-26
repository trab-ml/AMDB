# Artois Movie Database (AMDB)

AMDB is a web application designed to manage and explore a movie database through a user-friendly interface.

The project demonstrates fullstack fundamentals, including user authentication, database interaction, and dynamic content rendering.

## Overview

The application allows users to:

* Browse a catalog of movies
* Authenticate via a login system
* Interact with movie-related data through a structured interface

It focuses on combining backend logic with a clean frontend to deliver a functional and accessible user experience.

---

## Features

* **User Authentication**

  * Login system with predefined credentials
  * User registration functionality

* **Movie Catalog**

  * Display and exploration of movie data
  * Structured database-driven content

* **Web Interface**

  * Simple and functional UI
  * Server-side rendering with PHP

---

## Tech Stack

* **PHP** — Backend development
* **SQLite** — Lightweight database management
* **HTML / CSS** — Interface structure and styling
* **JavaScript** — Client-side interactions

---

## Setup & Configuration

### Permissions

```bash id="2d5h7k"
chmod 775 db_driver.sql
chmod 775 movie_database.db
```

### Start Local Server

```bash id="k39fsl"
sudo ./manager-linux-x64.run
```

---

## Access

Once the server is running, navigate to:

```
src/php/home.php
```

### Demo Credentials

```bash id="7sjf0p"
username: admin
password: progWeb2_
```

You can also create a new account via the registration feature.

---

## Interface Preview

* Homepage and navigation interface
* Server initialization via local environment

---

## Improvements (Ongoing)

* Refactor styling to leverage Bootstrap more effectively
* Enhance UI/UX for better usability and responsiveness
* Complete and stabilize remaining features
