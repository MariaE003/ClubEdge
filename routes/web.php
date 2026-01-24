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
    "president/testTwig" => ["PresidentController" , "testTwig"],
    "etudiant/dashboard" => ["EtudiantController" , "dashboard"],
    "logout" => ["AuthController" , "logout"],
    "DatabaseTest" => ["AuthController" , "testConnection"],
    "president/CreateArticle" => ["ArticleController" , "createArticle"],
    "president/insertArticle" => ["ArticleController" , "insertArticle"],
    "president/ArticleManage" => ["ArticleController" , "manageArticles"],
    
];  

