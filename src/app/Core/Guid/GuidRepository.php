<?php

namespace App\Core\Guid;

use Carbon\Carbon;

class GuidRepository
{

    /**
     * Create a new guid record
     *
     * @return Guid $guid
     */
    public function create()
    {
        $guidModel = new GuidModel();

        $guidModel->guid = GuidGenerator::generateValue();
        $guidModel->status = Guid::GUID_STATUS_ISSUED;
        $guidModel->created_at = Carbon::now()->toDateTimeString();

        $guidModel->save();

        $guid = new Guid();

        $guid->fill([
            'guid' => $guidModel->guid,
            'status' => $guidModel->status,
            'created_at' => $guidModel->created_at
        ]);

        $guid->modify();

        return $guid;
    }

    /**
     * Find all non assigned
     * guids and return the values
     *
     * @return array
     */
    public function findAllNonAssigned()
    {
        return GuidModel::nonAssigned()->get()->toArray();
    }

    /**
     * Assign a guid to an item
     *
     * @param string $value
     * @param string $assignTo
     *
     * @return Guid $guid
     */
    public function assignGuidTo(string $value, string $assignTo)
    {
        $guidModel = GuidModel::where('guid', '=', $value)
            ->nonAssigned()
            ->first();

        $guid = new Guid();
        $guid->fill([]);
        $guid->modify();

        if ($guidModel->status === Guid::GUID_STATUS_ASSIGNED) {
            return $guid;
        }

        $guidModel->guid = $value;
        $guidModel->status = Guid::GUID_STATUS_ASSIGNED;
        $guidModel->assigned_to = $assignTo;

        $guidModel->save();

        $guid->fill([
            'guid' => $guidModel->guid,
            'assigned_to' => $guidModel->assigned_to,
            'status' => $guidModel->status,
            'created_at' => $guidModel->created_at
        ]);

        $guid->modify();

        return $guid;
    }

    /**
     * Find a guid by its value
     *
     * @param string $guid
     *
     * @return Guid $guid
     */
    public function findByValue(string $guid)
    {
        $guidModel = GuidModel::find($guid);

        $guid = new Guid();
        $guid->fill([]);

        if (empty($guidModel)) {
            $guid->modify();
            return $guid;
        }

        $guid->fill($guidModel->toArray());

        $guid->modify();

        return $guid;
    }

    /**
     * Retrieve guid records
     * older than the given days
     *
     * @param int $days
     *
     * @return Guid[]
     */
    public function findNonAssignedOlderThan(int $days)
    {
        $guidData = GuidModel::olderThan($days)->get()->toArray();

        $guids = [];
        foreach ($guidData as $key => $guidInfo) {
            $guid = new Guid();
            $guid->fill($guidInfo);

            $guids[] = $guid;
        }

        return $guids;
    }

    /**
     * Delete a non assigned guid
     * by its value
     *
     * @param string $guid
     *
     * @return bool
     */
    public function deleteNonAssigned(string $guid)
    {
        $guidModel = GuidModel::where([
            ['guid', '=', $guid],
            ['status', '=', Guid::GUID_STATUS_ISSUED]
        ]);

        return $guidModel->delete();
    }
}
