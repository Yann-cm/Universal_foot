document.addEventListener('DOMContentLoaded', function() {
    const demarage = document.getElementById('demarage');
    if (!demarage) return;

    demarage.addEventListener('click', function() {
        initializeGame();
        startCountdown();
    });

    document.getElementById("reponse_user").addEventListener("input", function() {
        if (this.value.length > 2) {
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
    let start = 20;

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
    const timeChecks = [
        { time: 19, info: window.gameInfo?.info1 },
        { time: 17, info: window.gameInfo?.info2 },
        { time: 14, info: window.gameInfo?.info3 },
        { time: 12, info: window.gameInfo?.info4 },
        { time: 10, info: window.gameInfo?.info5 }
    ];

    const currentTimeCheck = timeChecks.find(check => time === check.time);
    if (currentTimeCheck?.info && infoElement) {
        appendInfo(currentTimeCheck.info, infoElement);
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