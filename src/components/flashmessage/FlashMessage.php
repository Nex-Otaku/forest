<?php

namespace app\components\flashmessage;

use Plasticbrain\FlashMessages\FlashMessages;

/**
 * Class FlashMessage
 * @package app\components\flashmessage
 */
class FlashMessage
{
    private $flashMessages;

    public function __construct()
    {
        $this->flashMessages = new FlashMessages();
    }

    public function success(string $message): void
    {
        $this->flashMessages->success($message);
    }

    public function renderMessages(): string
    {
        return $this->flashMessages->display(null, false);
    }
}