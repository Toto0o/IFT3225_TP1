async function create_user(username, password, isAdmin) {

	const response = await fetch('/users', {
		method : 'POST',
		body : JSON.stringify({
			"username": username,
			"password": password,
			"isAdmin": isAdmin
		}),
		headers: {
			'Content-Type': 'application/json'
		}
	});

	if (response.ok) {
		return await response.json();
	} else {
		const error = await response.json();
		throw new Error(error.message);
	}
}

async function login(username, password) {

	const response = await fetch('/login', {
		method : 'POST',
		body : JSON.stringify({
			"username": username,
			"password": password
		}),
		headers: {

			'Content-Type': 'application/json'
		}
	});

	if (response.ok) {
		return await response.json();
	} else {
		const error = await response.json();
		throw new Error(error.message);
	}
}

async function logout() {
	const response = await fetch('/logout', {
		method : 'POST',
		headers: {
			'Content-Type': 'application/json'
		}
	});

	if (response.ok) {
		return await response.json();
	} else {
		const error = await response.json();
		throw new Error(error.message);
	}
}

async function get_user(username) {
	const response = await fetch(`/users/${username}`, {
		method : 'GET',
		headers: {
			'Content-Type': 'application/json'
		}
	});

	if (response.ok) {
		return await response.json();
	} else {
		const error = await response.json();
		throw new Error(error.message);
	}
}

async function update_user(username, password, isAdmin) {
	const response = await fetch('/users', {
		method : 'PUT',
		body : JSON.stringify({
			"username": username,
			"password": password,
			"isAdmin": isAdmin
		}),
		headers: {
			'Content-Type': 'application/json'
		}
	});

	if (response.ok) {
		return await response.json();
	} else {
		const error = await response.json();
		throw new Error(error.message);
	}
}

async function delete_user(username) {
	const response = await fetch('/users', {
		method : 'DELETE',
		body : JSON.stringify({
			"username": username
		}),
		headers: {
			'Content-Type': 'application/json'
		}
	});

	if (response.ok) {
		return await response.json();
	} else {
		const error = await response.json();
		throw new Error(error.message);
	}
}


