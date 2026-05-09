<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['admin', 'leader', 'student']);

        $role = Auth::role();
        if ($role === 'admin') {
            $this->redirect('/dashboard/admin');
        }

        if ($role === 'leader') {
            $this->redirect('/dashboard/leader');
        }

        $this->redirect('/dashboard/student');
    }

    public function admin(): void
    {
        Auth::requireRole('admin');
        $db = app()->db();
        $user = Auth::user();

        $stats = [
            'total_organizations' => $this->count($db, "SELECT COUNT(*) AS total FROM organizations WHERE status = 'active'"),
            'pending_organizations' => $this->count($db, "SELECT COUNT(*) AS total FROM organizations WHERE status = 'pending'"),
            'total_users' => $this->count($db, 'SELECT COUNT(*) AS total FROM users'),
            'active_users' => $this->count($db, "SELECT COUNT(*) AS total FROM users WHERE status = 'active'"),
        ];

        $pendingOrganizations = get_organizations_by_status('pending', $db);
        $recentActivities = getActivityLogs([], $db);

        $this->render('dashboard/admin', [
            'title' => 'Admin Dashboard',
            'pageTitle' => 'Admin Dashboard',
            'pageDescription' => 'Oversee organizations, users, and approvals from one place.',
            'currentUser' => $user,
            'currentPage' => 'dashboard',
            'stats' => $stats,
            'pendingOrganizations' => array_slice($pendingOrganizations, 0, 5),
            'recentActivities' => array_slice($recentActivities, 0, 5),
        ], 'dashboard');
    }

    public function leader(): void
    {
        Auth::requireRole('leader');
        $user = Auth::user();
        $db = app()->db();
        $leaderId = (int)$user['user_id'];

        $organization = get_leader_organization($leaderId, $db);
        $events = get_all_org_events($leaderId, $db);

        $this->render('dashboard/leader', [
            'title' => 'Leader Dashboard',
            'pageTitle' => 'Leader Dashboard',
            'pageDescription' => 'Manage your organization, members, and event pipeline.',
            'currentUser' => $user,
            'currentPage' => 'dashboard',
            'organization' => $organization,
            'totalMembers' => get_total_members($leaderId, $db),
            'totalEvents' => get_total_org_events($leaderId, $db),
            'events' => array_slice($events, 0, 5),
        ], 'dashboard');
    }

    public function student(): void
    {
        Auth::requireRole('student');
        $user = Auth::user();
        $db = app()->db();
        $studentId = (int)$user['user_id'];

        $joinedOrganizations = get_user_organizations($studentId, $db);
        $events = get_user_events($studentId, $db);
        $recommended = get_recommended_organizations($studentId, $db);

        $this->render('dashboard/student', [
            'title' => 'Student Dashboard',
            'pageTitle' => 'Student Dashboard',
            'pageDescription' => 'Track your memberships, upcoming events, and organizations to explore.',
            'currentUser' => $user,
            'currentPage' => 'dashboard',
            'joinedOrganizations' => array_slice($joinedOrganizations, 0, 6),
            'upcomingEvents' => array_slice($events, 0, 6),
            'recommendedOrganizations' => array_slice($recommended, 0, 6),
            'unreadNotifications' => count_unread_notifications($studentId, $db),
        ], 'dashboard');
    }

    private function count(\mysqli $db, string $sql): int
    {
        $result = $db->query($sql);
        if (!$result) {
            return 0;
        }

        $row = $result->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }
}
