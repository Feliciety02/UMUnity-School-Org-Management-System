<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = null): string
    {
        $app = Application::getInstance();
        $viewPath = $app->basePath('app/Views/' . $view . '.php');

        if (!is_file($viewPath)) {
            throw new \RuntimeException("View [{$view}] not found.");
        }

        $content = self::make($viewPath, $data);

        if ($layout === null) {
            return $content;
        }

        $layoutPath = $app->basePath('app/Views/layouts/' . $layout . '.php');

        if (!is_file($layoutPath)) {
            throw new \RuntimeException("Layout [{$layout}] not found.");
        }

        return self::make($layoutPath, array_merge($data, ['content' => $content]));
    }

    public static function make(string $path, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        include $path;
        return (string)ob_get_clean();
    }
}
