# api.sandercokart.com 



# Getting started
Run the following commands to get up and started

```bash
composer install & artisan key:generate & php artisan migrate --seed & php artisan storage:link
```

# Routes
Routes are split among the following files:
## `routes/guest.php`
anyone can use these routes
## `routes/authenticated.php`
only authenticated users can use these routes
## `routes/verified.php`
only verified users can use these routes

# Added artisan commands
```
artisan prune:files
```
removes all files that are not used by the application

```
artisan make:contract
```
Makes a php interface or as laravel refers to them as contracts and are stored in `\App\Contracts`

```
artisan make:trait
```
Makes a php trait and is stored in `\App\Traits`

# Guidelines
## Testing
Testing is done using PEST (PHPUnit)
For this vendor package there are 3 commands:

```
php artisan pest:test {name} //generates a test file
```

```
php artisan pest:dataset {name} //generates a dataset file
```

In order to run tests call the following command:
```
php `./vendor/bin/pest`
```

# PHPStorm Support
## Command Line Tool
PHPStorm has a Run Anything feature that can be used to run any command.
This includes scripts found in the package.json.

### Adding Custom Tools
We can add additional tools to the Run Anything command line.
Go to file -> settings -> tools -> command line tool support
#### Composer
To add composer click the `+` button and select `Composer` from the dropdown.
Then you can define this tool for global use or limit it to just this project.
Use the projects default php interpreter and fill in the path to the composer.phar.

If you installed composer on Windows via the Windows installer setup executable
found on: https://getcomposer.org/download/ or via direct download: https://getcomposer.org/Composer-Setup.exe
you can find it in `C:\ProgramData\ComposerSetup\bin\composer.phar`

You can now use composer in the Run Anything tool by prefixing your command with `c`as alias.


#### Laravel Artisan
To add artisan click the `+` button and select `Laravel` from the dropdown.
Then you can define this tool for global use or limit it to just this project.
Use the projects default php interpreter and fill in the path to the `artisan` executable at the root this project.

You can now use artisan in the Run Anything tool by 
prefixing your command with whatever alias you have decided to use.


# Environment (.env)
We have 4 .env files.
* .env.local.example
* .env.prod.example
* .env.testing.example
