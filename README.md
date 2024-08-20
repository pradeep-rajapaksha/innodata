# Innodata Test 

## Configurig steps:

- Duplicate the `.env.example` and rename to `.env`
- Run `compoer install`
- Run `php artisan migrate --seed`
    
    This will create new admin user with login credintials email: `pradeepprasanna.rajapaksha4@gmail.com`, password: `abc123`

- Run `php artisan serve` or open the project at http://innodata.test/admin if you using [Laragon](https://laragon.org). 
- Use the login details mentioned: 
    email: `pradeepprasanna.rajapaksha4@gmail.com`
    password: `abc123`

- Run `php artisan app:generate-customers-csv` command for generate csv file with fake customers.

    It'll create `1000` records default, or `php artisan app:generate-customers-csv 1500` to create more records

    The exported file can be found at `storage/app/public/exports/customers-data.csv`.

- Run `php artisan app:import-customers-csv` for import a csv file with cunstomers. 

    It'll take `storage/app/public/exports/customers-data.csv` file as default to import the data into databse. 

    It can be any other file placed into `storage/app/public/` and run with the correct file path `php artisan app:import-customers-csv {filePath=exports/customers-data.csv}`

- Open [Customer List](http://innodata.test/admin/customers) view to check the imported records.

    There will be another Import/Export functioning options that provides by [Filament](https://filamentphp.com). 
