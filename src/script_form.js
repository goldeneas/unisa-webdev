document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("login-form");
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            const email = loginForm.querySelector("input[name='email']").value.trim();
            const password = document.getElementById("password-input").value.trim();

            if (!email || !password) {
                event.preventDefault();
                alert("Devi inserire email e password per accedere");
            }
        });
    }

    const registerForm = document.getElementById("Register");
    if (registerForm) {
        registerForm.addEventListener("submit", function(event) {
            let errors = [];
            const name = document.getElementById("name").value.trim();
            const surname = document.getElementById("surname").value.trim();
            const password = document.getElementById("password").value;

            if (name.length < 2) errors.push("Il nome è troppo corto.");
            if (surname.length < 2) errors.push("Il cognome è troppo corto.");
            if (password.length < 6) errors.push("La password deve avere almeno 6 caratteri.");

            if (errors.length > 0) {
                event.preventDefault();
                alert("C'è stato un errore nella registrazione" );
            }
        });
    }


    const groupForm = document.getElementById("group-creation-form");
    if (groupForm) {
        groupForm.addEventListener("submit", function(event) {
            const subject = document.getElementById("subject");
            const desc = document.getElementById("description").value.trim();

            if (subject.value === "" || subject.disabled) {
                event.preventDefault();
                alert("Devi selezionare prima il corso e successivamente la materia");
                return;
            }

            if (desc.length < 10) {
                event.preventDefault();
                alert("La descrizione deve essere lunga almeno 10 caratteri");
            }
        });
    }

    const searchForm = document.getElementById("search-bar");
    if (searchForm) {
        searchForm.addEventListener("submit", function(event) {
            const searchInput = document.getElementById("course-input").value.trim();
            if (searchInput.length < 2) {
                event.preventDefault();
            }
        });
    }
});


document.addEventListener("DOMContentLoaded", function() {
    const profileForm = document.getElementById("profileForm");

    if (profileForm) {
        profileForm.addEventListener("submit", function(event) {
            let errors = [];
            const enrollmentInput = document.getElementById("enrollment_year").value.trim();

            if (enrollmentInput !== "") {
                const year = parseInt(enrollmentInput, 10);

                if (isNaN(year)) {
                    errors.push("L'anno inserito non è valido.");
                } else if (year < 1968 || year > 2025) {
                    errors.push("L'anno deve essere compreso tra il 1968 e il 2025.");
                }
            }

            if (errors.length > 0) {
                event.preventDefault();
                alert("Impossibile salvare il profilo ");
            }
        });
    }
});