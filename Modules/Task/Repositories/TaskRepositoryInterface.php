<?php

namespace Modules\Task\Repositories;

interface TaskRepositoryInterface {
    public function create(array $data);
    public function findById(int $id);
    public function filter(array $filters, int $perPage = 10);
    public function update(int $id, array $data);
}
