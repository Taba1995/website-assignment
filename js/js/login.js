document.addEventListener("DOMContentLoaded", function () {
	// Select the form element
	const form = document.querySelector("form");

	// Add a submit event listener to the form
	form.addEventListener("submit", function (event) {
		// Get the trimmed values from the username and password input fields
		const username = document
			.querySelector('input[name="username"]')
			.value.trim();
		const password = document
			.querySelector('input[name="password"]')
			.value.trim();

		// Basic validation to check if either field is empty
		if (username === "" || password === "") {
			alert("Please fill in both username and password."); // Alert the user
			event.preventDefault(); // Prevent form submission if validation fails
		}
	});
});
