<a name="readme-top"></a>

<!-- PROJECT SHIELDS -->

[![Contributors][contributors-shield]][contributors-url]
[![LinkedIn][linkedin-shield]][linkedin-url-younes]
[![LinkedIn][linkedin-shield]][linkedin-url-salma]


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/YounesMakhlouf/Projet-Web">
    <img src="assets/img/logo.webp" alt="Logo" width="80" height="80">
  </a>

<h3 align="center">StoryVerse</h3>

  <p align="center">
An innovative platform designed for collaborative storytelling
    <br />
    <a href="https://github.com/YounesMakhlouf/Projet-Web"><strong>Explore the project »</strong></a>
    <br />
    <br />
    <a href="https://github.com/YounesMakhlouf/Projet-Web">View Demo</a>
    ·
    <a href="https://github.com/YounesMakhlouf/Projet-Web/issues">Report Bug</a>
    ·
    <a href="https://github.com/YounesMakhlouf/Projet-Web/issues">Request Feature</a>
  </p>
</div>


<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
    </li>
    <li><a href="#contributing">Contributing and future plans</a></li> 
  </ol>
</details>


<!-- ABOUT THE PROJECT -->
## About The Project

Welcome to StoryVerse, an innovative platform designed for collaborative storytelling. Join forces with fellow storytellers and embark on an extraordinary adventure where each contribution takes the story in unexpected and exciting directions.
### The Power of Collaborative Storytelling
Imagine a world where you can start a story and watch it grow, evolve, and unravel through the creative inputs of a vibrant community. With StoryVerse, storytelling is amplified as individuals from all walks of life come together to weave captivating tales.

Here's how it works: You begin a story with your unique imagination, setting the stage for what's to come. Other writers from around the world then step in, adding their own twists, turns, and surprises. As the story unfolds, witness characters take on a life of their own, plots thicken, and the narrative takes unexpected detours.
### Endless Possibilities
With StoryVerse, the possibilities are endless. Craft tales of adventure, mystery, romance, or delve into the realms of fantasy and science fiction. Let your imagination run wild as you contribute to ongoing stories or initiate new ones.
### Gamified Storytelling Experience
StoryVerse goes beyond just writing. We believe storytelling should be an immersive experience, which is why we've gamified StoryVerse. Earn experience points (XP) as you complete quests, unlocking new tiers and leveling up your storytelling prowess. Challenge yourself and embark on an epic storytelling adventure.
### Stay Connected and Engaged
We've incorporated a robust notification system to keep you connected and engaged. Receive real-time updates when someone interacts with your stories, whether it's likes, comments, or new followers. Stay in the loop and never miss a beat.

<p align="right">(<a href="#readme-top">back to top</a>)</p>


### Built With

* [![Symfony][Symfony.dev]][Symfony-url]
* [![Webpack][Webpack.dev]][Webpack-url]
* [![MySQL][MySQL.dev]][MySQL-url]
* [![Bootstrap][Bootstrap.com]][Bootstrap-url]

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

To get a local copy up and running follow these simple steps.


1. Clone the repo
   ```sh
   git clone https://github.com/github_username/repo_name.git
   ```
2. Install NPM packages
   ```sh
   npm install
   ```
3. Install Composer packages
   ```sh
   composer install
   ```
4. Create a file named .env.local and define the following variables:
APP_SECRET </br>
DATABASE_URL </br>
MAILER_DSN
5. Create a database using Symfony console
    ```sh
    symfony console doctrine:database:create
    ```
6. Create a migration
    ```sh
    symfony console make:migration
    ```
7. Migrate the database:
    ```sh
    symfony console doctrine:migrations:migrate
    ```
8. Load the fixtures:
    ```sh
    symfony console doctrine:fixtures:load
    ```
9. Run Webpack:
    ```sh
    npm run watch
    ```
10. Start the server:
    ```sh
    symfony serve -d
    ```
    
That's it! Enjoy the immersive world of StoryVerse.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTRIBUTING -->
## Contributing and Future Improvements

StoryVerse is a work in progress, and we are committed to continuously improving the platform. </br>
We welcome suggestions and pull requests from the community to make StoryVerse even better.

#### Adventure awaits! 📖✨

<p align="right">(<a href="#readme-top">back to top</a>)</p>


<!-- MARKDOWN LINKS & IMAGES -->
[contributors-shield]: https://img.shields.io/github/contributors/YounesMakhlouf/Projet-Web.svg?style=for-the-badge
[contributors-url]: https://github.com/YounesMakhlouf/Projet-Web/graphs/contributors
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url-younes]: https://www.linkedin.com/in/younes-makhlouf-608321255/
[linkedin-url-salma]: https://www.linkedin.com/in/selma-bouabidi-938b08237/
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[Symfony.dev]: https://img.shields.io/static/v1?style=for-the-badge&message=Symfony&color=000000&logo=Symfony&logoColor=FFFFFF&label=
[Symfony-url]: https://symfony.com/
[Webpack.dev]: https://img.shields.io/static/v1?style=for-the-badge&message=Webpack&color=222222&logo=Webpack&logoColor=8DD6F9&label=
[Webpack-url]: https://webpack.js.org/
[MySQL-url]:https://www.mysql.com/fr/
[MySQL.dev]: https://img.shields.io/static/v1?style=for-the-badge&message=MySQL&color=4479A1&logo=MySQL&logoColor=FFFFFF&label=