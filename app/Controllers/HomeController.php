<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $db = app()->db();

        $stats = [
            'active_organizations' => $this->countByQuery($db, "SELECT COUNT(*) AS total FROM organizations WHERE status = 'active'"),
            'registered_students' => $this->countByQuery($db, "SELECT COUNT(*) AS total FROM users WHERE role_id = 3"),
            'successful_events' => $this->countByQuery($db, "SELECT COUNT(*) AS total FROM events WHERE status IN ('approved', 'completed', 'active')"),
            'event_participants' => $this->countByQuery($db, "SELECT COUNT(*) AS total FROM membership"),
        ];

        $this->render('home', [
            'title' => 'UMUnity',
            'stats' => $stats,
        ]);
    }

    private function countByQuery(\mysqli $db, string $sql): int
    {
        $result = $db->query($sql);
        if (!$result) {
            return 0;
        }

        $row = $result->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }
}
