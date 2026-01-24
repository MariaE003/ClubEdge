<?php

 class EventController extends BaseController
{
    private EventRepository $eventRepository;
    private ClubRepository $clubRepository;

    public function __construct()
    {
        parent::__construct();
        $this->eventRepository = new EventRepository();
        $this->clubRepository = new ClubRepository(Database::getInstance()->getConnection());
        $this->requireRole('president');
    }

    public function presidentIndex(): void
    {
        $userId = $this->requireRole('president');

        $clubId = $this->clubRepository->findIdByPresidentId($userId);
        // var_dump($clubId);
        if (!$clubId) {
            $this->flash('error', "Aucun club associé à ce président.");
            $this->redirect('../president/dashboard');
        }

        $events = $this->eventRepository->listByClub($clubId);
        $this->renderWithFallback(
            'president/events/index.twig',
            __DIR__ . '/../views/president/events/index.php',
            ['events' => $events]
        );
    }

    public function presidentCreate(): void
    {
        $this->requireRole('president');

        $this->renderWithFallback(
            'president/events/create.twig',
            __DIR__ . '/../views/president/events/create.php',
            ['errors' => [], 'old' => []]
        );
    }

    public function presidentStore(): void
    {
        $userId = $this->requireRole('president');
        $this->requirePost();
        Csrf::validatePostOrDie();

        $clubId = $this->clubRepository->findIdByPresidentId($userId);
        if (!$clubId) {
            $this->flash('error', "Aucun club associé à ce président.");
            $this->redirect('president/dashboard');
        }

        $payload = Event::sanitizePayload($_POST);
        $errors = Event::validatePayload($payload);
        $uploaded = $this->saveEventImages($_FILES['images'] ?? null);
        if ($uploaded['errors']) {
            $errors['images'] = implode(' ', $uploaded['errors']);
        }

        if ($errors) {
            $this->renderWithFallback(
                'president/events/create.twig',
                __DIR__ . '/../views/president/events/create.php',
                ['errors' => $errors, 'old' => $payload]
            );
            return;
        }

        $eventDate = Event::parseEventDate($payload['event_date']);
        if (!$eventDate) {
            $this->renderWithFallback(
                'president/events/create.twig',
                __DIR__ . '/../views/president/events/create.php',
                ['errors' => ['event_date' => "La date est invalide."], 'old' => $payload]
            );
            return;
        }

        $event = new Event(
            null,
            $clubId,
            $payload['title'],
            $payload['description'],
            $eventDate,
            $payload['location'],
            $uploaded['paths'],
            $userId
        );

        $eventId = $this->eventRepository->create($event);
        Logger::info("event_created", ['event_id' => $eventId, 'user_id' => $userId]);

        $this->flash('success', "Événement créé.");
        $this->redirect('president/events');
    }

    public function presidentEdit(int $eventId): void
    {
        $userId = $this->requireRole('president');

        if (!$this->eventRepository->eventBelongsToPresident($eventId, $userId)) {
            $this->forbidden();
        }

        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            $this->notFound();
        }

        $old = [
            'title' => $event['title'] ?? '',
            'description' => $event['description'] ?? null,
            'location' => $event['location'] ?? null,
            'event_date' => $this->toDatetimeLocalValue($event['event_date'] ?? ''),
        ];

        $this->renderWithFallback(
            'president/events/edit.twig',
            __DIR__ . '/../views/president/events/edit.php',
            ['event' => $event, 'errors' => [], 'old' => $old]
        );
    }

    public function presidentUpdate(int $eventId): void
    {
        $userId = $this->requireRole('president');
        $this->requirePost();
        Csrf::validatePostOrDie();

        if (!$this->eventRepository->eventBelongsToPresident($eventId, $userId)) {
            $this->forbidden();
        }

        $eventRow = $this->eventRepository->findById($eventId);
        if (!$eventRow) {
            $this->notFound();
        }

        $payload = Event::sanitizePayload($_POST);
        $errors = Event::validatePayload($payload);
        $uploaded = $this->saveEventImages($_FILES['images'] ?? null);
        if ($uploaded['errors']) {
            $errors['images'] = implode(' ', $uploaded['errors']);
        }

        if ($errors) {
            $this->renderWithFallback(
                'president/events/edit.twig',
                __DIR__ . '/../views/president/events/edit.php',
                ['event' => $eventRow, 'errors' => $errors, 'old' => $payload]
            );
            return;
        }

        $eventDate = Event::parseEventDate($payload['event_date']);
        if (!$eventDate) {
            $this->renderWithFallback(
                'president/events/edit.twig',
                __DIR__ . '/../views/president/events/edit.php',
                ['event' => $eventRow, 'errors' => ['event_date' => "La date est invalide."], 'old' => $payload]
            );
            return;
        }

        $images = $eventRow['images'] ?? [];
        $images = array_values(array_merge($images, $uploaded['paths']));

        $event = new Event(
            $eventId,
            (int) $eventRow['club_id'],
            $payload['title'],
            $payload['description'],
            $eventDate,
            $payload['location'],
            $images,
            $userId
        );

        $this->eventRepository->update($eventId, $event);
        Logger::info("event_updated", ['event_id' => $eventId, 'user_id' => $userId]);

        $this->flash('success', "Événement mis à jour.");
        $this->redirect('president/events');
    }

    public function presidentDelete(int $eventId): void
    {
        $userId = $this->requireRole('president');
        $this->requirePost();
        Csrf::validatePostOrDie();

        if (!$this->eventRepository->eventBelongsToPresident($eventId, $userId)) {
            $this->forbidden();
        }

        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            $this->notFound();
        }

        $this->eventRepository->delete($eventId);
        $this->deleteEventImages($event['images'] ?? []);
        Logger::info("event_deleted", ['event_id' => $eventId, 'user_id' => $userId]);

        $this->flash('success', "Événement supprimé.");
        $this->redirect('president/events');
    }

    public function presidentParticipants(int $eventId): void
    {
        $userId = $this->requireRole('president');

        if (!$this->eventRepository->eventBelongsToPresident($eventId, $userId)) {
            $this->forbidden();
        }

        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            $this->notFound();
        }

        $participants = $this->eventRepository->listParticipants($eventId);
        $this->renderWithFallback(
            'president/events/participants.twig',
            __DIR__ . '/../views/president/events/participants.php',
            ['event' => $event, 'participants' => $participants]
        );
    }

    public function studentIndex(): void
    {
        $userId = $this->requireRole('etudiant');

        $events = $this->eventRepository->listForStudent($userId);
        $this->renderWithFallback(
            'student/events/index.twig',
            __DIR__ . '/../views/student/events/index.php',
            ['events' => $events]
        );
    }

    public function studentShow(int $eventId): void
    {
        $userId = $this->requireRole('etudiant');

        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            $this->notFound();
        }

        $event['is_joined'] = $this->eventRepository->isUserSignedUp($eventId, $userId);
        $event['is_past'] = (bool) ($event['is_past'] ?? $this->eventRepository->isPast($eventId));
        $participants = $this->eventRepository->listParticipants($eventId);

        $this->renderWithFallback(
            'student/events/show.twig',
            __DIR__ . '/../views/student/events/show.php',
            ['event' => $event, 'participants' => $participants]
        );
    }

    public function studentJoin(int $eventId): void
    {
        $userId = $this->requireRole('etudiant');
        $this->requirePost();
        Csrf::validatePostOrDie();

        $result = $this->eventRepository->signup(new EventSignup($eventId, $userId));
        if (!($result['success'] ?? false)) {
            $this->flash('error', $result['message'] ?? "Erreur lors de l'inscription.");
            $this->redirect("etudiant/events/$eventId");
        }

        $this->flash('success', $result['message'] ?? "Inscription réussie.");
        $this->redirect("etudiant/events/$eventId");
    }

    private function requireRole(string $role): int
    {
        if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
            $this->redirect('loginPage');
        }
        if ($_SESSION['role'] !== $role) {
            $this->forbidden();
        }
        return (int) $_SESSION['user_id'];
    }

    private function requirePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
    }



    private function renderWithFallback(string $twigTemplate, string $phpViewPath, array $data = []): void
    {
        $flash = $this->consumeFlash();
        $data['flash'] = $flash;

        $twigPath = __DIR__ . '/../views/twig/' . $twigTemplate;
        if (class_exists(\Twig\Environment::class) && file_exists($twigPath)) {
            View::render($twigTemplate, $data);
            return;
        }

        extract($data, EXTR_SKIP);
        require $phpViewPath;
    }

    private function redirect(string $path): void
    {
        $base = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
        $base = str_replace('\\', '/', $base);
        $base = rtrim(dirname($base), '/');
        if ($base === '.') {
            $base = '';
        }

        $location = $base . '/' . ltrim($path, '/');
        header("Location: $location");
        exit();
    }

    private function forbidden(): void
    {
        http_response_code(403);
        require __DIR__ . '/../views/errors/403.html';
        exit();
    }

    private function notFound(): void
    {
        http_response_code(404);
        require __DIR__ . '/../views/errors/404.html';
        exit();
    }

    private function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function consumeFlash(): ?array
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }

    private function saveEventImages(?array $files): array
    {
        if (!$files || !isset($files['name'])) {
            return ['paths' => [], 'errors' => []];
        }

        $names = is_array($files['name']) ? $files['name'] : [$files['name']];
        $tmpNames = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
        $errors = is_array($files['error']) ? $files['error'] : [$files['error']];
        $sizes = is_array($files['size']) ? $files['size'] : [$files['size']];

        $savedPaths = [];
        $uploadErrors = [];

        $targetDir = __DIR__ . '/../../public/uploads/events';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        foreach ($names as $i => $originalName) {
            $err = (int) ($errors[$i] ?? UPLOAD_ERR_NO_FILE);
            if ($err === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if ($err !== UPLOAD_ERR_OK) {
                $uploadErrors[] = "Échec de l'upload de l'image.";
                continue;
            }

            $size = (int) ($sizes[$i] ?? 0);
            if ($size <= 0 || $size > 5 * 1024 * 1024) {
                $uploadErrors[] = "Image trop lourde (max 5MB).";
                continue;
            }

            $tmp = $tmpNames[$i] ?? null;
            if (!$tmp || !is_file($tmp)) {
                $uploadErrors[] = "Fichier temporaire manquant.";
                continue;
            }

            $mime = $finfo->file($tmp) ?: '';
            if (!isset($allowed[$mime])) {
                $uploadErrors[] = "Format d'image non supporté.";
                continue;
            }

            $ext = $allowed[$mime];
            $filename = bin2hex(random_bytes(16)) . '.' . $ext;
            $dest = $targetDir . '/' . $filename;

            if (!move_uploaded_file($tmp, $dest)) {
                $uploadErrors[] = "Impossible d'enregistrer l'image.";
                continue;
            }

            $savedPaths[] = '/uploads/events/' . $filename;
        }

        return ['paths' => $savedPaths, 'errors' => $uploadErrors];
    }

    private function deleteEventImages(array $paths): void
    {
        foreach ($paths as $p) {
            $p = (string) $p;
            if (!str_starts_with($p, '/uploads/events/')) {
                continue;
            }
            $full = __DIR__ . '/../../public' . $p;
            if (is_file($full)) {
                @unlink($full);
            }
        }
    }

    private function toDatetimeLocalValue(string $dbValue): string
    {
        if ($dbValue === '') {
            return '';
        }
        try {
            $dt = new DateTimeImmutable($dbValue);
            return $dt->format('Y-m-d\\TH:i');
        } catch (Throwable) {
            return '';
        }
    }
    public function pageListEvent(){
        parent::render("student/event-list.html" , []);
    }

    public function pageEventList(): void
{
    $this->requireRole('admin');

    $filters = [
        'q'       => $_GET['q'] ?? '',
        'club_id' => $_GET['club_id'] ?? '',
        'status'  => $_GET['status'] ?? '',
    ];

    $events = $this->eventRepository->listAllForAdmin($filters);

    $clubs = $this->clubRepository->allClubs();

    $this->render('admin/events-overview.twig', [
        'events'  => $events,
        'clubs'   => $clubs,
        'filters' => $filters,
        'active'  => 'events'
    ]);
}

    public function AddReview(){
        require_once __DIR__. "/../views/student/review.php";
    }
}
