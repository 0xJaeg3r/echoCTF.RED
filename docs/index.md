Welcome to the echoCTF.RED documentation!

echoCTF is a pioneer computer security framework, developed by Echothrust Solutions, for developing, running, and maintaining cybersecurity related competitions (such as CTF).

Our main goals for echoCTF include:

* **Completeness** - Provide a complete set of tools and applications to develop, deploy and maintain competitions
* **Modularity** - Each component has a unique and clear role
* **Expandability** - echoCTF's components are designed to permit expansion


The following pages include instructions for installing, configuring and running your own self-hosted echoCTF based events.

## Components
echoCTF.RED is comprised of a few separate components each of witch requires its own installation and configuration depending on the use case and event type.

These components are are as follows:

* Web Interfaces
  * frontend: Web Interface accessible by participants
  * backend: Web Interface and console commands accessible only by administrators
* Docker API Servers: Servers running docker
* Docker Targets: docker containers running as targets that participants attack
* (optional) VPN Gateway server: for remote access


## Project Structure
 * `ansible` => Ansible playbooks for setting up and updating the infrastructure
 * `backend` => Admin interface and commands
 * `contrib` => Various contributed materials
 * `docs` => Documentation folder
 * `frontend` => User interface (what you see at https://echoctf.red/)
 * `nodejs` => Various nodejs scripts for bots and live feeds
 * `schemas` => database schema
 * `themes` => Various theme, images and layout sources