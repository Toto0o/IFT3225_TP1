async function create_user(String username, String password, Boolean isAdmin) {

	const response = await fetch('/users', {
		method : 'POST',
		
