<?php
class AdminController extends BaseController{

    private AdminRepository $adminRepository;
    private EtudiantRepository $etudiantRepository;
    private UserRepository $userRepository;
    private ClubRepository $clubRepository;
    public function __construct(){
        $this->adminRepository = new AdminRepository();
        $this->etudiantRepository =  new EtudiantRepository();
        $this->userRepository = new UserRepository();
        $this->clubRepository = new ClubRepository(Database::getInstance()->getConnection());
        parent::__construct();
    }
    public function dashboard(){
        $totalEt = $this->adminRepository->totalEtudiants();
        $totalCl = $this->adminRepository->totalClub();
        $totalEv = $this->adminRepository->totalEvenements();
        $clubs = $this->clubRepository->getAllClub();
        $this->render('admin/dashboard.twig', [
        'totalEt' => $totalEt,
        'totalCl' => $totalCl ,
        'totalEv'=> $totalEv,
        'clubs' => $clubs
    ]);
    }
    public function usersPage(){
        $users = $this->adminRepository->getAllEtudiant();
        $this->render('admin/students-manage.twig', [
        'users' => $users
    ]);
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
    public function searchUsers(): void{
        $q = $_GET['q'] ?? '';
        $users = $this->adminRepository->searchEtudiants($q);
        $this->render('admin/_students_rows.twig', [
            'users' => $users
        ]);
        exit;
    }



}