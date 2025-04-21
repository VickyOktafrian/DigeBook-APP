<!-- resources/views/components/chatbot.blade.php -->
<div>
    <div id="chat-toggle-btn" class="chat-toggle-btn">
        <i class="icon icon-bubbles"></i>
        <span>LibrAI</span>
    </div>

    <div id="chat-modal" class="chat-modal">
        <div class="chat-header">
            <h4>LibrAI Assistant</h4>
            <button id="close-chat" class="close-btn">&times;</button>
        </div>
        <div class="chat-body">
            <div id="chat-messages" class="chat-messages">
                <div class="bot-message">
                    <p>Halo! Saya LibrAI, asisten untuk Dige Book. Ada yang bisa saya bantu?</p>
                </div>
            </div>
            <form id="chat-form" class="chat-form">
                @csrf
                <input type="text" id="chat-input" name="question" placeholder="Ketik pertanyaan Anda..." required>
                <button type="submit">
                    <i class="icon icon-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .chat-toggle-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #0d6efd;
        color: white;
        padding: 15px 20px;
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        z-index: 1000;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .chat-toggle-btn:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
    }

    .chat-toggle-btn span {
        margin-left: 8px;
        font-weight: 500;
    }

    .chat-modal {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 350px;
        height: 500px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        display: none;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background-color: #0d6efd;
        color: white;
    }

    .chat-header h4 {
        margin: 0;
        font-size: 1.1rem;
    }

    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
    }

    .chat-body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .chat-messages {
        flex-grow: 1;
        padding: 15px;
        overflow-y: auto;
    }

    .user-message, .bot-message {
        margin-bottom: 15px;
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 18px;
    }

    .user-message {
        background-color: #e6f2ff;
        margin-left: auto;
        border-bottom-right-radius: 5px;
    }

    .bot-message {
        background-color: #f1f1f1;
        margin-right: auto;
        border-bottom-left-radius: 5px;
    }

    .user-message p, .bot-message p {
        margin: 0;
    }

    .chat-form {
        display: flex;
        padding: 10px 15px;
        border-top: 1px solid #eee;
    }

    .chat-form input {
        flex-grow: 1;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 30px;
        outline: none;
    }

    .chat-form button {
        background-color: #0d6efd;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-visible {
        display: flex;
    }

    /* Loading indicator for when waiting for a response */
    .loading-indicator {
        display: flex;
        padding: 10px 15px;
    }

    .loading-dots {
        display: flex;
        align-items: center;
    }

    .loading-dots span {
        width: 8px;
        height: 8px;
        margin: 0 2px;
        background-color: #888;
        border-radius: 50%;
        display: inline-block;
        animation: dot-flashing 1s infinite alternate;
    }

    .loading-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .loading-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes dot-flashing {
        0% {
            opacity: 0.2;
        }
        100% {
            opacity: 1;
        }
    }
    
    /* Styling for formatted bot messages */
    .bot-message ul, .bot-message ol {
        margin: 5px 0 5px 20px;
        padding-left: 15px;
    }

    .bot-message li {
        margin-bottom: 5px;
    }

    .bot-message p {
        margin: 0 0 10px 0;
    }

    .bot-message strong {
        font-weight: bold;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatToggleBtn = document.getElementById('chat-toggle-btn');
        const chatModal = document.getElementById('chat-modal');
        const closeChat = document.getElementById('close-chat');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatMessages = document.getElementById('chat-messages');

        // Toggle chat modal
        chatToggleBtn.addEventListener('click', function() {
            chatModal.classList.toggle('chat-visible');
            if (chatModal.classList.contains('chat-visible')) {
                chatInput.focus();
            }
        });

        // Close chat modal
        closeChat.addEventListener('click', function() {
            chatModal.classList.remove('chat-visible');
        });

        // Handle chat form submission
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const question = chatInput.value.trim();
            if (!question) return;

            // Add user message to chat
            addMessage(question, 'user');
            chatInput.value = '';
            
            // Show loading indicator
            showLoadingIndicator();

            // Send request to server
            fetch('/api/ask-api', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value || 
                                    document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ question: question })
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading indicator and add bot response
                removeLoadingIndicator();
                addMessage(data.answer, 'bot');
            })
            .catch(error => {
                // Handle error
                removeLoadingIndicator();
                addMessage('Maaf, terjadi kesalahan saat memproses permintaan Anda.', 'bot');
                console.error('Error:', error);
            });
        });

        // Function to add a message to the chat with improved formatting
        function addMessage(message, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = sender === 'user' ? 'user-message' : 'bot-message';
            
            if (sender === 'bot') {
                // Process message formatting for bot responses only
                
                // Format bold text (replace **text** with <strong>text</strong>)
                message = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                
                // Format lists
                // First check if the message contains list items (numbered or bullet points)
                if (message.match(/^\d+\.\s+.+$/m) || message.match(/^-\s+.+$/m)) {
                    // Split the message into lines
                    const lines = message.split('\n');
                    let formattedMessage = '';
                    let inList = false;
                    let listType = '';
                    
                    for (let i = 0; i < lines.length; i++) {
                        const line = lines[i];
                        
                        // Check for numbered list item
                        if (line.match(/^\d+\.\s+.+$/)) {
                            if (!inList || listType !== 'ol') {
                                // Close previous list if it was a different type
                                if (inList) formattedMessage += '</ul>';
                                // Start a new ordered list
                                formattedMessage += '<ol>';
                                listType = 'ol';
                                inList = true;
                            }
                            // Add list item
                            formattedMessage += `<li>${line.replace(/^\d+\.\s+/, '')}</li>`;
                        }
                        // Check for bullet list item
                        else if (line.match(/^-\s+.+$/)) {
                            if (!inList || listType !== 'ul') {
                                // Close previous list if it was a different type
                                if (inList) formattedMessage += '</ol>';
                                // Start a new unordered list
                                formattedMessage += '<ul>';
                                listType = 'ul';
                                inList = true;
                            }
                            // Add list item
                            formattedMessage += `<li>${line.replace(/^-\s+/, '')}</li>`;
                        }
                        // Regular text
                        else {
                            // Close any open list
                            if (inList) {
                                formattedMessage += listType === 'ol' ? '</ol>' : '</ul>';
                                inList = false;
                            }
                            // Add regular paragraph with line breaks
                            formattedMessage += line + '<br>';
                        }
                    }
                    
                    // Close any remaining open list
                    if (inList) {
                        formattedMessage += listType === 'ol' ? '</ol>' : '</ul>';
                    }
                    
                    message = formattedMessage;
                } else {
                    // If there are no lists, just replace line breaks with <br>
                    message = message.replace(/\n/g, '<br>');
                }
            }
            
            // Use innerHTML instead of textContent to parse HTML tags
            const messagePara = document.createElement('p');
            messagePara.innerHTML = message;
            
            messageDiv.appendChild(messagePara);
            chatMessages.appendChild(messageDiv);
            
            // Scroll to the bottom of the chat
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Function to show loading indicator
        function showLoadingIndicator() {
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'bot-message loading-indicator';
            loadingDiv.id = 'loading-indicator';
            
            const loadingDots = document.createElement('div');
            loadingDots.className = 'loading-dots';
            
            for (let i = 0; i < 3; i++) {
                const dot = document.createElement('span');
                loadingDots.appendChild(dot);
            }
            
            loadingDiv.appendChild(loadingDots);
            chatMessages.appendChild(loadingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Function to remove loading indicator
        function removeLoadingIndicator() {
            const loadingIndicator = document.getElementById('loading-indicator');
            if (loadingIndicator) {
                loadingIndicator.remove();
            }
        }
    });
</script>