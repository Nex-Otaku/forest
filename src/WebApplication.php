<?php

namespace app;

use app\actions\RegisterUser;
use app\components\db\Db;
use app\components\twig\Twig;

class WebApplication
{
    public function __construct(Config $config)
    {
        Db::setConfig($config);
    }

    public function run(): void
    {
        $response = $this->runAction();
        echo $response;
    }

    private function runAction(): string
    {
        $route = $this->getRoute();
        if ($route === '/') {
            return $this->actionMainPage();
        }
        if ($route === '/feed') {
            return $this->actionNews();
        }
        if ($route === '/register') {
            return $this->actionRegister();
        }
        return $this->actionNotFound();
    }

    private function actionMainPage(): string
    {
        return $this->render('main-page', ['name' => 'Fabien']);
    }

    private function actionNews(): string
    {
        return 'Общая лента новостей';
    }

    private function actionNotFound(): string
    {
        $this->setNotFoundHeader();
        return $this->errorResponse('Ой. Страница не найдена. Нам очень жаль :(');
    }

    private function errorResponse(string $message, int $code = 200): string
    {
        $this->setStatusCode($code);
        return $this->render('error-page', ['message' => $message]);
    }

    private function setNotFoundHeader(): void
    {
        $this->setStatusCode(404);
    }

    private function getRoute(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    private function actionRegister(): string
    {
        $formData = $this->getPostData('registrationForm');

        if (empty($formData)) {
            return $this->render('registration-page');
        }

        if (!empty($formData)) {
            try {
                $action = new RegisterUser(
                    $formData['login'],
                    $formData['first_name'],
                    $formData['last_name'],
                    $formData['password']
                );
                $action->execute();
            } catch (\Exception $exception) {
                return $this->errorResponse($exception->getMessage());
            }
        }

        return $this->redirectResponse('/');
    }

    private function redirectResponse(string $path): string
    {
        header("Location: {$path}", true, 302);
        exit();
    }

    private function getPostData(string $key)
    {
        $post = $_POST;

        if (!array_key_exists($key, $post)) {
            return null;
        }

        return $post[$key];
    }

    private function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    private function render(string $templateName, array $params = []): string
    {
        return (new Twig())->render($templateName, $params);
    }
}