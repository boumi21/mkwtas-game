// JS utils functions, constants etc


const guessStatus = Object.freeze({"correct":1, "incorrect":2, "present":3, "less":4, "more":5});

const guessStatusBackground = Object.freeze({1:"bg-success", 2:"bg-danger", 3:"bg-warning", 4:"bg-danger", 5:"bg-danger"});

const guessStatusText = Object.freeze({1:"text-success", 2:"text-danger", 3:"text-warning", 4:"text-danger", 5:"text-danger"});

const guessStatusIcon = Object.freeze({1:"✓", 2:"✘", 3:"?", 4:"▼", 5:"▲"});

function getGuessStatusBackground(status) {
    return guessStatusBackground[status];
}

function getGuessStatusText(status) {
    return guessStatusText[status];
}

function getGuessStatusIcon(status) {
    return guessStatusIcon[status];
}