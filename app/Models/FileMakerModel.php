<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\FileMakerAPI;

class FileMakerModel extends Model
{
    private $fileMakerAPI;

    public function __construct()
    {
        parent::__construct();
        $this->fileMakerAPI = new FileMakerAPI();
    }

    public function getTimerRecords()
    {
        try {
            $data = $this->fileMakerAPI->getRecords('Timer');

            if (isset($data['error'])) {
                return [
                    'success' => false,
                    'error' => $data['error']
                ];
            }

            $timers = [];
            foreach ($data as $record) {
                $f = $record['fieldData'];
                $timers[] = [
                    'userName'   => trim($f['UserName'] ?? ''),
                    'project'    => trim($f['ProjectName'] ?? ''),
                    'activity'   => trim($f['Activity'] ?? ''),
                    'task'       => trim($f['Task'] ?? ''),
                    'startTime'  => trim($f['StartTime'] ?? ''),
                    'endTime'    => trim($f['EndTimestamp'] ?? ''),
                    'duration'   => trim($f['Durations'] ?? '')
                ];
            }

            // Sort by username (A-Z), then by start time (ascending)
            usort($timers, function($a, $b) {
                $userComparison = strcasecmp($a['userName'], $b['userName']);
                if ($userComparison === 0) {
                    return strcmp($a['startTime'], $b['startTime']);
                }
                return $userComparison;
            });

            return [
                'success' => true,
                'count' => count($timers),
                'timers' => $timers
            ];

        } catch (\Exception $e) {
            log_message('error', 'FileMaker Model Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ];
        } finally {
            // Always logout to clean up the session
            $this->fileMakerAPI->logout();
        }
    }
}