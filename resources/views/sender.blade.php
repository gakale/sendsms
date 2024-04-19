<!DOCTYPE html>
<html>
<head>
    <title>Envoyer un SMS</title>
</head>
<body>
    <h1>Envoyer un SMS</h1>
    <form action="{{ route('send-sms') }}" method="POST">
        @csrf
        <label for="api_key">Clé API :</label>
        <input type="text" name="api_key" id="api_key" required>
        <br>
        <label for="api_secret">Secret API :</label>
        <input type="text" name="api_secret" id="api_secret" required>
        <br>
        <label for="sender_id">ID de l'expéditeur :</label>
        <input type="text" name="sender_id" id="sender_id" required>
        <br>
        <label for="recipients">Destinataire(s) :</label>
        <input type="text" name="recipients" id="recipients" required>
        <br>
        <label for="message">Message :</label>
        <textarea name="message" id="message" required></textarea>
        <br>
        <label for="channel">Canal :</label>
        <select name="channel" id="channel" required>
            <option value="twilio">Twilio</option>
            <option value="nexmo">Nexmo</option>
        </select>
        <br>
        <button type="submit">Envoyer le SMS</button>
    </form>
</body>
</html>
