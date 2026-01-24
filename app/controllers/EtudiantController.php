<?php
class EtudiantController extends BaseController{
    private EtudiantRepository $repo;
    public function __construct(){
        parent::__construct();
        $this->requireRole1('etudiant');
        $this->repo = new EtudiantRepository();
    }
    public function dashboard(): void
    {
        $userId = $_SESSION['user_id'];

        $data = $this->repo->getDashboardData($userId);

        $this->render('student/dashboard.twig', $data);
    }
}