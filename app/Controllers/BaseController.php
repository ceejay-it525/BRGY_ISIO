<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Main request instance
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Helpers loaded globally
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Session instance (optional)
     */
    protected $session;

    /**
     * Init controller
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        // DO NOT REMOVE
        parent::initController($request, $response, $logger);

        // Load session service (optional but useful)
        $this->session = service('session');

        // You can preload other services here if needed
        // e.g. $this->email = service('email');
    }

    /**
     * ✅ CSRF token refresh endpoint (for AJAX requests)
     */
    public function refreshCsrf()
    {
        return $this->response->setJSON([
            'csrfToken' => csrf_hash()
        ]);
    }
}