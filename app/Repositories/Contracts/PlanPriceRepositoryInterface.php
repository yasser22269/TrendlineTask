<?php
namespace App\Repositories\Contracts;

interface PlanPriceRepositoryInterface
{
    public function all();
    public function show($id);
    public function store(array $data);
    public function update($id, array $data);
    public function destroy($id);
}
