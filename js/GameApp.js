// Game logic

function gameApp() {
    return {

        // Values binded to Frontend
        formData: {
            idPlayer: '' // Value of the guessed player
        },
        correctPlayer: Alpine.$persist({ // Values binded to the correct player's container
            name: "",
            country: "",
            nbrRecords: "",
            nbrCollabs: "",
            firstRecordYear: "",
            lastTracks: [null, null, null]
        }),
        guessedPlayers: Alpine.$persist([]), // Array of guessed players
        timeOut: false, // If the game is over
        idGame: Alpine.$persist(""), // Game ID
        nbrGameCorrectGuesses: "", // Number of correct guesses in the current game


        // Method called at each guess
        // Fetches the guessed player's info and updates the page
        async guessName() {
            var guessedPlayerResult = await fetch('php_scripts/guess.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idGuessedPlayer: this.formData.idPlayer,
                    nbrTries: this.guessedPlayers.length + 1,
                    idGame: this.idGame
                })
            })
                .then(function (response) {
                    if (!response.ok) {
                        return response.json().then(error => {
                            throw new Error(error.error);
                        });
                    }
                    if (response.status == 205) {
                        return "timeout";
                    }
                    return response.json();
                })
                .then(function (guessInfos) {
                    return guessInfos;
                })
                .catch((e) => {
                    console.error('er : ', e);
                })
            if (guessedPlayerResult == "timeout") {
                this.resetGame();
                return;
            }
            this.guessedPlayers.push(guessedPlayerResult.guessedPlayer);
            this.analyzeGuess(guessedPlayerResult.guessedPlayer);
            this.updatePageInfos();
        },


        // Handle each property of the guessed player
        analyzeGuess(guessedPlayer) {
            var guessedPlayerProperties = Object.keys(guessedPlayer);
            // Update correctPlayer with guessedPlayer properties
            guessedPlayerProperties.forEach(property => {
                this.updateCorrectPlayer(property, guessedPlayer[property]);
            });

            // If win
            if (guessedPlayer.name.status == guessStatus.correct) {
                this.showWinModal();
            }

        },


        // Updates the correct player's properties with the guessed player's properties if correct
        updateCorrectPlayer(guessProperty, guess) {
            if (guessProperty == 'lastTracks') {
                for (const [i, track] of guess.entries()) {
                    if (track.status == guessStatus.correct) {
                        this.correctPlayer[guessProperty].splice(i, 1, track.value);
                    }
                }
            } else {
                if (guess.status == guessStatus.correct) {
                    this.correctPlayer[guessProperty] = guess.value;
                }
            }
        },


        // Update the page infos (game ID, number of correct guesses)
        async updatePageInfos() {
            var pageInfos = await fetch('php_scripts/updatePageInfos.php')
                .then(function (response) {
                    if (!response.ok) {
                        return response.json().then(error => {
                            throw new Error(error.error);
                        });
                    }
                    return response.json();
                })
                .then(function (pageInfos) {
                    return pageInfos;
                })
                .catch((e) => {
                    console.error('er : ', e);
                })
            if (this.idGame && this.idGame != pageInfos.id_game) {
                this.resetGame();
            }
            this.idGame = pageInfos.id_game;
            this.nbrGameCorrectGuesses = pageInfos.nbr_correct_guesses;
        },


        // Share win in clipboard
        async shareWin(el) {
            var text = "I guessed the TASer of the day #" + this.idGame + " in " + this.guessedPlayers.length + " " + (this.guessedPlayers.length > 1 ? 'tries' : 'try') + "! \nCan you do better? \n\nhttps://play.mkwtas.com";
            try {
                await navigator.clipboard.writeText(text);
                el.innerHTML = "Copied!";
                el.classList.remove("btn-info");
                el.classList.add("btn-success");
                setTimeout(() => {
                    el.innerHTML = "Share my result 📋";
                    el.classList.remove("btn-success");
                    el.classList.add("btn-info");
                }, 2000);
            } catch (error) {
                console.error(error.message);
            }
        },


        // Show win modal
        showWinModal() {
            const winModal = new bootstrap.Modal(document.getElementById('winModal'));
            winModal.show();
        },


        // Reset the game by removing the local storage and reloading the page
        resetGame() {
            this.timeOut = true;
            localStorage.removeItem("_x_correctPlayer");
            localStorage.removeItem("_x_guessedPlayers");
            localStorage.removeItem("_x_idGame");
            setTimeout(function () {
                window.location.reload();
            }, 2000);
        }

    }
}