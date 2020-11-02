# ServiceManager
Windows Service Manager

This project is a Website developped in PHP 5.1.

It has a built-in API that can recieve messages from windows services. 
Each message is tracked and stored in the local database. 

If the website does not recieve a message after a certain duration, it assumes something is wrong with the registered service and triggers a warning for the maintainer to go check that service.
