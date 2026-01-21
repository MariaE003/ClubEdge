<?php
class AdminController extends BaseController{

    private AdminRepository $adminRepository;
    private EtudiantRepository $etudiantRepository;
    private UserRepository $userRepository;
    public function __construct(){
        $this->adminRepository = new AdminRepository();
        $this->etudiantRepository =  new EtudiantRepository();
        $this->userRepository = new UserRepository();
        parent::__construct();
    }
    public function dashboard(){
        $totalEt = $this->adminRepository->totalEtudiants();
        $totalCl = $this->adminRepository->totalClub();
        $totalEv = $this->adminRepository->totalEvenements();
        $this->render('admin/dashboard.php', [
        'totalEt' => $totalEt,
        'totalCl' => $totalCl ,
        'totalEv'=> $totalEv
    ]);
    }
    public function usersPage(){
        $users = $this->adminRepository->getAllEtudiant();
        require_once __DIR__."/../views/admin/students-manage.php";
    }

    public function EditUserPage($id)
{
    $student = $this->etudiantRepository->getEtudiantById($id);

     $this->render('admin/edit-student.twig', [
        'student' => $student
    ]);
}
    public function deleteStudent($id){
        $this->adminRepository->supprimerEtudiant($id);
        $this->usersPage();
    }
    public function updateInfo(){
        $data = $_POST;
        $result = $this->userRepository->updateInfo($data);
        if($result){
            $this->EditUserPage($_POST["id"]);
        }
    }
    public function searchUsers(): void
{
    $q = $_GET['q'] ?? '';
    $users = $this->adminRepository->searchEtudiants($q);
    require __DIR__ . "/../views/admin/_students_rows.php";
    exit;
}


}