## Stratostack Bill Records Generation


The StratoSTACK Billing Portal enables developers, system integrators, managed service providers, and others providing services over the Apache CloudStack platform to offer Instance creation, provisioning, and billing without purchasing a cost prohibitive billing portal.


### Pre-requisites

**Packages**  
Apache2  
MySQL-5.5+  
PHP-5.5.9+  
Rabbitmq-server

### Installation

StratoSTACK uses Composer for dependency management.  See its [Download Guide](https://getcomposer.org/download/) for installation instructions.

#### Ubuntu

**Ubuntu 14.04 Package Dependencies**

	# sudo apt-get install apache2 php5 mysql-server-5.5 rabbitmq-server php5-mysql php5-mcrypt
	
Uncomment the limit in /etc/default/rabbitmq-server

Enable rabbitMQ management interface:

	# rabbitmq-plugins enable rabbitmq_management

**Setup a RabbitMQ user for Cloudstack**

Browse to http://[rabbitmq ip]:15672/.  Click Admin.  Add a username and password for Cloudstack to use to communicate with RabbitMQ.

Configure ACS Management Servers to send events to RabbitMQ

On your management servers, edit /etc/cloudstack/management/META-INF/cloudstack/core/spring-event-bus-context.xml

Add the following:

```
<beans xmlns="http://www.springframework.org/schema/beans"  
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:context="http://www.springframework.org/schema/context"
xmlns:aop="http://www.springframework.org/schema/aop"
xsi:schemaLocation="http://www.springframework.org/schema/beans
http://www.springframework.org/schema/beans/spring-beans-3.0.xsd
http://www.springframework.org/schema/aop http://www.springframework.org/schema/aop/spring-aop-3.0.xsd
http://www.springframework.org/schema/context
http://www.springframework.org/schema/context/spring-context-3.0.xsd">
   <bean id="eventNotificationBus" class="org.apache.cloudstack.mom.rabbitmq.RabbitMQEventBus">
      <property name="name" value="eventNotificationBus"/>
      <property name="server" value="RABBITMQ_SERVER_ADDRESS"/>
      <property name="port" value="5672"/>
      <property name="username" value="USERNAME"/>
      <property name="password" value="PASSWORD"/>
      <property name="exchange" value="cloudstack-events"/>
   </bean>
</beans>
```

**Install StratoSTACK Bill Records Generation server in /var/www/html**

	# composer create-project --prefer-dist 1stel/stratostack-records-generation recordsGen

**Create MySQL database**

	# mysqladmin -u<user> -p create cloud_admin

The server will also need access to the cloud and cloud_usage databases used by your Cloudstack installation.

**Update configuration files**

Edit .env, update the DB, CLOUD_DB and CLOUDUSAGE_DB sections.

Edit config/cloud.php, and set the resource limits for your cloud.  These limits represent the largest instance size your Cloud supports, or the largest size you want to allow to be created.

**Populate the database**

	# php artisan migrate:install
	# php artisan migrate --seed
	
**Update Apache Configuration**

Edit /etc/apache2/sites-enabled/000-default.conf

Change DocumentRoot to /var/www/html/recordsGen/public

Add the following under DocumentRoot:

	<Directory /var/www/html>
		Options FollowSymLinks
		AllowOverride All
	</Directory>

Enable mod_rewrite:

	# a2enmod rewrite

Restart Apache:

	# service apache2 restart
	
**Set permissions**

	# chown www-data.www-data /var/www/html/recordsGen -R
	
**Add event scheduler to cron**

Add the following to your crontab:

    * * * * * root php /var/www/html/recordsGen/artisan schedule:run >> /dev/null 2>&1

**Login to BRG**

Browse to http://<recordsGenServer>/ and login with Username: admin, Password: admin.

**Update Settings**

Click on the Settings section if you aren’t directed there automatically.  Fill in the management server information and click Sync ACS Settings.