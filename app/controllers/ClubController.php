<?php

class ClubController{
    private ClubRepository $repoClub;
    public function __contstruct(){
        $this->repo=new ClubRepository();
    }

    public function direction_clubs(){
        require_once __DIR__."../../views/student/clubs-list.html";
    }

    public function direction_events(){
        require_once __DIR__."../../views/student/events-list.html";
    }

    // public function pageClubs(){
        
    // }

    // public function pageUpdateClubs(){
        
    // }

    // public function 
}

?>