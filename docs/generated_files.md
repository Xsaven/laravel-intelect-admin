# Generated files #
------------
After the installation is complete, the following files are generated in the project directory:

#### Configuration file
After the installation is complete, all configurations are in the `config/lia.php` file.

#### Admin files
After install, you can find directory `app/Admin`, and then most of our develop work is under this directory.
```$xslt
    app/Admin
        ├──Controllers
        │   └──HomeController.php
        ├──bootstrap.php
        └──routes.php
```
`app/Admin/routes.php` is used to define routes.

`app/Admin/bootstrap.php` is bootstrapper for laravel-admin, more usages see comments inside it.

The `app/Admin/Controllers` directory is used to store all the controllers, The `HomeController.php` file under this directory is used to handle home request of admin.