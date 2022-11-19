# Log-and-Limit-Demo-Script-Access-Per-Day-By-IP
If you wish to prevent abuse of your DEMO Applications, Themes and Scripts on your server. This is the code for you.
With this script / Code, its is possible to limit the number of requests that are made to a webpage in the course of a day based on IP address or any other information.

## How to install

1. Download the script
2. Create Table using .sql file provided
3. Edit logger file to include database credentials and whitelist ip's
4. Add following to every script you want to limit. 
```
include_once('../PATH_TO LOGGER FILE/logger.php');
```

