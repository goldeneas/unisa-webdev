function onGeoSuccess(position) {
    const lat = position.coords.latitude;
    const lon = position.coords.longitude;
    
    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lon;
    
    const geoButton = document.getElementById("geo-btn");
    if (geoButton) {
        geoButton.textContent = `Posizione Acquisita: Lat=${lat.toFixed(4)}, Lon=${lon.toFixed(4)}`;
        geoButton.disabled = true;
    }
}

function onGeoError(error) {
    let message = "Impossibile ottenere la posizione.";
    
    const geoButton = document.getElementById("geo-btn");
    if (geoButton) {
        geoButton.textContent = `Errore Geolocalizzazione: ${message}`;
        geoButton.disabled = true;
        geoButton.style.backgroundColor = 'red';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    const loginForm = document.getElementById("login-form");
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            let errors = [];
            const emailInput = loginForm.querySelector("input[name='email']");
            const email = emailInput.value.trim();
            const password = document.getElementById("password-input").value.trim();

            if (!email) {
                errors.push("Devi inserire l'email");
            } else if (!validateEmail(email)) {
                errors.push("Il formato dell'email non è valido");
            }

            if (!password) {
                errors.push("Devi inserire la password");
            }

            if (errors.length > 0) {
                event.preventDefault();
                alert("Errore nel Login:\n- " + errors.join("\n- "));
            }
        });
    }

    const registerForm = document.getElementById("Register");
    if (registerForm) {
        registerForm.addEventListener("submit", function(event) {
            let errors = [];
            const name = document.getElementById("name").value.trim();
            const surname = document.getElementById("surname").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value;

            if (name.length < 2) errors.push("Devi inserire più lettere nel nome");
            if (surname.length < 2) errors.push("Devi inserire più lettere nel cognome");

            if (!email) {
                errors.push("Devi inserire più lettere l'email ");
            } else if (!validateEmail(email)) {
                errors.push("Inserisci un indirizzo email valido ");
            }

            if (password.length < 6) errors.push("La password deve avere almeno 6 caratteri.");

            if (errors.length > 0) {
                event.preventDefault();
                alert("Errore nella Registrazione:\n- " + errors.join("\n- "));
            }
        });
    }

    const groupForm = document.getElementById("group-creation-form");
    if (groupForm) {
        groupForm.addEventListener("submit", function(event) {
            let errors = [];
            const groupName = document.getElementById("group-name").value.trim();
            const facolta = document.getElementById("facolta").value;
            const subject = document.getElementById("subject");
            const desc = document.getElementById("description").value.trim();

            if (groupName.length < 3) {
                errors.push("Il nome del gruppo deve contenere almeno 3 caratteri.");
            }

            if (facolta === "") {
                errors.push("Devi selezionare un corso di studi.");
            }

            if (subject.value === "" || subject.disabled) {
                errors.push("Devi selezionare una materia.");
            }

            if (desc.length < 10) {
                errors.push("La descrizione deve contenere almeno 10 caratteri.");
            }

            if (errors.length > 0) {
                event.preventDefault();
                alert("Impossibile creare il gruppo:\n- " + errors.join("\n- "));
            }
        });
    }

    const searchForm = document.getElementById("search-bar");
    if (searchForm) {
        searchForm.addEventListener("submit", function(event) {
            const searchInput = document.getElementById("course-input").value.trim();
            if (searchInput.length < 2) {
                event.preventDefault();
                alert("Inserisci almeno 2 caratteri per cercare.");
            }
        });
    }

    const profileForm = document.getElementById("profileForm");
    if (profileForm) {
        profileForm.addEventListener("submit", function(event) {
            let errors = [];
            const enrollmentInput = document.getElementById("enrollment_year").value.trim();
            const yearSelect = document.getElementById("year").value;
            const facultySelect = document.getElementById("faculty").value;
            const modeSelect = document.getElementById("mode").value;

            if (enrollmentInput !== "") {
                const year = parseInt(enrollmentInput, 10);
                if (isNaN(year)) {
                    errors.push("L'anno di immatricolazione non è un numero valido.");
                } else if (year < 1968 || year > 2025) {
                    errors.push("L'anno di immatricolazione deve essere compreso tra il 1968 e il 2025.");
                }
            }

            if (yearSelect === "") errors.push("Seleziona il tuo anno universitario.");
            if (enrollmentInput === "") errors.push("Seleziona il tuo anno di immatricolazione.")
            if (facultySelect === "") errors.push("Seleziona la tua facoltà.");
            if (modeSelect === "") errors.push("Seleziona la modalità preferita.");

            if (errors.length > 0) {
                event.preventDefault();
                alert("Impossibile salvare il profilo:\n- " + errors.join("\n- "));
            }
        });

       
        const geoButton = document.getElementById("geo-btn");
        if (geoButton) {
            geoButton.addEventListener("click", function(event) {
                event.preventDefault(); 
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(onGeoSuccess, onGeoError); 
                    geoButton.textContent = "Acquisizione in corso...";
                } else {
                    alert("Il tuo browser non supporta la geolocalizzazione.");
                }
            });
        }
    }
});
