# Plan A Assessment

Welcome! This project is designed to determine whether the current electricity usage in a selected zone is primarily based on renewable energy sources or not. The default zone is set to 'DE' (Germany).

To change the default zone, open the IndexController and update the $zone variable in the request parameter on line 20. request('zone', default: 'PL'); 


## Future Improvements
We aim to enhance the project by introducing a feature that allows users to select their desired zone dynamically from a list of zones provided by the API. This will make it easier for users to switch zones and view the corresponding results without modifying the code.


## Requirements  
- PHP >= 8.0  
- Docker (if using Laravel Sail)  
- Composer  

---

## Installation  
```
1. Navigate to your workspace:  
   cd [YOUR WORKSPACE]
    
2. Create a directory for the project:
    mkdir -p plana-assessment/project  
    cd plana-assessment/project  

3. Clone the repository:
    git clone git@github.com:murtazapervez/plan-a-task.git .  
```

## Setting Up the Project
```
1. Install dependencies:
    composer install  

2. Copy the example .env file and configure your environment variables:
    cp .env.example .env  

3. Generate the application key:
    php artisan key:generate  
```
## Running the Application
Option 1: Using Laravel Sail (Recommended for Docker Users)
```
1. Start the application using Laravel Sail:
    ./vendor/bin/sail up -d  

2. Access the application in your browser at:
    [http://127.0.0.1:80/](http://127.0.0.1:80/)

3. To stop the application, run:
    ./vendor/bin/sail down  
```
## Option 2: Using PHP's Built-in Server

1. Start the server:
    php artisan serve --port=8000  


2. Access the application in your browser at:
    [http://127.0.0.1:8000/](http://127.0.0.1:8000/)
