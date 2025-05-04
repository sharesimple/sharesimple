// Code-Input-Script by Dave Bitter
// Found under https://codepen.io/davebitter/pen/VweaZqY
// Modified by Konstantin Protzen

// Elements
const charCodeForm = document.querySelector('[data-char-code-form]');
const charCodeInputs = [...charCodeForm.querySelectorAll('[data-char-code-input]')];

// Input Storage
var input_id = "";

// Event callbacks
const handleInput = ({ target }) => {
    // Allow only characters (letters)
    target.value = target.value.replace(/[^a-zA-Z]/g, '');
    if (!target.value.length) {
        notReady();
        return target.value = null;
    }

    const inputLength = target.value.length;
    let currentIndex = charCodeInputs.indexOf(target);

    if (inputLength > 1) {
        const inputValues = target.value.split('');

        inputValues.forEach((value, valueIndex) => {
            const nextValueIndex = currentIndex + valueIndex;

            if (nextValueIndex >= charCodeInputs.length) { return; }

            charCodeInputs[nextValueIndex].value = value;
        });

        currentIndex += inputValues.length - 2;
    }

    const nextIndex = currentIndex + 1;

    if (nextIndex < charCodeInputs.length) {
        charCodeInputs[nextIndex].focus();
    }

    checkReadyState();
}

const handleKeyDown = e => {
    const { code, target } = e;
    const currentIndex = charCodeInputs.indexOf(target);
    const previousIndex = currentIndex - 1;
    const nextIndex = currentIndex + 1;

    const hasPreviousIndex = previousIndex >= 0;
    const hasNextIndex = nextIndex <= charCodeInputs.length - 1;

    switch (code) {
        case 'ArrowLeft':
        case 'ArrowUp':
            if (hasPreviousIndex) {
                charCodeInputs[previousIndex].focus();
            }
            e.preventDefault();
            break;

        case 'ArrowRight':
        case 'ArrowDown':
            if (hasNextIndex) {
                charCodeInputs[nextIndex].focus();
            }
            e.preventDefault();
            break;
        case 'Backspace':
            if (!e.target.value.length && hasPreviousIndex) {
                charCodeInputs[previousIndex].value = null;
                charCodeInputs[previousIndex].focus();
                notReady();
            }
            break;
        case 'Enter':
            if (hasNextIndex) {
                charCodeInputs[nextIndex].focus();
            } else {
                checkReadyState(true);
            }
            break;
        default:
            break;
    }
}

// Check if all inputs are populated
const checkReadyState = (immediateRedirect = false) => {
    const allPopulated = charCodeInputs.every(input => input.value && input.value.trim().length > 0);
    if (allPopulated) {
        const allChars = charCodeInputs.map(input => input.value).join('');
        if (!immediateRedirect) ready(allChars);
        else location.assign(`/rooms/?id=${allChars}`);
    } else {
        notReady();
    }
}

// Ready and Not Ready methods
const joinButton = document.getElementById("joinButton");
const ready = (roomId) => {
    joinButton.onclick = () => { location.assign(`/rooms/?id=${roomId}`); };
    joinButton.disabled = false;
    joinButton.innerText = "Join room: " + roomId.toUpperCase();
}
const notReady = () => {
    joinButton.onclick = () => { };
    joinButton.disabled = true;
    joinButton.innerText = "Enter the room code";
}
notReady(); // Ensure state on load

// Event listeners
charCodeForm.addEventListener('input', handleInput);
charCodeForm.addEventListener('keydown', handleKeyDown);
