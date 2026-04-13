<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function requireAuth(): void
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: index.php');
        exit;
    }
}

function authUser(): ?array
{
    return $_SESSION['admin'] ?? null;
}
