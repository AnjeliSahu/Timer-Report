<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FileMakerModel;
use CodeIgniter\HTTP\ResponseInterface;

class ApiController extends BaseController
{
    private $fileMakerModel;

    public function __construct()
    {
        $this->fileMakerModel = new FileMakerModel();
    }

    public function getTimers()
    {
        // Set JSON response headers
        $this->response->setContentType('application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');

        try {
            $result = $this->fileMakerModel->getTimerRecords();
            
            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'API Controller Error: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'error' => 'Internal server error'
                ]);
        }
    }
}