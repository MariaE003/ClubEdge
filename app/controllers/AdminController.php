<?php
class AdminController{

    private AdminRepository $adminRepository;
    public function __construct(){
        $this->adminRepository = new AdminRepository();
    }
    public function dashboard(){
        $totalEt = $this->adminRepository->totalEtudiants();
        $totalCl = $this->adminRepository->totalClub();
        $totalEv = $this->adminRepository->totalEvenements();
        require_once __DIR__."/../views/admin/dashboard.php";
    }
    public function usersPage(){
        require_once __DIR__."/../views/admin/students-manage.html";
    }

    public function EditUserPage(){
        require_once __DIR__."/../views/admin/edit-student.html";
    }


}