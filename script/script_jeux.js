document.addEventListener('DOMContentLoaded', function() {
    const demarage = document.getElementById('demarage');
    if (!demarage) return;

    demarage.addEventListener('click', function() {
        initializeGame();
        startCountdown();
    });

    document.getElementById("reponse_user").addEventListener("input", function() {
        if (this.value.length > 3) {
            document.getElementById('pas_mt').id = "datalist_joueurs";
        }
    });
});

function initializeGame() {
    const elements = {
        submitButton: document.getElementById('submitButton'),
        titleElement: document.getElementById('titre_joueur'),
        chronoContainer: document.getElementById('chrono-container'),
        playerInfo: document.getElementById('joueur_info'),
        userInput: document.getElementById('reponse_user')
    };

    Object.values(elements).forEach(element => {
        if (element) element.style.display = 'flex';
    });
}

function startCountdown() {
    const countdownElement = document.getElementById('chrono');
    const infoElement = document.getElementById('info');
    const reponseUser = document.getElementById('reponse_user');
    const submitButton = document.getElementById('submitButton');
    let start = 18;

    const countdownInterval = setInterval(function() {
        start--;
        if (countdownElement) countdownElement.textContent = start;

        handleInfoDisplay(start, infoElement);

        if (start === 0) {
            clearInterval(countdownInterval);
            handleTimeUp();
        }
    }, 1000);

    if (submitButton) {
        submitButton.addEventListener('click', function() {
            handleSubmission(reponseUser.value);
        });
    }
}

function handleInfoDisplay(time, infoElement) {
    // Ces valeurs devraient être passées depuis PHP via des data attributes
    const timeToInfo = {
        18: window.gameInfo?.info1,
        16: window.gameInfo?.info2,
        14: window.gameInfo?.info3,
        12: window.gameInfo?.info4,
        10: window.gameInfo?.info5
    };

    if (timeToInfo[time] && infoElement) {
        appendInfo(timeToInfo[time], infoElement);
    }
}

function appendInfo(info, element) {
    const infoParagraph = document.createElement('p');
    infoParagraph.textContent = info;
    element.appendChild(infoParagraph);
}

function handleTimeUp() {
    const form = document.getElementById('cherche_et_trouve');
    if (!form) return;

    document.getElementById('reponse').value = "time";
    document.getElementById('id_personne').value = window.playerId;
    form.submit();
    form.style.display = 'none';
}

function handleSubmission(reponse) {
    const form = document.getElementById('cherche_et_trouve');
    if (!form) return;

    document.getElementById('reponse').value = reponse;
    document.getElementById('id_personne').value = window.playerId;
    form.submit();
}