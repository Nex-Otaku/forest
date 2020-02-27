<?php

namespace app\components\twig;

use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;
use app\components\environment\Environment as AppEnvironment;

/**
 * Class Twig
 * @package app\components\twig
 */
class Twig
{
    private $isDevelopmentMode = true;

    public function render(string $templateName, array $params = []): string
    {
        $loader = new FilesystemLoader(AppEnvironment::getTemplatesFolder());
        $twig = new TwigEnvironment($loader, [
            'cache' => $this->isDevelopmentMode
                ? false
                : AppEnvironment::getCacheFolder(),
        ]);

        try {
            $twigFilename = "{$templateName}.twig";
            $result = $twig->render($twigFilename, $params);
        } catch (\Throwable $throwable) {
            return (string)$throwable;
        }

        return $result;
    }
}