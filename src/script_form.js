function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function initLoginValidation() {
    const loginForm = document.getElementById("login-form");
    if (!loginForm) return;

    loginForm.addEventListener("submit", function(event) {
        let errors = [];
        const emailInput = loginForm.querySelector("input[name='email']");
        const email = emailInput.value.trim();
        const password = document.getElementById("password-input").value.trim();

        if (!email) errors.push("Devi inserire l'email");
        else if (!validateEmail(email)) errors.push("Il formato dell'email non è valido");

        if (!password) errors.push("Devi inserire la password");

        if (errors.length > 0) {
            event.preventDefault();
            alert("Errore nel Login:\n- " + errors.join("\n- "));
        }
    });
}

function initRegisterValidation() {
    const registerForm = document.getElementById("Register");
    if (!registerForm) return;

    registerForm.addEventListener("submit", function(event) {
        let errors = [];
        const name = document.getElementById("name").value.trim();
        const surname = document.getElementById("surname").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const hasNumber = /[0-9]/;
        if (name.length < 2) errors.push("Il nome deve avere almeno 2 lettere");
        if (hasNumber.test(name)) errors.push("Il nome non può contenere numeri");
        if (surname.length < 2) errors.push("Il cognome deve avere almeno 2 lettere");
        if (hasNumber.test(surname)) errors.push("Il cognome non può contenere numeri");
        if (!validateEmail(email)) errors.push("Inserisci un indirizzo email valido");
        if (password.length < 6) errors.push("La password deve avere almeno 6 caratteri");

        if (errors.length > 0) {
            event.preventDefault();
            alert("Errore nella Registrazione:\n- " + errors.join("\n- "));
        }
    });
}

function initGroupCreationValidation() {
    const groupForm = document.getElementById("group-creation-form");
    if (!groupForm) return;

    groupForm.addEventListener("submit", function(event) {
        let errors = [];
        const groupName = document.getElementById("group-name").value.trim();
        const facolta = document.getElementById("facolta").value;
        const subject = document.getElementById("subject").value;
        const desc = document.getElementById("description").value.trim();

        if (groupName.length < 3) errors.push("Il nome del gruppo deve contenere almeno 3 caratteri");
        if (facolta === "") errors.push("Devi selezionare un corso di studi");
        if (subject === "") errors.push("Devi selezionare una materia");
        if (desc.length < 10) errors.push("La descrizione deve contenere almeno 10 caratteri");

        if (errors.length > 0) {
            event.preventDefault();
            alert("Impossibile creare il gruppo:\n- " + errors.join("\n- "));
        }
    });
}

function initProfileManagement() {
    const profileForm = document.getElementById("profileForm");
    if (!profileForm) return;

    profileForm.addEventListener("submit", function(event) {
        let errors = [];
        const enrollmentInput = document.getElementById("enrollment_year").value.trim();
        const yearSelect = document.getElementById("year").value;
        const facultySelect = document.getElementById("faculty").value;
        const modeSelect = document.getElementById("mode").value;

        if (enrollmentInput !== "") {
            const year = parseInt(enrollmentInput, 10);
            if (isNaN(year) || year < 1968 || year > 2025) {
                errors.push("L'anno di immatricolazione deve essere tra il 1968 e il 2025");
            }
        } else {
            errors.push("Inserisci l'anno di immatricolazione");
        }

        if (yearSelect === "") errors.push("Seleziona il tuo anno universitario");
        if (facultySelect === "") errors.push("Seleziona la tua facoltà");
        if (modeSelect === "") errors.push("Seleziona la tua modalità di studio preferita");

        if (errors.length > 0) {
            event.preventDefault();
            alert("Errore Profilo:\n- " + errors.join("\n- "));
        }
    });

    const geoButton = document.getElementById("geo-btn");
    if (geoButton) {
        geoButton.addEventListener("click", function(e) {
            e.preventDefault();
            if (navigator.geolocation) {
                geoButton.textContent = "Acquisizione in corso...";
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lon = pos.coords.longitude;
                        document.getElementById("latitude").value = lat;
                        document.getElementById("longitude").value = lon;
                        geoButton.textContent = `Posizione OK: ${lat.toFixed(2)}, ${lon.toFixed(2)}`;
                        geoButton.disabled = true;
                    },
                    () => {
                        geoButton.textContent = "Errore geolocalizzazione";
                        geoButton.style.backgroundColor = "red";
                    }
                );
            }
        });
    }
}
