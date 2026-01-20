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

    "president/events" => ["EventController", "presidentIndex"],
    "president/events/create" => ["EventController", "presidentCreate"],
    "president/events/store" => ["EventController", "presidentStore"],
    "president/events/{id}/edit" => ["EventController", "presidentEdit"],
    "president/events/{id}/update" => ["EventController", "presidentUpdate"],
    "president/events/{id}/delete" => ["EventController", "presidentDelete"],
    "president/events/{id}/participants" => ["EventController", "presidentParticipants"],

    "etudiant/events" => ["EventController", "studentIndex"],
    "etudiant/events/{id}" => ["EventController", "studentShow"],
    "etudiant/events/{id}/join" => ["EventController", "studentJoin"],

];

