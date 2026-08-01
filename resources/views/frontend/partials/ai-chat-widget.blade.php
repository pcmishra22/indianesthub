{{-- ════════════════════════════════════════════
     AI CHAT ASSISTANT — floating widget
     Self-contained (CSS + JS inline) so it doesn't
     depend on any other stylesheet/build step.
════════════════════════════════════════════ --}}
<style>
    #ai-chat-launcher {
        position: fixed;
        left: 20px;
        bottom: 20px;
        z-index: 99999;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        border: none;
        box-shadow: 0 6px 18px rgba(29, 78, 216, .35);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 24px;
        transition: transform .15s ease;
    }
    #ai-chat-launcher:hover { transform: scale(1.06); }
    #ai-chat-launcher .ai-chat-badge {
        position: absolute; top: -4px; right: -4px;
        background: #16a34a; color: #fff; font-size: 10px;
        border-radius: 10px; padding: 1px 6px; font-weight: 600;
    }

    #ai-chat-panel {
        position: fixed;
        left: 20px;
        bottom: 88px;
        z-index: 99999;
        width: 340px;
        max-width: calc(100vw - 32px);
        height: 460px;
        max-height: calc(100vh - 140px);
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 12px 40px rgba(0,0,0,.2);
        display: none;
        flex-direction: column;
        overflow: hidden;
        font-family: inherit;
    }
    #ai-chat-panel.open { display: flex; }

    #ai-chat-panel .ai-chat-header {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff; padding: 12px 14px;
        display: flex; align-items: center; justify-content: space-between;
    }
    #ai-chat-panel .ai-chat-header .title { font-weight: 600; font-size: 14px; }
    #ai-chat-panel .ai-chat-header .subtitle { font-size: 11px; opacity: .85; }
    #ai-chat-panel .ai-chat-close { background: none; border: none; color: #fff; font-size: 18px; cursor: pointer; line-height: 1; }

    #ai-chat-messages {
        flex: 1; overflow-y: auto; padding: 12px; background: #f7f8fa;
    }
    .ai-chat-msg { margin-bottom: 10px; display: flex; }
    .ai-chat-msg.user { justify-content: flex-end; }
    .ai-chat-msg .bubble {
        max-width: 80%; padding: 8px 12px; border-radius: 12px; font-size: 13px; line-height: 1.4;
    }
    .ai-chat-msg.user .bubble { background: #2563eb; color: #fff; border-bottom-right-radius: 3px; }
    .ai-chat-msg.assistant .bubble { background: #fff; border: 1px solid #e5e7eb; color: #1f2937; border-bottom-left-radius: 3px; }
    .ai-chat-typing { font-size: 12px; color: #6b7280; padding: 4px 12px; }

    #ai-chat-form {
        display: flex; gap: 8px; padding: 10px; border-top: 1px solid #eee; background: #fff;
    }
    #ai-chat-input {
        flex: 1; border: 1px solid #d1d5db; border-radius: 20px; padding: 8px 14px;
        font-size: 13px; outline: none;
    }
    #ai-chat-input:focus { border-color: #2563eb; }
    #ai-chat-send {
        background: #2563eb; color: #fff; border: none; border-radius: 50%;
        width: 36px; height: 36px; flex-shrink: 0; cursor: pointer;
    }
    #ai-chat-send:disabled { opacity: .5; cursor: not-allowed; }

    @media (max-width: 767px) {
        #ai-chat-launcher { bottom: 76px; left: 14px; }
        #ai-chat-panel { bottom: 140px; left: 14px; }
    }
</style>

<button id="ai-chat-launcher" title="Ask our AI Property Assistant" aria-label="Open AI chat assistant">
    <i class="bi bi-robot"></i>
    <span class="ai-chat-badge">AI</span>
</button>

<div id="ai-chat-panel">
    <div class="ai-chat-header">
        <div>
            <div class="title">IndianEstHub Assistant</div>
            <div class="subtitle">Ask about properties, prices &amp; more</div>
        </div>
        <button class="ai-chat-close" id="ai-chat-close" aria-label="Close chat">&times;</button>
    </div>
    <div id="ai-chat-messages"></div>
    <form id="ai-chat-form">
        <input type="text" id="ai-chat-input" placeholder="Type your question..." autocomplete="off" maxlength="1000">
        <button type="submit" id="ai-chat-send"><i class="bi bi-send-fill"></i></button>
    </form>
</div>

<script>
(function () {
    const launcher   = document.getElementById('ai-chat-launcher');
    const panel      = document.getElementById('ai-chat-panel');
    const closeBtn   = document.getElementById('ai-chat-close');
    const messagesEl = document.getElementById('ai-chat-messages');
    const form       = document.getElementById('ai-chat-form');
    const input      = document.getElementById('ai-chat-input');
    const sendBtn    = document.getElementById('ai-chat-send');

    const csrfToken   = document.querySelector('meta[name="csrf-token"]')?.content;
    const propertyId  = document.querySelector('meta[name="ai-chat-property-id"]')?.content || '';
    const sendUrl     = "{{ route('ai-chat.send') }}";

    let sessionToken = localStorage.getItem('ieh_ai_chat_token');
    if (!sessionToken) {
        sessionToken = 'ai_' + Date.now() + '_' + Math.random().toString(36).slice(2, 12);
        localStorage.setItem('ieh_ai_chat_token', sessionToken);
    }

    let opened = false;

    function addMessage(role, text) {
        const wrap = document.createElement('div');
        wrap.className = 'ai-chat-msg ' + role;
        const bubble = document.createElement('div');
        bubble.className = 'bubble';
        bubble.textContent = text;
        wrap.appendChild(bubble);
        messagesEl.appendChild(wrap);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function showTyping() {
        const el = document.createElement('div');
        el.className = 'ai-chat-typing';
        el.id = 'ai-chat-typing-indicator';
        el.textContent = 'Assistant is typing...';
        messagesEl.appendChild(el);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function hideTyping() {
        document.getElementById('ai-chat-typing-indicator')?.remove();
    }

    launcher.addEventListener('click', function () {
        panel.classList.toggle('open');
        if (!opened) {
            opened = true;
            addMessage('assistant', "Hi! I'm your IndianEstHub AI assistant. Ask me about properties, pricing, or areas in Chandigarh, Mohali, Zirakpur or Panchkula — I'm happy to help.");
            input.focus();
        }
    });

    closeBtn.addEventListener('click', function () {
        panel.classList.remove('open');
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        addMessage('user', message);
        input.value = '';
        sendBtn.disabled = true;
        showTyping();

        fetch(sendUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                message: message,
                session_token: sessionToken,
                property_id: propertyId || null,
            }),
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            hideTyping();
            addMessage('assistant', data.reply || "Sorry, I couldn't respond. Please try again.");
        })
        .catch(function () {
            hideTyping();
            addMessage('assistant', "Sorry, something went wrong. Please try again or WhatsApp us.");
        })
        .finally(function () {
            sendBtn.disabled = false;
        });
    });
})();
</script>
