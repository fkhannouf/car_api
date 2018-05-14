# Introduction
This PHP project is a graphQL experiment.

It acts as a proxy between a graphQL consumer and the CarQueryApi.

A local CSV file is also acting as a data source for cars data.
It supports actually a few queries.

# Installation
You need to have a typical LAMP stack to run this project.  
The easiest way to do it is to use [Vagrant](https://www.vagrantup.com) to setup your environment.

Firstly, add an entry `car-api.local` pointing to 127.0.0.1 to the /etc/hosts file of your local machine.

Assuming Vagrant is installed, copy/paste the snippet below into create a file named `Vagrantfile` put in a dedicated folder.

    Vagrant.configure("2") do |config|
      config.vm.box = "fkhannouf/UbuntuServer18.04LTS-BionicBeaver-LAMP7.2"
      config.vm.box_version = "0.0.1"
      config.vm.network "forwarded_port", guest: 80, host: 8080
      startupScript = <<-SCRIPT
        apt-get update
        while [ "$?" != "0" ]; do
          sleep 5
          apt-get update
          apt-get -q --yes install php7.2-mbstring
        done
      SCRIPT
      config.vm.provision "shell", inline: startupScript
    end
    
When the setup is done, you can log into the vagrant box by typing :`vagrant ssh`

From here you can clone the project using :

`git clone https://github.com/fkhannouf/car_api.git`

Run install the project dependcies :
`composer install -d car_api`
`composer dump-autoload -o -d car_api`

Now, get into the project directory then create a symlink in /var/www :
`sudo ln -s /home/vagrant/car_api /var/www/`

Create an Apache VirtualHost configuration file at `/etc/apache2/sites-enabled/car-api.conf` using the command below :

    printf "<VirtualHost *:80>\nServerName car-api.local\nDocumentRoot /var/www/car_api\nErrorLog ${APACHE_LOG_DIR}/error.log\nCustomLog ${APACHE_LOG_DIR}/access.log combined\n</VirtualHost>" | sudo tee /etc/apache2/sites-enabled/car-api.conf

You can restart the Apache server :  
`sudo service apache2 restart`

# Running

The service is now reachable at `http://car-api.local:8080`

# Tests
Here you'll find some typical queries that are supported in both GraphQL and Curl form :

**Get Makes (manufacturer) list**

    { 
      makes {
        name
      }
    }

or

    curl --request POST \
      --url 'http://car-api.local:8080/?=' \
      --header 'content-type: application/json' \
      --data '{"query":"{\n\tmakes {\n\t\tname\n\t}\n}"}'

**Get Makes with name starting with "c"**

    {
      makes(startingWith:"c") {
	    name
      }
    }

or

    curl --request POST \
      --url 'http://car-api.local:8080/?=' \
      --header 'content-type: application/json' \
      --data '{"query":"{\n\tmakes(startingWith:\"c\") {\n\t\tname\n\t}\n}"}'

**Get cars with total cost of ownership < 3000**

    {
      cars(tcoBelow:3000) {
        make
        model
        trim
        tco
      }
    }

or

    curl --request POST \
      --url 'http://car-api.local:8080/?=' \
      --header 'content-type: application/json' \
      --data '{"query":"{\n\tcars(tcoBelow:3000) {\n\t\tmake\n\t\tmodel\n\t\ttrim\n\t\ttco\n\t}\n}"}'

**Get cars with total cost of ownership > 5000**

    {
      cars(tcoAbove:5000) {
        make
        model
        trim
        tco
      }
    }

or

    curl --request POST \
      --url 'http://car-api.local:8080/?=' \
      --header 'content-type: application/json' \
      --data '{"query":"{\n\tcars(tcoAbove:5000) {\n\t\tmake\n\t\tmodel\n\t\ttrim\n\t\ttco\n\t}\n}"}'

**Get cars for a specified manufacturer**

    {
      cars(make:"Peugeot") {
        make
		model
		trim
		tco
      }
    }

or

    curl --request POST \
    --url http://car-api.local:8080/ \
    --header 'content-type: application/json' \
    --data '{"query":"{\n  cars(make: \"Peugeot\") {\n    make\n    model\n    trim\n    tco\n  }\n}\n"}'

