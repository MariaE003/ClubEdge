    <?php

    $routes = [
        "/" => ["AuthController" , "pageHome"],
        "home" => ["AuthController" , "pageHome"],
        "loginPage" => ["AuthController" , "pageLogin"],
        "registerPage" => ["AuthController" , "pageRegister"],
        "register" => ["AuthController" , "register"],
        "login" => ["AuthController" , "login"],
        "admin/dashboard" => ["AdminController" , "dashboard"],
        "president/dashboard" => ["PresidentController" , "dashboard"],
        "etudiant/dashboard" => ["EtudiantController" , "dashboard"],
        "admin/users" => ["AdminController" , "usersPage"],
        "admin/editUser/{id}" => ["AdminController" , "EditUserPage"],
        "admin/deleteUser/{id}" => ["AdminController" , "deleteStudent"],
        "admin/users/search" => ["AdminController", "searchUsers"],
        "admin/updateInfoUser" => ["AdminController", "updateInfo"],
        

    ];

