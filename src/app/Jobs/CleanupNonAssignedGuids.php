<?php
/**
 * Created by PhpStorm.
 * User: herant
 * Date: 22-03-18
 * Time: 16:22
 */

namespace App\Jobs;


use App\Core\Guid\GuidRepository;

class CleanupNonAssignedGuids extends Job
{

    /**
     * Contains the guid repository
     *
     * @var GuidRepository
     */
    private $guidRepository;

    /**
     * Create a new job instance.
     */
    public function __construct(GuidRepository $guidRepository)
    {
        $this->guidRepository = $guidRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $guids = $this->guidRepository
            ->findNonAssignedOlderThan(10);

        foreach ($guids as $guid) {
            $this->guidRepository
                ->deleteNonAssigned($guid->getGuid());
        }
    }
}
