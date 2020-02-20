<?php

namespace app;

use app\actions\RegisterUser;

class WebApplication
{
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
        return 'Главная страница';
    }

    private function actionNews(): string
    {
        return 'Общая лента новостей';
    }

    private function actionNotFound(): string
    {
        $this->setNotFoundHeader();
        return 'Ой. Страница не найдена. Нам очень жаль :(';
    }

    private function errorResponse(string $message, int $code = 200): string
    {
        $this->setStatusCode($code);
        return $message;
    }

    private function setNotFoundHeader(): void
    {
        $this->setStatusCode(404);
    }

    private function getRoute(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function actionRegister(): string
    {
        $formData = $this->getPostData('registrationForm');

//        var_dump($formData);
        if (empty($formData)) {
            return $this->errorResponse('Не заполнены данные формы.', 400);
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
}