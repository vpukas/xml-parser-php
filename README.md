# XML Parser

## Table of Contents
* [Technologies Used](#technologies-used)
* [Setup](#setup)
* [Usage](#usage)
* [Features](#features)
* [Project Status](#project-status)
* [Contact](#contact)

## Technologies Used
- PHP 7.4.3
- Docker 

## Setup
1. You can run application using Docker (localhost:8080): 
   ```
   docker-compose up --build
   ```
## Usage
1. Run application based on instructions in setup section.
2. Go to http://localhost:8080/parse if you want to parse your file, or http://localhost:8080/index if you want to check php.ini settings.
3. To actually parse your file you need to place it in public/uploads folder (on your local machine, it will be moved to docker container automatically) and use its name in the form on the right side of parse page (eg. feed.xml), also specify please your desired output file (eg. feed_out.xml).
4. Processing your file usually takes some time, so wait until info about your paused/active orders shows up on the screen, then you can check out your file in the same directory (public/uploads) named exactly as you specified earlier in the form.
## Features
- Parsing XML files with orders

## Project Status
Project is: _finished_.

## Contact
Created by Valerii Pukas - feel free to contact me!
