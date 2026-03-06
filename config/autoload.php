<?php

spl_autoload_register(function (string $class): void {
    $prefix = 'StokPro\\';
    $base   = __DIR__ . '/../src/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;

    $relative = substr($class, strlen($prefix));

    // Khusus namespace Config — folder-nya di /config/, bukan /src/
    if (strncmp('Config\\', $relative, 7) === 0) {
        $file = __DIR__ . '/' . substr($relative, 7) . '.php';
    } else {
        $file = $base . str_replace('\\', '/', $relative) . '.php';
    }

    if (file_exists($file)) {
        require $file;
    }
});

function rupiah(int|float $n): string
{
    return 'Rp ' . number_format($n, 0, ',', '.');
}

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}

function setFlash(string $msg, string $tipe = 'success'): void
{
    $_SESSION['flash'] = ['msg' => $msg, 'tipe' => $tipe];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}
