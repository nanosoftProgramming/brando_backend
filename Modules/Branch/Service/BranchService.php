<?php

namespace Modules\Branch\Service;

use Illuminate\Support\Facades\File;
use Modules\Admin\App\Models\Admin;
use Modules\Branch\App\Models\Branch;
use Modules\Common\Helpers\UploadHelper;

class BranchService
{
    use UploadHelper;

    public function findAll($data = [])
    {
        $branches = Branch::query()
            ->available()
            ->with(['restaurant', 'city', 'orders'])
            ->filter($data)
            ->orderByDesc('created_at');

        return getCaseCollection($branches, $data); // Assuming this handles pagination
    }

    public function findById($id)
    {
        return Branch::findOrFail($id);
    }

    public function create($data, $managerData)
    {
        if (request()->hasFile('manager_image')) {
            $managerData['image'] = $this->upload(request()->file('manager_image'), 'admin');
        }
        $branch = Branch::create($data);
        $managerData['branch_id'] = $branch->id;
        $branchManager = Admin::create($managerData);
        $branchManager->assignRole('Branch Manager');

        return $branch->fresh()->load('manager');
    }

    public function update($branch, $data, $managerData)
    {
        if (request()->hasFile('manager_image')) {
            File::delete(public_path('uploads/admin/'.$this->getImageName('admin', $branch->manager->image)));
            $managerData['image'] = $this->upload(request()->file('manager_image'), 'admin');
        }
        if ($data) {
            $branch->update($data);
        }
        if ($managerData) {
            $branch->manager()->update($managerData);
        }

        return $branch->fresh()->load(['restaurant', 'city', 'manager']);
    }

    public function delete($branch)
    {
        $branch->delete();
    }

    public function toggleActivate($branch)
    {
        $branch->update(['is_active' => ! $branch->is_active]);

        return $branch->fresh();
    }
}
