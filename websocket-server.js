const WebSocket = require('ws');
const http = require('http');
const server = http.createServer();
const wss = new WebSocket.Server({ server });

// Store connected clients
const clients = new Map();
const userRooms = new Map();

wss.on('connection', (ws, req) => {
    console.log('New WebSocket connection');
    
    // Extract user ID from query parameters
    const url = new URL(req.url, `http://${req.headers.host}`);
    const userId = url.searchParams.get('user_id');
    
    if (userId) {
        clients.set(userId, ws);
        console.log(`User ${userId} connected`);
        
        // Send connection confirmation
        ws.send(JSON.stringify({
            type: 'connection',
            userId: userId,
            message: 'Connected to chat server'
        }));
    }
    
    ws.on('message', (message) => {
        try {
            const data = JSON.parse(message);
            handleMessage(data, ws, userId);
        } catch (error) {
            console.error('Error parsing message:', error);
        }
    });
    
    ws.on('close', () => {
        if (userId) {
            clients.delete(userId);
            console.log(`User ${userId} disconnected`);
        }
    });
    
    ws.on('error', (error) => {
        console.error('WebSocket error:', error);
    });
});

function handleMessage(data, ws, senderId) {
    switch (data.type) {
        case 'send_message':
            handleSendMessage(data, senderId);
            break;
        case 'typing':
            handleTyping(data, senderId);
            break;
        case 'join_room':
            handleJoinRoom(data, senderId);
            break;
        case 'leave_room':
            handleLeaveRoom(data, senderId);
            break;
        default:
            console.log('Unknown message type:', data.type);
    }
}

function handleSendMessage(data, senderId) {
    const { receiver_id, message } = data;
    
    // Send to receiver if online
    const receiverWs = clients.get(receiver_id.toString());
    if (receiverWs && receiverWs.readyState === WebSocket.OPEN) {
        receiverWs.send(JSON.stringify({
            type: 'new_message',
            sender_id: senderId,
            receiver_id: receiver_id,
            message: message,
            timestamp: new Date().toISOString()
        }));
    }
    
    // Send confirmation to sender
    const senderWs = clients.get(senderId.toString());
    if (senderWs && senderWs.readyState === WebSocket.OPEN) {
        senderWs.send(JSON.stringify({
            type: 'message_sent',
            message_id: Date.now(), // In real app, this would be the actual message ID
            receiver_id: receiver_id
        }));
    }
}

function handleTyping(data, senderId) {
    const { receiver_id, is_typing } = data;
    
    // Send typing indicator to receiver
    const receiverWs = clients.get(receiver_id.toString());
    if (receiverWs && receiverWs.readyState === WebSocket.OPEN) {
        receiverWs.send(JSON.stringify({
            type: 'user_typing',
            user_id: senderId,
            is_typing: is_typing
        }));
    }
}

function handleJoinRoom(data, userId) {
    const { room_id } = data;
    
    if (!userRooms.has(userId)) {
        userRooms.set(userId, new Set());
    }
    userRooms.get(userId).add(room_id);
    
    console.log(`User ${userId} joined room ${room_id}`);
}

function handleLeaveRoom(data, userId) {
    const { room_id } = data;
    
    if (userRooms.has(userId)) {
        userRooms.get(userId).delete(room_id);
    }
    
    console.log(`User ${userId} left room ${room_id}`);
}

// Broadcast message to all users in a room
function broadcastToRoom(roomId, message) {
    for (const [userId, rooms] of userRooms.entries()) {
        if (rooms.has(roomId)) {
            const ws = clients.get(userId);
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify(message));
            }
        }
    }
}

// Get online users
function getOnlineUsers() {
    return Array.from(clients.keys());
}

// Health check endpoint
server.on('request', (req, res) => {
    if (req.url === '/health') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            status: 'ok',
            online_users: getOnlineUsers().length,
            timestamp: new Date().toISOString()
        }));
    } else {
        res.writeHead(404);
        res.end();
    }
});

const PORT = process.env.WEBSOCKET_PORT || 6001;

server.listen(PORT, () => {
    console.log(`WebSocket server running on port ${PORT}`);
    console.log(`Health check available at http://localhost:${PORT}/health`);
});

module.exports = { wss, clients, userRooms }; 