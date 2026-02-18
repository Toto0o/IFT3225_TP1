<?php

class Account {
	
	private $id;
	private $name;
	private $authenticated;

	public function add_account(string $username, string $password): int {}

	public function getId_from_name(string $name): ?int {}

	public function edit_account() {}

	public function delete_account(int $id) {}

	public function login(string $username, string $password) {}

	public function session_login(int $id) {}
	
	public function register_login() {}

}
