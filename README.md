# JOURNALENS 

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)

Journalens is a simple website consisting of three features: searching for journals, summarizing journals, and finding journal gaps. The APIs used are Gemini and Semantic Scholar.

> Due to time constraints, only the journal search feature was implemented.

## Features 🔥
* Login & Register
* Input Journal 
* Summarizing Journal
* API & CRUD

## Contributors
- Alan — API & Semantic, Main Project Lead
- Davin — Login Page & Main Idea
- Hamzah — Welcome Page & Main Carry
- Neza — Software Eng Reports
- Reihan — Register Page

### Backend Setup (Laravel) 🔙

```bash
cd C:/laragon/www/JournaLens

composer install
cp .env.example .env

php artisan key:generate
php artisan migrate
php artisan serve
```

### Open in Browser
```
http://127.0.0.1:8000
```

### License

This project is licensed for personal and non-commercial use only.
Commercial use requires permission from the author.