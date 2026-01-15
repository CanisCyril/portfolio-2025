# Full Stack Learning Projects (Laravel • VueJS • Redis • Three.js • Docker )

This repository contains multiple learning-focused projects covering **backend architecture, frontend UI, Redis caching, and basic 3D game mechanics using Three.js and Docker**.

Please note the portfolio is still at a very early stage.

---

### Requirements

- PHP 8+
- Composer
- Node.js & npm (LTS Recommened)
- MySQL
- Redis (for mining game caching - this can be ignored for now)

---
## Installation & Setup (Simplest Setup without using Docker & WSL)



1. Clone the repository using: ``` git clone https://github.com/CanisCyril/portfolio-2025.git```
2. Run ``` composer install```
3. Run ``` npm install```
4. Change .env.example to .env
5. Fill in database connection settings in .env
6. Run ``` php artisan key:generate```
7. Run ``` php artisan migrate --seed```
8. Run ``` php artisan serve in terminal```
9. Run ``` npm run dev in another terminal```
10. http://localhost:8000/ Use this link to get to Dashboard, this is just a simple dashboard
design and there is not much functionality here. Projects section is temporary and will be
replaced once the projects are completed.



## Projects

### Responsive Helpdesk 

Currently supports:
- Creating tickets
- Picking up / assigning tickets
- Commenting
- Completing tickets
- Light/Dark Theme

Planned Features:
- Real time notifications using websockets
- Complete reporting, exports

Notes:
- Frontend is mostly complete (with a few bugs).  
- Backend still needs refactoring and cleanup.

Link: http://localhost:8000/helpdesk/demo-login


### Simple Mining Game (To learn 3D Modelling - ThreeJS)

Currently supports:
- Movement
- Very basic map build
- Mining Ores
- Caching XP and Ores through Redis (caches to avoid constant db reads/writes)

Planned Features:
- Ability to purchase and equip new equipment
- Automated queues to update cached xp and ore
- Better game/map design
- Change characters

Notes:

Unfortunately, I was not able to get this ready to be showcased,
in time. Something relating to the assets was causing the system 
to crash. However, you will still be able to view the code. 
This projects has better examples of using services and SOLID.

