<?php

require_once __DIR__.'/bootstrap.php';
global $errors;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setup</title>
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/setup.css">
</head>
<body>
    <h1 class="text-center">Welcome!</h1><hr>
    <h3 class="text-center">Let's help you set up the application.
    </h3>
    <p class="text-center">It's very easy</p>
    <div id="f">
        <?php if (! empty($errors) ) : ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><span class="alert alert-danger"><?= $error ?></span></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <form action="/" method="post" class="form">
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="text-center">
                        Enter your database details
                    </h4>
                    <br>
                    <div class="form-group">
                        <label for="db_host" class="sr-only">
                            Database host
                        </label>
                        <input type="text" name="db_host" placeholder="Host (default = 'localhost')" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="db_user" class="sr-only">
                            Choose database name
                        </label>
                        <input type="text" name="db_name" placeholder="Choose database name (default = 'laa_db')" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="db_user" class="sr-only">
                            Database username
                        </label>
                        <input type="text" name="db_user" placeholder="Database user (Default = 'root')" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="db_pass" class="sr-only">
                            Database password
                        </label>
                        <input type="password" name="db_pass" placeholder="Database password" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <h4 class="text-center">
                        Set up an admin account.
                    </h4>
                    <br>
                    <div class="form-group">
                        <label for="username" class="sr-only">
                            Username
                        </label>
                        <input type="text" name="username" placeholder="Choose username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">
                            Username
                        </label>
                        <input type="password" name="password" placeholder="Enter password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="cpassword" class="sr-only">
                            Username
                        </label>
                        <input type="password" name="cpassword" placeholder="Confirm password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Let's go." class="btn btn-primary form-control">
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>