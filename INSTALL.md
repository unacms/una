# Installation Guide for the Application using Docker and Composer

This guide outlines the steps required to install and run the application using Docker and Composer.

```bash

git checkout 14.0.0-B1

```

## 1. Installing Composer

Begin by downloading the Docker image for Composer using the following command:

```bash

docker pull composer/composer

```

## 2. Installing PHP Dependencies using Composer
After downloading the Docker image for Composer, run the following command to install the PHP dependencies of the application:

```bash

docker run --rm -it -v "$(pwd):/app" composer/composer install

```

This command will run Composer in a Docker container, and the PHP dependencies will be installed in the current directory.

## 3. Setting Permissions
Before running the Docker containers, ensure that proper permissions are set for directories and files. Follow the instructions below to set permissions:

create file

```bash

vim set_permissions.sh

```

copy the instruction below

```sh

#!/bin/bash
# set_permissions.sh

# Set execute permissions for ffmpeg.exe
chmod +x ./plugins/ffmpeg/ffmpeg.exe

# Set permissions for the specified directories
chmod 777 ./inc
chmod 777 ./cache
chmod 777 ./cache_public
chmod 777 ./logs
chmod 777 ./tmp
chmod 777 ./storage
chmod 777 ./periodic

```

Make sure you are in the root directory of the application.

Make the set_permissions.sh script executable using the command:

```bash

chmod +x set_permissions.sh

```

Run the set_permissions.sh script using the command:

```bash

sudo ./set_permissions.sh

```
This will automatically apply the appropriate permissions for all directories and files specified in the script.

## 4. Running Docker Containers using docker-compose

To run the application, use docker-compose. Make sure you have a properly configured docker-compose.yml file for your application.

```bash

docker-compose up

```

This command will start the Docker containers according to the specifications in the docker-compose.yml file, allowing you to run the application.


This guide details the steps to download Composer using Docker, install PHP dependencies using Composer, and run the application using Docker Compose. The `INSTALL.md` file should serve as a helpful guide for someone looking to install and run your application using Docker and Composer.



