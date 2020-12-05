<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFactory
{
    private bool $success;
    private $content;
    private int $status;
    private ?int $currentPage;
    private ?int $itemsPerPage;

    public function __construct(
        bool $success,
        $content,
        int $status,
        int $currentPage = null,
        int $itemsPerPage = null)
    {
        $this->success = $success;
        $this->content = $content;
        $this->status = $status;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getResponse(): JsonResponse
    {
        $content = [
            'success' => $this->success,
            'current_page' => $this->currentPage,
            'items_per_page' => $this->itemsPerPage,
            'content' => $this->content
        ];

        if (is_null($this->currentPage)) {
            unset($content['current_page']);
            unset($content['items_per_page']);
        }

        if (is_null($this->content)) {
            unset($content['content']);
        }

        return new JsonResponse($content, $this->status);
    }
}