// Simple Chat Implementation for Class Presentation
class SimpleChat {
    constructor() {
        this.selectedUserId = null;
        this.currentUserId = null;
        this.init();
    }

    init() {
        // Get current user ID from meta tag
        this.currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        
        // Set up event listeners
        this.setupEventListeners();
        
        // Start polling for new messages
        this.startPolling();
    }

    setupEventListeners() {
        // Handle contact selection
        document.querySelectorAll('.contact-item').forEach(item => {
            item.addEventListener('click', function() {
                const contactId = this.getAttribute('data-id');
                const contactName = this.querySelector('h6').textContent;
                const contactRole = this.querySelector('small').textContent;
                
                // Update active state
                document.querySelectorAll('.contact-item').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                
                // Update chat header
                document.getElementById('current-chatting').textContent = contactName;
                document.getElementById('current-role').textContent = contactRole;
                document.getElementById('receiver_id').value = contactId;
                
                // Enable message input
                document.getElementById('message').disabled = false;
                document.querySelector('#message-form button[type="submit"]').disabled = false;
                
                // Load messages
                if (window.chat) {
                    window.chat.selectUser(contactId, contactName, contactRole);
                }
            });
        });
        
        // Handle message form submission
        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = document.getElementById('message').value.trim();
            const receiverId = document.getElementById('receiver_id').value;
            
            if (message && receiverId && window.chat) {
                window.chat.sendMessage(message, receiverId);
                document.getElementById('message').value = '';
            }
        });
        
        // Handle message input keypress
        document.getElementById('message').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('message-form').dispatchEvent(new Event('submit'));
            }
        });
    }

    selectUser(userId, userName, userRole) {
        this.selectedUserId = userId;
        
        // Update UI
        document.querySelectorAll('.contact-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector(`[data-id="${userId}"]`).classList.add('active');

        // Show chat interface
        document.getElementById('current-chatting').textContent = userName;
        document.getElementById('current-role').textContent = userRole;
        document.getElementById('receiver_id').value = userId;

        // Load messages
        this.fetchMessages();
    }

    fetchMessages() {
        if (!this.selectedUserId) return;

        fetch(`/admin/chat/messages/${this.selectedUserId}`)
            .then(response => response.json())
            .then(messages => {
                this.displayMessages(messages);
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }

    displayMessages(messages) {
        const chatBox = document.getElementById('chat-box');
        const currentUserId = this.currentUserId;
        
        if (messages.length === 0) {
            chatBox.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <h5>No messages yet</h5>
                    <p>Start the conversation by sending a message</p>
                </div>
            `;
            return;
        }

        chatBox.innerHTML = messages.map(message => {
            const isSent = message.sender_id == currentUserId;
            const messageClass = isSent ? 'sent' : 'received';
            const time = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            return `
                <div class="message ${messageClass}" data-message-id="${message.id}">
                    <div class="message-content">
                        ${this.escapeHtml(message.message)}
                    </div>
                    <div class="message-time text-${isSent ? 'end' : 'start'}">
                        ${time}
                    </div>
                    ${isSent ? '<div class="message-status status-sent">✓ Sent</div>' : ''}
                </div>
            `;
        }).join('');

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    sendMessage(message, receiverId) {
        fetch('/admin/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                receiver_id: receiverId,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Add message to chat immediately
                this.addMessageToChat(data.message);
                // Refresh messages to get the latest
                this.fetchMessages();
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Failed to send message. Please try again.');
        });
    }

    addMessageToChat(message) {
        const chatBox = document.getElementById('chat-box');
        const currentUserId = this.currentUserId;
        const isSent = message.sender_id == currentUserId;
        const messageClass = isSent ? 'sent' : 'received';
        const time = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        const messageHtml = `
            <div class="message ${messageClass}" data-message-id="${message.id}">
                <div class="message-content">
                    ${this.escapeHtml(message.message)}
                </div>
                <div class="message-time text-${isSent ? 'end' : 'start'}">
                    ${time}
                </div>
                ${isSent ? '<div class="message-status status-sent">✓ Sent</div>' : ''}
            </div>
        `;
        
        // Remove "no messages" placeholder if exists
        const placeholder = chatBox.querySelector('.text-center.text-muted');
        if (placeholder) {
            placeholder.remove();
        }
        
        chatBox.insertAdjacentHTML('beforeend', messageHtml);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    updateUnreadCount() {
        fetch('/admin/chat/unread-count')
            .then(response => response.json())
            .then(data => {
                const notification = document.getElementById('chat-notification');
                if (notification) {
                    if (data.count > 0) {
                        notification.textContent = data.count;
                        notification.style.display = 'inline';
                    } else {
                        notification.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error updating unread count:', error);
            });
    }

    startPolling() {
        // Poll for new messages every 5 seconds
        setInterval(() => {
            if (this.selectedUserId) {
                this.fetchMessages();
            }
            this.updateUnreadCount();
        }, 5000);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chat when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.chat = new SimpleChat();
    
    // Check for user parameter in URL for direct chat
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('user');
    if (userId && window.chat) {
        const userItem = document.querySelector(`[data-id="${userId}"]`);
        if (userItem) {
            const userName = userItem.querySelector('h6').textContent;
            const userRole = userItem.querySelector('small').textContent;
            window.chat.selectUser(userId, userName, userRole);
        }
    }
}); 