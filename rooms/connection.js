const serverAddress = 'wss://rooms.sharesimple.de:443';
let websocket;
let roomId;
let fileInput;
let readyStateDisplay;
let readyStateIcon;
let receivedFilesDisplay;
let broadcastButton;

function connectWebSocket(roomId) {
    // If a WebSocket connection already exists, close it
    if (websocket) websocket.close();

    websocket = new WebSocket(serverAddress);

    websocket.onopen = () => {
        console.log('WebSocket connected.');
        websocket.send(JSON.stringify({ type: 'joinRoom', payload: { roomId } }));
        // Create keep-alive interval
        setInterval(() => {
            if (websocket.readyState === WebSocket.OPEN) {
                websocket.send(JSON.stringify({ type: 'ping' }));
            }
        }, 20000);
    };

    websocket.onmessage = event => {
        try {
            const message = JSON.parse(event.data);
            console.log('Message from server:', message);
            const type = message.type;
            const payload = message.payload;

            if (type === 'roomJoined') { // Client joined a room
                roomId = payload.roomId;
                document.getElementById('roomTitle').textContent = `Welcome to room: ${roomId}`;
                history.pushState({}, '', `${window.location.pathname}?id=${roomId}`); // Push state to include room ID in URL
            } else if (type === 'ready') {
                clients = payload.clients;
                readyStateDisplay.textContent = 'Ready! You are connected to ' + (clients - 1) + ' other ' + (clients > 2 ? 'users' : 'user') + '.';
                readyStateIcon.classList.add('ready');
                broadcastButton.disabled = false;
            } else if (type === 'notReady') {
                readyStateDisplay.textContent = 'Not Ready. Waiting for another user.';
                readyStateIcon.classList.remove('ready');
                broadcastButton.disabled = true;
            } else if (type === 'file') {
                // Basic display of received file data (you'll need to handle binary data properly)
                const receivedData = payload.data;
                const sender = payload.senderId;
                console.log(`Received file data from ${sender}:`, receivedData);
                const blob = new Blob([new Uint8Array(receivedData.data)], { type: 'application/octet-stream' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = receivedData.name || `received_file_from_${sender}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            } else if (type === 'error') {
                console.error('Server error:', payload.message);
                alert(`Server error: ${payload.message}`);
            }
        } catch (error) {
            console.error('Error processing message from server:', error);
        }
    };

    websocket.onclose = () => {
        console.log('WebSocket connection closed.');
        readyStateDisplay.textContent = 'Disconnected from server.';
        readyStateIcon.classList.remove('ready');
        broadcastButton.disabled = true;
    };

    websocket.onerror = error => {
        console.error('WebSocket error:', error);
        readyStateDisplay.textContent = 'Error connecting to server.';
        readyStateIcon.classList.remove('ready');
        broadcastButton.disabled = true;
    };
}

function createNewRoom() {
    connectWebSocket(null); // No roomId to join initially, server will generate one
}

function joinRoom(roomIdToJoin) {
    connectWebSocket(roomIdToJoin);
}

function sendFile() {
    if (websocket && websocket.readyState === WebSocket.OPEN && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = () => {
            websocket.send(JSON.stringify({ type: 'file', payload: { name: file.name, size: file.size, data: Array.from(new Uint8Array(reader.result)) } }));
            console.log(JSON.stringify({ type: 'file', payload: { name: file.name, size: file.size, data: Array.from(new Uint8Array(reader.result)) } }));
        };

        reader.onerror = error => {
            console.error('Error reading file:', error);
            alert('Failed to read the file.');
        };

        reader.readAsArrayBuffer(file);
    } else {
        alert('No file selected or not connected to the server.');
    }
}

// This function is called when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // const joinRoomInput = document.getElementById('joinRoomId');
    // const newRoomButton = document.getElementById('newRoomButton');
    // const joinButton = document.getElementById('joinButton');
    fileInput = document.getElementById('fileInput');
    readyStateDisplay = document.getElementById('readyState');
    readyStateIcon = document.getElementById('readyStateIcon');
    receivedFilesDisplay = document.getElementById('receivedFiles');
    broadcastButton = document.getElementById('broadcastButton');

    // Check for ?id query parameter in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const roomIdFromUrl = urlParams.get('id');
    if (roomIdFromUrl) {
        joinRoom(roomIdFromUrl);
    } else {
        createNewRoom();
    }

    // newRoomButton.addEventListener('click', createNewRoom);
    // joinButton.addEventListener('click', () => {
    //     const enteredRoomId = joinRoomInput.value.trim();
    //     if (enteredRoomId) {
    //         joinRoom(enteredRoomId);
    //     } else {
    //         alert('Please enter a room ID or create a new one.');
    //     }
    // });

    if (fileInput) {
        fileInput.addEventListener('change', sendFile);
    }
    if (broadcastButton) {
        broadcastButton.addEventListener('click', () => fileInput.click());
    }
});