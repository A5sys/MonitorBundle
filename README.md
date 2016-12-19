# Simple Monitor Bundle #

This bundle gives some simple statistics about the application usage.

You can easily know, day per day:

 *  how many requests did not end
 *  the slow requests
  * the requests that kills you app performance
 

# Installation #

## Download ##
Download the bundle using composer:

	composer require 'a5sys/simple-monitor-bundle'

## Enable bundle ##
Enable the bundle in your /app/AppKernel.php:

    new A5sys\MonitorBundle\MonitorBundle(),

## Add channel ##
Configure the logger:

	monolog:
	    channels: ['monitor']
	    handlers:        
	        monitor:
        	    type:  rotating_file
	            max_files: 60
        	    path:  "%kernel.logs_dir%/monitor.log"
	            channels: ['monitor']
        	    formatter: monitor.monolog.formatter

Do not forget to remove the channel monitor from your main log if you do not want to spam it.

Example:

	monolog:
	    handlers:
	        main:
	            type: rotating_file
	            max_files: 60
	            path:   "%kernel.logs_dir%/%kernel.environment%.log"
	            level:  debug
	            channels: [!'monitor']

## Routing ##
Set up routing, in your app/config/routing.yml

    monitor_controller:
        resource: "@MonitorBundle/Controller/"
        type:     annotation
        prefix:   /monitor

## Security ##

Set up security, in your app/config/security.yml

    security:
        access_control:
            - { path: ^/monitor/, roles: ROLE_SUPER_ADMIN }
            
## Configuration ##

These are the available configurations with their default value:

	monitor:
	    enable: true #enable or disable the logs
            slow_threshold:
            	warning: 1000 #the requests that needs more than X ms is a slow one
            	error: 3000 #the requests that needs more than X ms is an error, it takes too much time
	types:
		start: true #is the start time for the request log required
		stop: true #is the stop time for the request log required
		duration: true #is the duration for the request log required
		memory: true #is the memory for the request log required
		url: true #is the url for the request log required
		user: true #is the user for the request log required
# Usage #

The monitor logs are in your app/logs folder.

The statistics can be viewed at the url (depending  of your configuration)

	http://yourapp/monitor