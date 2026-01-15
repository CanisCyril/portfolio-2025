# Full Stack Learning Projects (Laravel • VueJS • Redis • Three.js • Docker )

This repository contains multiple learning-focused projects covering **backend architecture, frontend UI, Redis caching, and basic 3D game mechanics using Three.js and Docker**.

---

### Requirements

- PHP 8+
- Composer
- Node.js & npm (LTS Recommened)
- MySQL
- Redis (for mining game caching - this can be ignored for now)

---
## Installation & Setup

```bash

1. Clone the repository
1. Clone Project 
2. Run composer install
3. Run npm install
4. Change .env.example to .env
5. Run php artisan key:generate
6. Fill in database connection settings in .env
7. Run php artisan migrate --seed
8. Run php artisan serve in terminal
9. Run npm run dev in another terminal

10. http://localhost:8000/ Use this link to get to Dashboard, this is just a simple dashboard design and there is not much functionality here. Projects section is temporary and will be replaced once the projects are completed.
11. http://localhost:8000/helpdesk/demo-login  There’s also a reponsive helpdesk I’m working on which currently allows you to create tickets, pick them up, comment, complete, reports, etc. A lot of the frontend in completed, contains a few bugs, backend still needs work and refactoring
12. I started a project to learn the basics of ThreeJS for 3D modelling, it’s a simple mining game which uses redis to cache data. (still quite unfinished but there is some basic functionality in there, e.g. being able to move around and mine rocks). This projects backend follows SOLID principles and contains better example of using services

At the moment manual commands are used but in the future automated queues will handle the redis updates e.g. xp / ore mined updates 

Unfortunately, I was not able to get this ready to be showcased, uploading the assets seems to make the entire system crash of it was best to leave it out with the time constraints.

Link: http://localhost:8000/games/mining
