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
    // "etudiant/page_clubs" => ["ClubController" , "direction_clubs"],
    // "etudiant/events"=> ["ClubController","direction_events"],
    // les route des clubs
    "admin/clubs" => ["ClubController" , "pageClubs"],
    "student/clubs-list" => ["ClubController" , "AfficherClub"],//afficher les clubs
    // detail des clubs
    "student/club-detail" => ["ClubController" , "detailClub"],
    "student/join" => ["ClubController", "join"],
    "etudiant/direction_clubList" => ["ClubController","direction_clubList"]
    
    
    

];

