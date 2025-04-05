<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MPSChat with AI - DTEP</title>
  <!-- เชื่อมต่อ Google Fonts (ใช้ Kanit) -->
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Kanit', sans-serif;
      background-color: #f4f4f4;
      color: #333;
      line-height: 1.6;
    }
    /* Header */
    header {
      background-color: #fff;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ccc;
      animation: slideDown 1s ease-in-out;
    }
    @keyframes slideDown {
      from { transform: translateY(-100%); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .logo {
      display: flex;
      align-items: center;
    }
    .logo img {
      width: 50px;
      margin-right: 10px;
      transition: transform 0.3s ease;
    }
    .logo img:hover {
      transform: scale(1.1);
    }
    .logo h1 {
      font-size: 24px;
      color: #33CC66;
    }
    nav ul {
      list-style: none;
      display: flex;
    }
    nav ul li {
      margin: 0 15px;
    }
    nav ul li a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }
    .cta {
      background-color: #33CC66;
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
    }
    /* Chat Container */
    .chat-container {
      max-width: 800px;
      margin: 30px auto;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    .chat-header {
      background-color: #33CC66;
      color: #fff;
      padding: 15px;
      text-align: center;
      font-size: 20px;
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .chat-messages {
      padding: 20px;
      flex: 1;
      overflow-y: auto;
      height: 400px;
      background-color: #f9f9f9;
    }
    .chat-input {
      display: flex;
      border-top: 1px solid #ccc;
    }
    .chat-input input {
      flex: 1;
      padding: 15px;
      font-size: 16px;
      border: none;
      outline: none;
    }
    .chat-input button {
      padding: 15px 20px;
      border: none;
      background-color: #33CC66;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .chat-input button:hover {
      background-color: #2bb85a;
    }
    /* Chat Message Styles */
    .message {
      margin-bottom: 15px;
      animation: messageFadeIn 0.5s;
    }
    @keyframes messageFadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .message.user {
      text-align: right;
    }
    .message.bot {
      text-align: left;
    }
    .message p {
      display: inline-block;
      padding: 10px 15px;
      border-radius: 20px;
      max-width: 70%;
    }
    .message.user p {
      background-color: #33CC66;
      color: #fff;
    }
    .message.bot p {
      background-color: #e0e0e0;
      color: #333;
    }
    /* Footer */
    footer {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 20px;
      margin-top: 30px;
    }
    /* Responsive */
    @media (max-width: 768px) {
      .chat-container {
        margin: 20px;
      }
      nav ul {
        flex-direction: column;
        align-items: center;
      }
      nav ul li {
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo">
      <img src="DIGITAL TECHNOLOGY EXCELLENCE PROGRAM.png" alt="โลโก้ DTEP">
      <h1>DTEP</h1>
    </div>
    <nav>
      <ul>
        <li><a href="index.html">หน้าแรก</a></li>
        <li><a href="about.html">เกี่ยวกับ</a></li>
        <li><a href="courses.html">หลักสูตร</a></li>
        <li><a href="activities.html">กิจกรรม</a></li>
        <li><a href="staff.html">แนะนำบุคลากร</a></li>
        <li><a href="student.html">สำหรับนักเรียน</a></li>
      </ul>
    </nav>
    <a href="http://61.7.228.141/admission/index.php?r=register" class="cta">สมัครเรียน</a>
  </header>
  
  <!-- Chat AI Section -->
  <div class="chat-container">
    <div class="chat-header">MPSChat with AI</div>
    <div class="chat-messages" id="chatMessages">
      <!-- ข้อความแชทจะแสดงที่นี่ -->
    </div>
    <div class="chat-input">
      <input type="text" id="chatInput" placeholder="พิมพ์ข้อความของคุณที่นี่...">
      <button id="sendBtn">ส่ง</button>
    </div>
  </div>
  
  <!-- Footer -->
  <footer>
    <p>&copy; 2025 โรงเรียนแม่สายประสิทธิ์ศาสตร์ | DIGITAL TECHNOLOGY EXCELLENCE PROGRAM (DTEP)</p>
  </footer>
  
  <!-- JavaScript สำหรับระบบ Chat AI โดยใช้ Hugging Face Inference API กับโมเดล microsoft/DialoGPT-medium -->
  <script>
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');

    // ฟังก์ชันเรียก Hugging Face Inference API สำหรับโมเดลที่สามารถตอบคำถามได้
    async function getBotResponse(userMessage) {
      // ตัวอย่างใช้โมเดล microsoft/DialoGPT-medium ซึ่งเป็นโมเดลฟรีที่สามารถตอบคำถามได้
      const apiUrl = "https://api-inference.huggingface.co/models/microsoft/DialoGPT-medium";
      try {
        const response = await fetch(apiUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer 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"
          },
          body: JSON.stringify({
            inputs: userMessage,
            options: { wait_for_model: true }
          })
        });
        const data = await response.json();
        if (data.error) {
          console.error("API Error:", data.error);
          return "ขอโทษครับ ไม่สามารถตอบกลับได้ในขณะนี้";
        }
        if (Array.isArray(data) && data.length > 0 && data[0].generated_text) {
          return data[0].generated_text;
        } else if (data.generated_text) {
          return data.generated_text;
        } else {
          return "ขอโทษครับ ไม่สามารถตอบกลับได้ในขณะนี้";
        }
      } catch (error) {
        console.error("Error:", error);
        return "ขอโทษครับ เกิดข้อผิดพลาดในการตอบกลับ";
      }
    }

    // ฟังก์ชันเพิ่มข้อความลงในกล่องแชท
    function appendMessage(sender, message) {
      const messageElem = document.createElement('div');
      messageElem.classList.add('message', sender);
      const p = document.createElement('p');
      p.innerText = message;
      messageElem.appendChild(p);
      chatMessages.appendChild(messageElem);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // ฟังก์ชันส่งข้อความและรับการตอบกลับจาก AI
    async function sendMessage() {
      const userMessage = chatInput.value.trim();
      if (userMessage !== "") {
        appendMessage('user', userMessage);
        chatInput.value = "";
        const botResponse = await getBotResponse(userMessage);
        appendMessage('bot', botResponse);
      }
    }

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        sendMessage();
      }
    });
  </script>
</body>
</html>
