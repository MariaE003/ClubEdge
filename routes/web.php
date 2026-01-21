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
    "admin/editUser" => ["AdminController" , "EditUserPage"],


    // les route des clubs
    "admin/clubs" => ["ClubController" , "pageClubs"],
    
    "student/clubs-list" => ["ClubController" , "AfficherClub"],//afficher les clubs
    // detail des clubs
    "student/club-detail" => ["ClubController" , "detailClub"],
    
    // form pour creer un club
    "admin/Pagecreate-club" => ["ClubController" , "PageAdd"],

    "admin/create-club" => ["ClubController" , "AddClub"],

];
