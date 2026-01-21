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
        $users = $this->adminRepository->getAllEtudiant();
        require_once __DIR__."/../views/admin/students-manage.php";
    }

    public function EditUserPage(){
        require_once __DIR__."/../views/admin/edit-student.html";
    }
    public function deleteStudent($id){
        $this->adminRepository->supprimerEtudiant($id);
        $this->usersPage();
    }
    public function searchUsers(): void
{
    $q = $_GET['q'] ?? '';
    $users = $this->adminRepository->searchEtudiants($q);
    require __DIR__ . "/../views/admin/_students_rows.php";
    exit;
}


}