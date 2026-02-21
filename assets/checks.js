

function validate_username(String username) {
	return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(username);
}

function validate_password(String password) {
	return password.length() > 5;
}

function compare_password(String passowrd, String repeat) {
	return password == repeat;
}


async function username_unique(String username) {

	const url = '/users/' + username;
	const response = await fetch(url, {
		method : 'GET',
		headers : {
			'Content-Type' : 'application/json'
		}
	});

	if (!response.ok) {
		const text = await response.message();
		throw new Error(message);
	}

	const data = await response.json();

	const id = data.id;

	return (id == NULL);
}


