<?php

$routes = [
    // Public / Auth
    "/"                 => ["AuthController", "pageHome"],
    "home"              => ["AuthController", "pageHome"],
    "loginPage"         => ["AuthController", "pageLogin"],
    "registerPage"      => ["AuthController", "pageRegister"],
    "register"          => ["AuthController", "register"],
    "login"             => ["AuthController", "login"],
    "logout"            => ["AuthController", "logout"],
    "DatabaseTest"      => ["AuthController", "testConnection"],

    // Dashboards
    "admin/dashboard"       => ["AdminController", "dashboard"],
    "president/dashboard"   => ["PresidentController", "dashboard"],
    "etudiant/dashboard"    => ["EtudiantController", "dashboard"],

    // President
    "president/testTwig"            => ["PresidentController", "testTwig"],
    "president/CreateArticle"       => ["ArticleController", "createArticle"],
    "president/insertArticle"       => ["ArticleController", "insertArticle"],
    "president/ArticleManage"       => ["ArticleController", "manageArticles"],

    // Admin - Users
    "admin/users"               => ["AdminController", "usersPage"],
    "admin/editUser/{id}"       => ["AdminController", "EditUserPage"],
    "admin/deleteUser/{id}"     => ["AdminController", "deleteStudent"],
    "admin/users/search"        => ["AdminController", "searchUsers"],
    "admin/updateInfoUser"      => ["AdminController", "updateInfo"],

    // Events (President side)
    "president/events"                  => ["EventController", "presidentIndex"],
    "president/events/create"           => ["EventController", "presidentCreate"],
    "president/events/store"            => ["EventController", "presidentStore"],
    "president/events/{id}/edit"        => ["EventController", "presidentEdit"],
    "president/events/{id}/update"      => ["EventController", "presidentUpdate"],
    "president/events/{id}/delete"      => ["EventController", "presidentDelete"],
    "president/events/{id}/participants"=> ["EventController", "presidentParticipants"],

    // Events (Student side)
    "etudiant/events"           => ["EventController", "studentIndex"],
    "etudiant/events/{id}"      => ["EventController", "studentShow"],
    "etudiant/events/{id}/join" => ["EventController", "studentJoin"],

    // Clubs (Student / Etudiant)
    "etudiant/clubs-list"       => ["ClubController", "AfficherClub"],
    "etudiant/club-detail"      => ["ClubController", "detailClub"],
    "student/clubs-list"        => ["ClubController", "AfficherClub"],
    "student/club-detail"       => ["ClubController", "detailClub"],
    "student/join"              => ["ClubController", "join"],

    // Clubs (Admin)
    "admin/clubs"           => ["ClubController", "AfficherClubAdmin"],
    "admin/club-detail"     => ["ClubController", "detailClubAdmin"],
    "admin/clubs/create"    => ["ClubController", "PageAdd"],
    "admin/clubs/store"     => ["ClubController", "AddClub"],
    "admin/editClub"        => ["ClubController", "pageUpdateClubs"],
    "admin/deleteClub"      => ["ClubController", "deleteClub"],

    // Events list (Admin)
    "admin/events-list"     => ["EventController", "pageListEvent"],
];
