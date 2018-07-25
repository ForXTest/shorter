URL Shortener
=========

## Requirements
- Ubuntu 16.04
- Docker
- Docker Compose

## Installing Docker
- First, add the GPG key for the official Docker repository to the system:

`sudo apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 --recv-keys 58118E89F3A912897C070ADBF76221572C52609D`

- Add the Docker repository to APT sources:

`sudo apt-add-repository 'deb https://apt.dockerproject.org/repo ubuntu-xenial main'`

- Next, update the package database with the Docker packages from the newly added repo:

`sudo apt-get update`

- Make sure you are about to install from the Docker repo instead of the default Ubuntu 16.04 repo:

`apt-cache policy docker-engine`

- Finally, install Docker:

`sudo apt-get install -y docker-engine`

- Docker should now be installed, the daemon started, and the process enabled to start on boot. Check that it's running:

`sudo systemctl status docker`


## Installing Docker Compose
- Install Docker Compose

`sudo curl -L https://github.com/docker/compose/releases/download/1.22.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose`

- Next set the permissions:

`sudo chmod +x /usr/local/bin/docker-compose`

- Then verify that the installation was successful by checking the version:

`docker-compose --version`

This will print out the version we installed:

`Output
docker-compose version 1.22.0, build 8dd22a9
`
## Install Application
1. Run server `sudo docker-compose up`
2. Add to file _/etc/hosts_ next string:


`127.0.0.1       shortener.local`

3. Open in your browser url http://shortener.local/
