<?php

namespace app\core\components\layout;

class headerDiv
{
    private array $pages = [
        'ongoing' => [
            'id' => 'ongoing',
            'link' => '#',
            'icon' => 'cached',
            'name' => 'Ongoing'
        ],
        'pending' => [
            'id' => 'pending',
            'link' => '#',
            'icon' => 'hourglass_empty',
            'name' => 'Pending'
        ],
        'completed' => [
            'id' => 'completed',
            'link' => '#',
            'icon' => 'done_all',
            'name' => 'Completed'
        ],
        'cancelled' => [
            'id' => 'cancelled',
            'link' => '#',
            'icon' => 'block',
            'name' => 'Cancelled'
        ],
        'individuals' => [
            'id' => 'individual',
            'link' => '#',
            'icon' => 'person',
            'name' => 'Individual'
        ],
        'organizations' => [
            'id' => 'organization',
            'link' => '#',
            'icon' => 'business',
            'name' => 'Organization'
        ],
        'posted' => [
            'id' => 'posted',
            'link' => '#',
            'icon' => 'publish',
            'name' => 'Posted'
        ],
        'history' => [
            'id' => 'history',
            'link' => '#',
            'icon' => 'history',
            'name' => 'History'
        ],
        'active' => [
            'id' => 'active',
            'link' => '#',
            'icon' => 'broadcast_on_personal',
            'name' => 'Active'
        ],
        'upcoming' => [
            'id' => 'upcoming',
            'link' => '#',
            'icon' => 'schedule',
            'name' => 'Upcoming'
        ],
        'accepted' => [
            'id' => 'accepted',
            'link' => '#',
            'icon' => 'check_circle_outline',
            'name' => 'Accepted'
        ],
        'assigned' => [
            'id' => 'assigned',
            'link' => '#',
            'icon' => 'assignment_ind',
            'name' => 'Assigned'
        ],
        'logistic' => [
            'id' => 'logistic',
            'link' => '#',
            'icon' => 'local_shipping',
            'name' => 'Logistic'
        ],
        'manager' => [
            'id' => 'manager',
            'link' => '#',
            'icon' => 'supervisor_account',
            'name' => 'Manager'
        ],
    ];

    public function __construct()
    {
        echo "<div class='heading-pages'>";
    }

    public function heading($heading): void
    {
        echo sprintf("<div class='heading'><h1>%s</h1></div>", $heading);
    }

    public function pages($pages): void
    {
        echo "<div class='pages'>";
        foreach ($pages as $key) {
            $class = ($key === $pages[0]) ? "active-heading-page": "";
            $page = $this->pages[$key];
            echo sprintf("<a href='%s' id='%s' class='page %s'><i class='material-icons'>%s</i> %s</a>", $page['link'], $page['id'],$class, $page['icon'], $page['name']);
        }
        echo "</div>";
    }

    public function end(): void
    {
        echo "</div>";
    }

}