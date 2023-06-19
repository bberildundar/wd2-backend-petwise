<?php
namespace Services;

use Repositories\UserRepository;
use Models\User;

class UserService {

    private $userRepository;

    function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    function getAll($offset = null, $limit = null) {
        return $this->userRepository->getAll($offset, $limit);
    }

    function getById($id) {
        return $this->userRepository->getById($id);
    }
    function getByEmail($id) {
        return $this->userRepository->getByEmail($id);
    }

    function create(User $user) {
        return $this->userRepository->create($user);
    }

    function update(User $user, $id) {
        return $this->userRepository->update($user, $id);
    }

    function delete($id) {
        return $this->userRepository->delete($id);
    }

    public function checkUsernamePassword($username, $password) {
        return $this->userRepository->checkEmailPassword($username, $password);
    }
}

?>