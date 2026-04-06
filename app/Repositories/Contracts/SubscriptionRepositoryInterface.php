<?php
namespace App\Repositories\Contracts;

interface SubscriptionRepositoryInterface
{
    public function all();
    public function store(array $data);
    public function show($id);
    public function userSubscriptions($userId);
    public function update($id, array $data);
    public function destroy($id);
}
