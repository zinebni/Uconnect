let currentUserId = null;
let messageRefreshInterval = null;

function closeChat() {
    const chatWindow = document.getElementById('chatWindow');
    if (chatWindow) {
        chatWindow.classList.add('hidden');
    }
    currentUserId = null;
    if (messageRefreshInterval) {
        clearInterval(messageRefreshInterval);
    }
}

function scrollToBottom() {
    const messagesArea = document.getElementById('messagesArea');
    if (messagesArea) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
}

function refreshMessages() {
    if (!currentUserId) return;
    
    fetch(`/messages/${currentUserId}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newMessages = doc.querySelector('#messagesArea');
            if (newMessages) {
                document.getElementById('messagesArea').innerHTML = newMessages.innerHTML;
                scrollToBottom();
            }
        })
        .catch(error => console.error('Error refreshing messages:', error));
}

// Initialize chat functionality when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('messageForm');
    const messagesArea = document.getElementById('messagesArea');
    
    if (messagesArea) {
        currentUserId = messagesArea.dataset.userId;
        if (currentUserId) {
            // Start refreshing messages
            messageRefreshInterval = setInterval(refreshMessages, 5000);
            // Initial scroll to bottom
            scrollToBottom();
        }
    }

    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (message && currentUserId) {
                const formData = new FormData(messageForm);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                fetch(`/messages/${currentUserId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'text/html'
                    },
                    body: formData
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newMessages = doc.querySelector('#messagesArea');
                    if (newMessages) {
                        document.getElementById('messagesArea').innerHTML = newMessages.innerHTML;
                        input.value = '';
                        scrollToBottom();
                    }
                })
                .catch(error => console.error('Error sending message:', error));
            }
        });
    }
}); 